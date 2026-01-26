<?php
// app/Http/Controllers/UnitsController.php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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

        if ($request->ajax()) {
            return view('adminFolder.partials.units-grid', compact('units'))->render();
        }

        return view('adminFolder.rooms-cottages.rooms-cottages', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'unitName' => 'required|string|max:255',
            'unitType' => 'required|in:room,cottage,special',
            'description' => 'required|string',
            'capacity' => 'required|integer|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'unitRatePrice' => 'required|numeric|min:0',
            'unitStatus' => 'required|in:available,maintenance,blocked',
            'blockStartDate' => 'nullable|date',
            'blockEndDate' => 'nullable|date|after_or_equal:blockStartDate',
            'blockReason' => 'nullable|string',
            'for_special_events' => 'sometimes|boolean',
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
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'unitRatePrice' => 'required|numeric|min:0',
            'unitStatus' => 'required|in:available,maintenance,blocked',
            'blockStartDate' => 'nullable|date',
            'blockEndDate' => 'nullable|date|after_or_equal:blockStartDate',
            'blockReason' => 'nullable|string',
            'for_special_events' => 'sometimes|boolean',
        ]);

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
            'unitRatePrice' => $request->unitRatePrice,
            'unitStatus' => $request->unitStatus,
            'blockStartDate' => $blockStartDate,
            'blockEndDate' => $blockEndDate,
            'blockReason' => $blockReason,
            'for_special_events' => $request->has('for_special_events') ? true : false,
        ]);

        return redirect()->route('admin.rooms-cottages')->with('success', 'Unit updated successfully!');
    }

    /**
     * Delete a unit and all its associated images
     */
    public function destroy($id)
    {
        try {
            $unit = Unit::findOrFail($id);
            
            // 1. I-delete muna ang mga related cart items
            CartItem::where('unitID', $id)->delete();
            
            // 2. Delete all images from storage
            if ($unit->images && is_array($unit->images)) {
                foreach ($unit->images as $imagePath) {
                    // Check if image exists in storage before deleting
                    if (Storage::disk('public')->exists($imagePath)) {
                        Storage::disk('public')->delete($imagePath);
                        Log::info("Deleted image: {$imagePath}");
                    } else {
                        Log::warning("Image not found in storage: {$imagePath}");
                    }
                }
            }
            
            // 3. Delete the unit record from database
            $unit->delete();

            return redirect()->route('admin.rooms-cottages')
                ->with('success', 'Unit and all associated images deleted successfully!');
                
        } catch (\Exception $e) {
            Log::error('Error deleting unit: ' . $e->getMessage());
            return redirect()->route('admin.rooms-cottages')
                ->with('error', 'Error deleting unit: ' . $e->getMessage());
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

            return redirect()->route('admin.rooms-cottages')
                ->with('success', "{$updatedCount} unit(s) unblocked successfully!");
                
        } catch (\Exception $e) {
            Log::error('Error unblocking units: ' . $e->getMessage());
            return redirect()->route('admin.rooms-cottages')
                ->with('error', 'Error unblocking units: ' . $e->getMessage());
        }
    }
    /**
     * Block multiple units with date range
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
            $updatedCount = Unit::whereIn('unitID', $request->unitIDs)->update([
                'unitStatus' => 'blocked',
                'blockStartDate' => $request->blockStartDate,
                'blockEndDate' => $request->blockEndDate,
                'blockReason' => $request->blockReason,
            ]);

            return redirect()->route('admin.rooms-cottages')
                ->with('success', "{$updatedCount} unit(s) blocked successfully!");
                
        } catch (\Exception $e) {
            Log::error('Error blocking units: ' . $e->getMessage());
            return redirect()->route('admin.rooms-cottages')
                ->with('error', 'Error blocking units: ' . $e->getMessage());
        }
    }
}