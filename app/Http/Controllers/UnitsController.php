<?php

// app/Http/Controllers/UnitsController.php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Booking;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class UnitsController extends Controller
{
    public function index(Request $request)
    {
        $query = Unit::query();

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('unitName', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Status filter
        if ($request->has('status') && $request->status != '') {
            $query->where('unitStatus', $request->status);
        }

        // Type filter
        if ($request->has('type') && $request->type != '') {
            $query->where('unitType', $request->type);
        }

        $units = $query->paginate(12);

        // ===== DATE FILTER: Find booked unit IDs on the selected date =====
        $bookedUnitIds = collect();
        $filterDate = null;

        if ($request->has('date') && $request->date != '') {
            $filterDate = Carbon::parse($request->date)->format('Y-m-d');

            // Normal bookings (day-use, overnight): checkInDate <= date <= checkOutDate
            $normalCartIds = Booking::whereIn('bookingStatus', ['confirmed', 'pending'])
                ->whereIn('bookingType', ['day-use', 'overnight'])
                ->whereHas('cart', function ($q) use ($filterDate) {
                    $q->where('checkInDate', '<=', $filterDate)
                      ->where('checkOutDate', '>=', $filterDate);
                })
                ->pluck('cartID');

            // Special event bookings: eventStartTime <= date <= eventEndTime
            $specialCartIds = Booking::whereIn('bookingStatus', ['confirmed', 'pending'])
                ->where('bookingType', 'special-event')
                ->whereNotNull('eventStartTime')
                ->whereNotNull('eventEndTime')
                ->whereDate('eventStartTime', '<=', $filterDate)
                ->whereDate('eventEndTime', '>=', $filterDate)
                ->pluck('cartID');

            $allCartIds = $normalCartIds->merge($specialCartIds)->unique();

            $bookedUnitIds = CartItem::whereIn('cartID', $allCartIds)
                ->pluck('unitID')
                ->unique();
        }
        // ===== END DATE FILTER =====

        if ($request->ajax()) {
            return view('adminFolder.partials.units-grid', compact('units', 'bookedUnitIds', 'filterDate'))->render();
        }

        return view('adminFolder.rooms-cottages.rooms-cottages', compact('units', 'bookedUnitIds', 'filterDate'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'unitName' => 'required|string|max:255',
            'unitType' => 'required|in:room,cottage,special',
            'description' => 'required|string',
            'capacity' => 'required|integer|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
            'unitRatePrice' => 'required|numeric|min:0',
            'unitStatus' => 'required|in:available,maintenance,blocked',
            'blockStartDate' => 'nullable|date',
            'blockEndDate' => 'nullable|date|after_or_equal:blockStartDate',
            'blockReason' => 'nullable|string',
            'for_special_events' => 'sometimes|boolean',
            'virtualTourPanorama' => 'nullable|string|max:255',
        ]);

        $imagePaths = [];
        
        // I-upload ang mga images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('unit-images', 'public');
                $imagePaths[] = $path;
            }
        }

        // Convert empty strings to null for nullable fields
        $blockStartDate = $request->blockStartDate ?: null;
        $blockEndDate = $request->blockEndDate ?: null;
        $blockReason = $request->blockReason ?: null;

        $unit = Unit::create([
            'unitName' => $request->unitName,
            'unitType' => $request->unitType,
            'description' => $request->description,
            'capacity' => $request->capacity,
            'images' => $imagePaths,
            'unitRatePrice' => $request->unitRatePrice,
            'unitStatus' => $request->unitStatus,
            'blockStartDate' => $blockStartDate,
            'blockEndDate' => $blockEndDate,
            'blockReason' => $blockReason,
            'for_special_events' => $request->has('for_special_events') ? true : false,
        ]);

        return redirect()->route('admin.rooms-cottages')->with('success', 'Unit added successfully!');
    }
    
    public function editForm($id)
    {
        $unit = Unit::findOrFail($id);
        return view('adminFolder.rooms-cottages.modals.edit-form', compact('unit'));
    }

    public function edit($id)
    {
        $unit = Unit::findOrFail($id);
        
        // Convert images to full URLs for the frontend
        $unitData = $unit->toArray();
        $unitData['image_urls'] = $unit->image_urls;
        
        return response()->json($unitData);
    }

    public function update(Request $request, $id)
    {
        $unit = Unit::findOrFail($id);

        $request->validate([
            'unitName' => 'required|string|max:255',
            'unitType' => 'required|in:room,cottage,special',
            'description' => 'required|string',
            'capacity' => 'required|integer|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
            'unitRatePrice' => 'required|numeric|min:0',
            'unitStatus' => 'required|in:available,maintenance,blocked',
            'blockStartDate' => 'nullable|date',
            'blockEndDate' => 'nullable|date|after_or_equal:blockStartDate',
            'blockReason' => 'nullable|string',
            'for_special_events' => 'sometimes|boolean',
            'virtualTourPanorama' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // ✅ VALIDATION: If changing status to blocked, check for booking conflicts
            if ($request->unitStatus === 'blocked' && $request->blockStartDate && $request->blockEndDate) {
                $blockStartDate = Carbon::parse($request->blockStartDate)->format('Y-m-d');
                $blockEndDate = Carbon::parse($request->blockEndDate)->format('Y-m-d');

                // Check for normal bookings
                $hasNormalBooking = $this->checkNormalBookingConflict($id, $blockStartDate, $blockEndDate);
                
                // Check for special event bookings
                $hasSpecialEventBooking = $this->checkSpecialEventConflict($id, $blockStartDate, $blockEndDate);

                if ($hasNormalBooking || $hasSpecialEventBooking) {
                    DB::rollBack();
                    
                    $conflictTypes = [];
                    if ($hasNormalBooking) $conflictTypes[] = 'normal booking';
                    if ($hasSpecialEventBooking) $conflictTypes[] = 'special event';
                    
                    $errorMessage = 'Cannot block the following units because they have confirmed or pending bookings during the selected dates: ' . 
                                   $unit->unitName . ' (' . implode(', ', $conflictTypes) . ')';

                    Log::warning("Update failed - Booking conflict for unit {$id}: " . implode(', ', $conflictTypes));

                    return redirect()->route('admin.rooms-cottages')
                        ->with('error', $errorMessage);
                }
            }

            $imagePaths = $unit->images ?? [];

            // I-upload ang mga bagong images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('unit-images', 'public');
                    $imagePaths[] = $path;
                }
            }

            // Convert empty strings to null for nullable fields
            $blockStartDate = $request->blockStartDate ?: null;
            $blockEndDate = $request->blockEndDate ?: null;
            $blockReason = $request->blockReason ?: null;

            $unit->update([
                'unitName' => $request->unitName,
                'unitType' => $request->unitType,
                'description' => $request->description,
                'capacity' => $request->capacity,
                'images' => $imagePaths,
                'virtualTourPanorama' => $request->virtualTourPanorama,
                'unitRatePrice' => $request->unitRatePrice,
                'unitStatus' => $request->unitStatus,
                'blockStartDate' => $blockStartDate,
                'blockEndDate' => $blockEndDate,
                'blockReason' => $blockReason,
                'for_special_events' => $request->has('for_special_events') ? true : false,
            ]);

            DB::commit();

            return redirect()->route('admin.rooms-cottages')->with('success', 'Unit updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating unit: ' . $e->getMessage());
            return redirect()->route('admin.rooms-cottages')
                ->with('error', 'Error updating unit: ' . $e->getMessage());
        }
    }

    /**
     * ✅ NEW: Check booking status before allowing delete
     */
    public function checkBookingStatus($id)
    {
        try {
            $unit = Unit::findOrFail($id);
            
            // Get all active bookings (pending or confirmed) for this unit
            $activeBookings = Booking::whereHas('cart.cartItems', function($q) use ($id) {
                    $q->where('unitID', $id);
                })
                ->whereIn('bookingStatus', ['pending', 'confirmed'])
                ->with(['cart.user'])
                ->get();

            $bookingDetails = $activeBookings->map(function($booking) {
                $guestName = $booking->cart->user->name ?? 'Unknown';
                
                if ($booking->bookingType === 'special-event') {
                    return [
                        'status' => $booking->bookingStatus,
                        'guest_name' => $guestName,
                        'event_start' => Carbon::parse($booking->eventStartTime)->format('M d, Y'),
                        'event_end' => Carbon::parse($booking->eventEndTime)->format('M d, Y'),
                        'type' => 'special-event'
                    ];
                } else {
                    return [
                        'status' => $booking->bookingStatus,
                        'guest_name' => $guestName,
                        'check_in' => Carbon::parse($booking->cart->checkInDate)->format('M d, Y'),
                        'check_out' => Carbon::parse($booking->cart->checkOutDate)->format('M d, Y'),
                        'type' => $booking->bookingType
                    ];
                }
            });

            return response()->json([
                'can_delete' => $activeBookings->isEmpty(),
                'active_bookings_count' => $activeBookings->count(),
                'bookings' => $bookingDetails
            ]);

        } catch (\Exception $e) {
            Log::error('Error checking booking status: ' . $e->getMessage());
            return response()->json([
                'can_delete' => false,
                'error' => 'Error checking booking status'
            ], 500);
        }
    }

    /**
     * ✅ UPDATED: Delete a unit with proper validation
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $unit = Unit::findOrFail($id);
            
            // ✅ SERVER-SIDE VALIDATION: Check for active bookings
            $activeBookings = Booking::whereHas('cart.cartItems', function($q) use ($id) {
                    $q->where('unitID', $id);
                })
                ->whereIn('bookingStatus', ['pending', 'confirmed'])
                ->count();

            if ($activeBookings > 0) {
                DB::rollBack();
                Log::warning("Cannot delete unit {$id} - Has {$activeBookings} active booking(s)");
                
                return redirect()->route('admin.rooms-cottages')
                    ->with('error', "Cannot delete {$unit->unitName}. This unit has {$activeBookings} active booking(s). Please wait until all bookings are completed or cancelled.");
            }
            
            // 1. Delete related cart items (only from inactive/cancelled bookings)
            CartItem::where('unitID', $id)
                ->whereHas('cart', function($q) {
                    $q->where('is_active', false);
                })
                ->delete();
            
            // 2. Delete all images from storage
            if ($unit->images && is_array($unit->images)) {
                foreach ($unit->images as $imagePath) {
                    if (Storage::disk('public')->exists($imagePath)) {
                        Storage::disk('public')->delete($imagePath);
                        Log::info("Deleted image: {$imagePath}");
                    }
                }
            }
            
            // 3. Delete the unit record
            $unit->delete();

            DB::commit();
            
            Log::info("Successfully deleted unit {$id}: {$unit->unitName}");

            return redirect()->route('admin.rooms-cottages')
                ->with('success', 'Unit and all associated images deleted successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting unit: ' . $e->getMessage());
            return redirect()->route('admin.rooms-cottages')
                ->with('error', 'Cannot delete this unit because it is currently in a guest\'s cart. Please wait until the cart is cleared or the booking is completed.');
        }
    }

    /**
     * Delete a single image from a unit
     */
    public function deleteImage(Request $request, $id)
    {
        try {
            $unit = Unit::findOrFail($id);
            $imageToDelete = $request->image_path;
            
            // Get current images array
            $images = $unit->images;
            
            if (!is_array($images)) {
                return response()->json([
                    'success' => false, 
                    'message' => 'No images found'
                ], 400);
            }
            
            // Find the image in the array
            $key = array_search($imageToDelete, $images);
            
            if ($key !== false) {
                // Delete from storage
                if (Storage::disk('public')->exists($imageToDelete)) {
                    Storage::disk('public')->delete($imageToDelete);
                    Log::info("Deleted single image: {$imageToDelete}");
                }
                
                // Remove from array
                unset($images[$key]);
                $images = array_values($images); // Re-index array
                
                // Update unit with new images array
                $unit->update(['images' => $images]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Image deleted successfully',
                    'remaining_images' => count($images)
                ]);
            }
            
            return response()->json([
                'success' => false, 
                'message' => 'Image not found in unit'
            ], 404);
            
        } catch (\Exception $e) {
            Log::error('Error deleting image: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Error deleting image: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Block multiple units with date range - WITH BOOKING CONFLICT VALIDATION
     */
    public function blockDates(Request $request)
    {
        $request->validate([
            'unitIDs' => 'required|array',
            'unitIDs.*' => 'exists:units,unitID',
            'blockStartDate' => 'required|date',
            'blockEndDate' => 'required|date|after_or_equal:blockStartDate',
            'blockReason' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $unitIDs = $request->unitIDs;
            $blockStartDate = Carbon::parse($request->blockStartDate)->format('Y-m-d');
            $blockEndDate = Carbon::parse($request->blockEndDate)->format('Y-m-d');
            $blockReason = $request->blockReason;

            // ✅ VALIDATION: Check for booking conflicts
            $conflictingUnits = [];
            
            foreach ($unitIDs as $unitID) {
                $unit = Unit::find($unitID);
                if (!$unit) continue;

                // Check for normal bookings (day-use and overnight)
                $hasNormalBooking = $this->checkNormalBookingConflict($unitID, $blockStartDate, $blockEndDate);
                
                // Check for special event bookings
                $hasSpecialEventBooking = $this->checkSpecialEventConflict($unitID, $blockStartDate, $blockEndDate);

                if ($hasNormalBooking || $hasSpecialEventBooking) {
                    $conflictingUnits[] = [
                        'unit_name' => $unit->unitName,
                        'unit_id' => $unitID,
                        'has_normal_booking' => $hasNormalBooking,
                        'has_special_event' => $hasSpecialEventBooking
                    ];
                }
            }

            // If there are conflicts, return error with details
            if (!empty($conflictingUnits)) {
                DB::rollBack();
                
                // Format unit names with their conflict types
                $unitsList = collect($conflictingUnits)->map(function($conflict) {
                    $types = [];
                    if ($conflict['has_normal_booking']) $types[] = 'normal booking';
                    if ($conflict['has_special_event']) $types[] = 'special event';
                    return $conflict['unit_name'] . ' (' . implode(', ', $types) . ')';
                })->join(', ');

                Log::warning('Block dates failed - Booking conflicts: ' . $unitsList);

                return redirect()->route('admin.rooms-cottages')
                    ->with('error', 
                        'Cannot block the following units because they have confirmed or pending bookings during the selected dates: ' . $unitsList
                    );
            }

            // If no conflicts, proceed to block
            $updatedCount = Unit::whereIn('unitID', $unitIDs)->update([
                'unitStatus' => 'blocked',
                'blockStartDate' => $blockStartDate,
                'blockEndDate' => $blockEndDate,
                'blockReason' => $blockReason,
            ]);

            DB::commit();

            Log::info("Successfully blocked {$updatedCount} unit(s) from {$blockStartDate} to {$blockEndDate}");

            return redirect()->route('admin.rooms-cottages')
                ->with('success', "{$updatedCount} unit(s) blocked successfully from {$blockStartDate} to {$blockEndDate}!");
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error blocking units: ' . $e->getMessage());
            return redirect()->route('admin.rooms-cottages')
                ->with('error', 'Error blocking units: ' . $e->getMessage());
        }
    }

    /**
     * Check if unit has normal booking conflicts (day-use or overnight)
     */
    private function checkNormalBookingConflict($unitId, $blockStartDate, $blockEndDate)
    {
        $blockStart = Carbon::parse($blockStartDate)->startOfDay();
        $blockEnd = Carbon::parse($blockEndDate)->startOfDay();

        // Get all confirmed/pending normal bookings for this unit
        $bookings = Booking::whereHas('cart.cartItems', function($q) use ($unitId) {
                $q->where('unitID', $unitId);
            })
            ->whereIn('bookingStatus', ['confirmed', 'pending'])
            ->whereIn('bookingType', ['day-use', 'overnight'])
            ->with('cart')
            ->get();

        foreach ($bookings as $booking) {
            if (!$booking->cart) continue;

            $bookingStart = Carbon::parse($booking->cart->checkInDate)->startOfDay();
            $bookingEnd = Carbon::parse($booking->cart->checkOutDate)->startOfDay();

            // Check for date overlap
            $hasOverlap = (
                ($blockStart->between($bookingStart, $bookingEnd)) ||
                ($blockEnd->between($bookingStart, $bookingEnd)) ||
                ($bookingStart->between($blockStart, $blockEnd)) ||
                ($bookingEnd->between($blockStart, $blockEnd)) ||
                ($blockStart->eq($bookingStart) && $blockEnd->eq($bookingEnd))
            );

            if ($hasOverlap) {
                Log::info("Normal booking conflict found for unit {$unitId}: Booking #{$booking->bookingID} ({$bookingStart->format('Y-m-d')} to {$bookingEnd->format('Y-m-d')})");
                return true;
            }
        }

        return false;
    }

    /**
     * Check if unit has special event booking conflicts
     */
    private function checkSpecialEventConflict($unitId, $blockStartDate, $blockEndDate)
    {
        $blockStart = Carbon::parse($blockStartDate)->startOfDay();
        $blockEnd = Carbon::parse($blockEndDate)->endOfDay();

        // Get all confirmed/pending special event bookings for this unit
        $bookings = Booking::whereHas('cart.cartItems', function($q) use ($unitId) {
                $q->where('unitID', $unitId);
            })
            ->whereIn('bookingStatus', ['confirmed', 'pending'])
            ->where('bookingType', 'special-event')
            ->whereNotNull('eventStartTime')
            ->whereNotNull('eventEndTime')
            ->get();

        foreach ($bookings as $booking) {
            $eventStart = Carbon::parse($booking->eventStartTime)->startOfDay();
            $eventEnd = Carbon::parse($booking->eventEndTime)->endOfDay();

            // Check for date overlap
            $hasOverlap = (
                ($blockStart->between($eventStart, $eventEnd)) ||
                ($blockEnd->between($eventStart, $eventEnd)) ||
                ($eventStart->between($blockStart, $blockEnd)) ||
                ($eventEnd->between($blockStart, $blockEnd)) ||
                ($blockStart->lte($eventStart) && $blockEnd->gte($eventEnd))
            );

            if ($hasOverlap) {
                Log::info("Special event conflict found for unit {$unitId}: Booking #{$booking->bookingID} ({$eventStart->format('Y-m-d')} to {$eventEnd->format('Y-m-d')})");
                return true;
            }
        }

        return false;
    }

    /**
     * Unblock multiple units
     */
    public function unblockDates(Request $request)
    {
        $request->validate([
            'unitIDs' => 'required|array',
            'unitIDs.*' => 'exists:units,unitID',
        ]);

        try {
            $updatedCount = Unit::whereIn('unitID', $request->unitIDs)->update([
                'unitStatus' => 'available',
                'blockStartDate' => null,
                'blockEndDate' => null,
                'blockReason' => null,
            ]);

            Log::info("Successfully unblocked {$updatedCount} unit(s)");

            return redirect()->route('admin.rooms-cottages')
                ->with('success', "{$updatedCount} unit(s) unblocked successfully!");
                
        } catch (\Exception $e) {
            Log::error('Error unblocking units: ' . $e->getMessage());
            return redirect()->route('admin.rooms-cottages')
                ->with('error', 'Error unblocking units: ' . $e->getMessage());
        }
    }
}