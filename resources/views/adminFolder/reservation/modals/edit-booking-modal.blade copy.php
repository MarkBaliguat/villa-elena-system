<!-- Edit Booking Modal -->
<div id="editBookingModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-800">Edit Booking</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        
        <form id="editBookingForm" class="p-6">
            <input type="hidden" name="booking_id" id="edit_booking_id">
            <input type="hidden" name="original_email" id="original_email">
            <input type="hidden" name="original_guest_name" id="original_guest_name">
            
            <div class="grid grid-cols-2 gap-4 mb-6">
                <!-- Guest Information -->
                <div class="col-span-2">
                    <h4 class="text-lg font-medium text-gray-800 mb-3">Guest Information</h4>
                </div>
                
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Guest Name</label>
                    <input type="text" name="guest_name" id="edit_guest_name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-gray-100 disabled:text-gray-600"
                           oninput="checkIfFieldHasValue(this, 'name')">
                    <div id="name-change-warning" class="mt-1 text-sm text-amber-600 hidden">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        Name cannot be changed once booking is created
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="edit_email" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-gray-100 disabled:text-gray-600"
                           oninput="checkIfFieldHasValue(this, 'email')">
                    <div id="email-change-warning" class="mt-1 text-sm text-amber-600 hidden">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        Email cannot be changed once booking is created
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="phone" id="edit_phone" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Booking Details -->
                <div class="col-span-2 mt-4">
                    <h4 class="text-lg font-medium text-gray-800 mb-3">Booking Details</h4>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Booking Status</label>
                    <select name="booking_status" id="edit_booking_status" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Number of Guests</label>
                    <input type="number" name="num_guests" id="edit_num_guests" min="1" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Check-in Date</label>
                    <input type="date" name="checkin_date" id="edit_checkin_date" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Check-out Date</label>
                    <input type="date" name="checkout_date" id="edit_checkout_date"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Total Price</label>
                    <input type="number" name="total_price" id="edit_total_price" step="0.01" min="0" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Additional fields for cancellation -->
                <div id="cancellation_fields" class="col-span-2 hidden">
                    <h4 class="text-lg font-medium text-gray-800 mb-3">Cancellation Details</h4>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cancellation Reason</label>
                        <input type="text" name="cancellation_reason" id="edit_cancellation_reason"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Optional reason for cancellation">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Refund Amount</label>
                            <input type="number" name="refund_amount" id="edit_refund_amount" step="0.01" min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   value="0">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Refund Method</label>
                            <select name="refund_method" id="edit_refund_method"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select refund method</option>
                                <option value="cash">Cash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="gcash">GCash</option>
                                <option value="credit_card">Credit Card Reversal</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-sm text-yellow-700">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <strong>Note:</strong> Changing status to "Cancelled" will trigger an email notification to the guest.
                        </p>
                    </div>
                </div>

                <!-- Additional note for completed status -->
                <div id="completed_note" class="col-span-2 hidden">
                    <div class="mt-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                        <p class="text-sm text-green-700">
                            <i class="fas fa-check-circle mr-2"></i>
                            <strong>Note:</strong> Changing status to "Completed" will trigger a thank you email to the guest.
                        </p>
                    </div>
                </div>
                
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Special Requirements</label>
                    <textarea name="special_requirements" id="edit_special_requirements" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Any special requirements or notes..."></textarea>
                </div>
            </div>
            
            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeEditModal()"
                        class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="submit" id="updateBookingBtn"
                        class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed">
                    <span id="updateText">Update Booking</span>
                    <span id="updateSpinner" class="hidden">
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        Updating...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// ============================================
// EDIT BOOKING MODAL SCRIPTS - WITH ASYNC CLOSE + SWEETALERT2
// ============================================

let originalName = '';
let originalEmail = '';
let isNameLocked = false;
let isEmailLocked = false;
let originalBookingType = '';
let originalUnitId = '';
let originalBookingId = '';

// Show/hide loading state for update button
function showUpdateLoading() {
    const updateBtn = document.getElementById('updateBookingBtn');
    const updateText = document.getElementById('updateText');
    const updateSpinner = document.getElementById('updateSpinner');
    
    if (updateBtn && updateText && updateSpinner) {
        updateBtn.disabled = true;
        updateText.classList.add('hidden');
        updateSpinner.classList.remove('hidden');
    }
}

function hideUpdateLoading() {
    const updateBtn = document.getElementById('updateBookingBtn');
    const updateText = document.getElementById('updateText');
    const updateSpinner = document.getElementById('updateSpinner');
    
    if (updateBtn && updateText && updateSpinner) {
        updateBtn.disabled = false;
        updateText.classList.remove('hidden');
        updateSpinner.classList.add('hidden');
    }
}

function openEditModal() {
    document.getElementById('editBookingModal').classList.remove('hidden');
    document.getElementById('editBookingModal').classList.add('flex');
    
    // Setup phone validation for edit form
    setupPhoneValidation('edit_phone');
    
    // Add event listener for outside click
    setTimeout(() => {
        document.addEventListener('click', handleEditOutsideClick);
    }, 100);
}

// ✅ PROMISE-BASED MODAL CLOSE (ensures modal closes BEFORE SweetAlert shows)
function closeEditModal() {
    return new Promise((resolve) => {
        const modal = document.getElementById('editBookingModal');
        
        // Start close animation
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        
        // Clear phone validation styling
        const phoneInput = document.getElementById('edit_phone');
        if (phoneInput) {
            phoneInput.classList.remove('border-red-500', 'border-green-500');
            const errorDiv = phoneInput.parentNode.querySelector('.phone-error');
            if (errorDiv) errorDiv.remove();
        }
        
        // Reset field states
        resetFieldProtection();
        
        // Hide additional fields
        document.getElementById('cancellation_fields').classList.add('hidden');
        document.getElementById('completed_note').classList.add('hidden');
        
        // Clear conflict messages
        clearEditConflictMessage();
        
        // Hide loading state
        hideUpdateLoading();
        
        // Remove event listener
        document.removeEventListener('click', handleEditOutsideClick);
        
        // ✅ Wait for CSS animation to complete (300ms)
        setTimeout(() => {
            resolve(); // Modal is now fully closed
        }, 300);
    });
}

// Handle outside click for edit modal
function handleEditOutsideClick(event) {
    const modal = document.getElementById('editBookingModal');
    const modalContent = modal.querySelector('.bg-white');
    if (!modalContent.contains(event.target)) closeEditModal();
}

// Handle booking status change
document.getElementById('edit_booking_status').addEventListener('change', function() {
    const status = this.value;
    const cancellationFields = document.getElementById('cancellation_fields');
    const completedNote = document.getElementById('completed_note');
    
    // Show/hide cancellation fields
    if (status === 'cancelled') {
        cancellationFields.classList.remove('hidden');
        completedNote.classList.add('hidden');
        
        // Auto-fill refund amount based on net paid
        const netPaid = parseFloat(document.getElementById('payment_net_paid')?.value || 0);
        if (netPaid > 0) {
            document.getElementById('edit_refund_amount').value = netPaid.toFixed(2);
        }
    } else if (status === 'completed') {
        cancellationFields.classList.add('hidden');
        completedNote.classList.remove('hidden');
    } else {
        cancellationFields.classList.add('hidden');
        completedNote.classList.add('hidden');
    }
});

// Check if field has value and lock it
function checkIfFieldHasValue(inputElement, fieldType) {
    if (fieldType === 'name') {
        const currentValue = inputElement.value.trim();
        if (originalName && currentValue !== originalName) {
            // Revert to original value
            inputElement.value = originalName;
            
            // ✅ SWEETALERT WARNING
            Swal.fire({
                icon: 'warning',
                title: 'Field Locked',
                text: 'Guest name cannot be changed once booking is created',
                confirmButtonColor: '#f59e0b',
                timer: 2000,
                timerProgressBar: true
            });
            
            // Show inline warning
            document.getElementById('name-change-warning').classList.remove('hidden');
            
            // Highlight field temporarily
            inputElement.classList.add('border-amber-500');
            setTimeout(() => {
                inputElement.classList.remove('border-amber-500');
            }, 1000);
        } else {
            document.getElementById('name-change-warning').classList.add('hidden');
        }
    } else if (fieldType === 'email') {
        const currentValue = inputElement.value.trim();
        if (originalEmail && currentValue !== originalEmail) {
            // Revert to original value
            inputElement.value = originalEmail;
            
            // ✅ SWEETALERT WARNING
            Swal.fire({
                icon: 'warning',
                title: 'Field Locked',
                text: 'Email address cannot be changed once booking is created',
                confirmButtonColor: '#f59e0b',
                timer: 2000,
                timerProgressBar: true
            });
            
            // Show inline warning
            document.getElementById('email-change-warning').classList.remove('hidden');
            
            // Highlight field temporarily
            inputElement.classList.add('border-amber-500');
            setTimeout(() => {
                inputElement.classList.remove('border-amber-500');
            }, 1000);
        } else {
            document.getElementById('email-change-warning').classList.add('hidden');
        }
    }
}

// Reset field protection states
function resetFieldProtection() {
    originalName = '';
    originalEmail = '';
    originalBookingType = '';
    originalUnitId = '';
    originalBookingId = '';
    isNameLocked = false;
    isEmailLocked = false;
    
    // Hide warnings
    document.getElementById('name-change-warning').classList.add('hidden');
    document.getElementById('email-change-warning').classList.add('hidden');
}

// Format date for HTML date input (YYYY-MM-DD)
function formatDateForInput(dateString) {
    if (!dateString) return '';
    
    const date = new Date(dateString);
    
    // Check if date is valid
    if (isNaN(date.getTime())) {
        console.error('Invalid date:', dateString);
        return '';
    }
    
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    
    return `${year}-${month}-${day}`;
}

// Clear edit conflict message
function clearEditConflictMessage() {
    const conflictMessage = document.getElementById('edit-conflict-message');
    if (conflictMessage) {
        conflictMessage.remove();
    }
    const submitBtn = document.getElementById('editBookingForm').querySelector('button[type="submit"]');
    if (submitBtn) {
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    }
}

// Create edit conflict message element
function createEditConflictMessage() {
    const messageDiv = document.createElement('div');
    messageDiv.id = 'edit-conflict-message';
    const form = document.getElementById('editBookingForm');
    const lastField = form.querySelector('.grid > .col-span-2:last-of-type');
    lastField.parentNode.insertBefore(messageDiv, lastField.nextSibling);
    return messageDiv;
}

// Check conflict when editing dates
function checkEditDateConflict() {
    const bookingId = document.getElementById('edit_booking_id').value;
    const unitId = originalUnitId; // Use original unit ID since unit cannot be changed
    const checkinDate = document.getElementById('edit_checkin_date').value;
    const checkoutDate = document.getElementById('edit_checkout_date').value;
    const bookingType = document.getElementById('edit_booking_type')?.value || originalBookingType;

    console.log('=== EDIT CONFLICT CHECK START ===');
    console.log('Booking ID:', bookingId);
    console.log('Unit ID:', unitId);
    console.log('Checkin Date:', checkinDate);
    console.log('Checkout Date:', checkoutDate);
    console.log('Booking Type:', bookingType);

    if (!unitId || !checkinDate) {
        console.log('Missing unit or checkin date - skipping check');
        return;
    }

    // For day-use, use checkin date as checkout date
    const finalCheckoutDate = (bookingType === 'day-use') ? checkinDate : (checkoutDate || checkinDate);

    console.log('Final Checkout Date:', finalCheckoutDate);

    const params = new URLSearchParams({
        unit_id: unitId,
        checkin_date: checkinDate,
        checkout_date: finalCheckoutDate,
        booking_type: bookingType,
        exclude_booking_id: bookingId // Exclude current booking from conflict check
    });

    const url = `/admin/bookings/check-availability?${params}`;
    console.log('Fetching URL:', url);

    fetch(url)
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            
            const submitBtn = document.getElementById('editBookingForm').querySelector('button[type="submit"]');
            const conflictMessage = document.getElementById('edit-conflict-message') || createEditConflictMessage();
            
            // Format dates for display
            const checkinFormatted = new Date(checkinDate).toLocaleDateString('en-US', { 
                month: 'short', 
                day: 'numeric', 
                year: 'numeric' 
            });
            
            let checkoutFormatted = checkinFormatted;
            if (bookingType === 'overnight' && finalCheckoutDate && finalCheckoutDate !== checkinDate) {
                checkoutFormatted = new Date(finalCheckoutDate).toLocaleDateString('en-US', { 
                    month: 'short', 
                    day: 'numeric', 
                    year: 'numeric' 
                });
            }
            
            if (!data.available) {
                console.log('❌ EDIT CONFLICT DETECTED');
                
                if (data.conflict_type === 'special_event') {
                    conflictMessage.innerHTML = `
                        <div class="flex items-start">
                            <i class="fas fa-calendar-times text-orange-500 mt-0.5 mr-2"></i>
                            <div>
                                <strong class="block">Special Event Conflict</strong>
                                <span class="text-sm">Cannot update booking due to special event on selected dates.</span>
                                <div class="mt-1 text-xs text-gray-600">
                                    Requested: ${bookingType === 'day-use' ? checkinFormatted : checkinFormatted + ' to ' + checkoutFormatted}
                                </div>
                            </div>
                        </div>
                    `;
                    conflictMessage.className = 'col-span-2 p-3 bg-orange-50 border border-orange-200 rounded-lg text-orange-800 text-sm';
                } else {
                    const conflictMessageText = bookingType === 'day-use'
                        ? `Unit is already booked for ${checkinFormatted}.`
                        : `Unit is already booked from ${checkinFormatted} to ${checkoutFormatted}.`;
                    
                    conflictMessage.innerHTML = `
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-red-500 mt-0.5 mr-2"></i>
                            <div>
                                <strong class="block">Date Conflict</strong>
                                <span class="text-sm">${conflictMessageText}</span>
                                <div class="mt-1 text-xs text-gray-600">
                                    Please choose different dates for this booking.
                                </div>
                            </div>
                        </div>
                    `;
                    conflictMessage.className = 'col-span-2 p-3 bg-red-50 border border-red-200 rounded-lg text-red-800 text-sm';
                }
                
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                console.log('✅ NO EDIT CONFLICT - Available');
                
                conflictMessage.innerHTML = `
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mt-0.5 mr-2"></i>
                        <div>
                            <strong class="block">Dates Available</strong>
                            <span class="text-sm">Selected dates are available for this unit.</span>
                            <div class="mt-1 text-xs text-gray-600">
                                You can proceed with updating the booking.
                            </div>
                        </div>
                    </div>
                `;
                conflictMessage.className = 'col-span-2 p-3 bg-green-50 border border-green-200 rounded-lg text-green-800 text-sm';
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        })
        .catch(error => {
            console.error('❌ Error checking edit availability:', error);
            // Don't block submission if there's an error checking availability
            const submitBtn = document.getElementById('editBookingForm').querySelector('button[type="submit"]');
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        });
}

// Edit booking function
function editBooking(bookingId) {
    console.log('Editing booking ID:', bookingId);
    
    fetch(`/admin/bookings/${bookingId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Edit booking response:', data);
            
            if (data.success) {
                const booking = data.data;
                
                // Store original values
                originalName = booking.guest_name || '';
                originalEmail = booking.email || '';
                originalBookingId = booking.bookingID;
                originalBookingType = booking.booking_type;
                // Store unit ID (get it from the units array)
                originalUnitId = booking.units && booking.units.length > 0 ? booking.units[0].unitID : '';
                
                // Check if fields should be locked
                isNameLocked = !!originalName;
                isEmailLocked = !!originalEmail;
                
                // Set form values
                document.getElementById('edit_booking_id').value = booking.bookingID;
                document.getElementById('edit_guest_name').value = originalName;
                document.getElementById('edit_email').value = originalEmail;
                document.getElementById('original_email').value = originalEmail;
                document.getElementById('original_guest_name').value = originalName;
                document.getElementById('edit_phone').value = booking.phone;
                document.getElementById('edit_booking_status').value = booking.booking_status;
                document.getElementById('edit_num_guests').value = booking.num_guests;
                document.getElementById('edit_total_price').value = parseFloat(booking.total_price).toFixed(2);
                document.getElementById('edit_special_requirements').value = booking.special_requirements || '';
                
                // Apply field protection
                applyFieldProtection();
                
                // Format dates properly for date inputs
                const checkinDate = formatDateForInput(booking.checkin_date);
                const checkoutDate = booking.checkout_date ? formatDateForInput(booking.checkout_date) : '';
                
                console.log('Formatted dates - Checkin:', checkinDate, 'Checkout:', checkoutDate);
                
                const checkinInput = document.getElementById('edit_checkin_date');
                const checkoutInput = document.getElementById('edit_checkout_date');
                
                checkinInput.value = checkinDate;
                checkoutInput.value = checkoutDate;
                
                // Set min date for checkin to today
                const today = new Date().toISOString().split('T')[0];
                checkinInput.min = today;
                if (checkoutInput.value) {
                    checkoutInput.min = checkinDate;
                }
                
                // Add event listeners for date changes to check conflicts
                checkinInput.addEventListener('change', function() {
                    if (checkoutInput.value) {
                        checkoutInput.min = this.value;
                    }
                    clearEditConflictMessage();
                    setTimeout(checkEditDateConflict, 100);
                });
                
                checkoutInput.addEventListener('change', function() {
                    clearEditConflictMessage();
                    setTimeout(checkEditDateConflict, 100);
                });
                
                // Load payment summary for refund amount
                loadPaymentSummaryForRefund(bookingId);
                
                // Initial conflict check
                setTimeout(checkEditDateConflict, 500);
                
                openEditModal();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: data.message || 'Failed to load booking details',
                    confirmButtonColor: '#dc2626'
                });
            }
        })
        .catch(error => {
            console.error('Error loading booking:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Error loading booking details',
                confirmButtonColor: '#dc2626'
            });
        });
}

// Load payment summary for refund amount suggestion
function loadPaymentSummaryForRefund(bookingId) {
    fetch(`/admin/bookings/${bookingId}/payment-summary`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const refundAmountInput = document.getElementById('edit_refund_amount');
                const netPaid = parseFloat(data.data.net_paid || 0);
                
                if (netPaid > 0) {
                    refundAmountInput.value = netPaid.toFixed(2);
                    refundAmountInput.max = netPaid;
                    refundAmountInput.setAttribute('max', netPaid);
                } else {
                    refundAmountInput.value = '0.00';
                    refundAmountInput.max = 0;
                    refundAmountInput.setAttribute('max', 0);
                }
            }
        })
        .catch(error => {
            console.error('Error loading payment summary:', error);
        });
}

// Apply field protection based on original values
function applyFieldProtection() {
    const nameField = document.getElementById('edit_guest_name');
    const emailField = document.getElementById('edit_email');
    
    // Add tooltip or visual indicator
    if (isNameLocked) {
        nameField.title = "Name cannot be changed once booking is created";
        nameField.classList.add('cursor-not-allowed');
        nameField.readOnly = true;
    }
    
    if (isEmailLocked) {
        emailField.title = "Email cannot be changed once booking is created";
        emailField.classList.add('cursor-not-allowed');
        emailField.readOnly = true;
    }
}

// ✅ UPDATE BOOKING WITH ASYNC MODAL CLOSE + SWEETALERT2
document.getElementById('editBookingForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    // Check if there's a conflict before submitting
    const conflictMessage = document.getElementById('edit-conflict-message');
    if (conflictMessage && (conflictMessage.textContent.includes('already booked') || conflictMessage.textContent.includes('Special Event Conflict'))) {
        Swal.fire({
            icon: 'error',
            title: 'Date Conflict',
            text: 'Please resolve the date conflict before updating the booking.',
            confirmButtonColor: '#dc2626'
        });
        return;
    }
    
    // Validate phone numbers before submission
    if (!validateFormPhoneNumbers()) {
        Swal.fire({
            icon: 'warning',
            title: 'Invalid Phone Number',
            text: 'Please fix the phone number validation errors before submitting.',
            confirmButtonColor: '#f59e0b'
        });
        return;
    }
    
    const bookingId = document.getElementById('edit_booking_id').value;
    const newStatus = document.getElementById('edit_booking_status').value;
    
    // ✅ SWEETALERT CONFIRMATION for status changes
    if (newStatus === 'cancelled') {
        const result = await Swal.fire({
            icon: 'warning',
            title: 'Cancel Booking?',
            text: 'This will send a cancellation email to the guest. Are you sure?',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, cancel booking',
            cancelButtonText: 'No, go back'
        });
        
        if (!result.isConfirmed) return;
    } else if (newStatus === 'completed') {
        const result = await Swal.fire({
            icon: 'question',
            title: 'Mark as Completed?',
            text: 'This will send a thank you email to the guest. Are you sure?',
            showCancelButton: true,
            confirmButtonColor: '#16a34a',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, mark as completed',
            cancelButtonText: 'No, go back'
        });
        
        if (!result.isConfirmed) return;
    }
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    // Ensure we use original values for name and email
    if (originalName) {
        data.guest_name = originalName;
    }
    if (originalEmail) {
        data.email = originalEmail;
    }
    
    // Remove hidden fields from submission
    delete data.booking_id;
    delete data.original_email;
    delete data.original_guest_name;

    console.log('Updating booking:', bookingId, data);

    // Clean phone number before sending (remove any formatting)
    if (data.phone) {
        data.phone = data.phone.replace(/\D/g, '');
    }

    // ✅ SWEETALERT VALIDATION: Dates
    if (originalBookingType === 'day-use') {
        if (data.checkout_date && data.checkout_date !== data.checkin_date) {
            Swal.fire({
                icon: 'warning',
                title: 'Invalid Dates',
                text: 'For day-use bookings, check-out date must be the same as check-in date',
                confirmButtonColor: '#f59e0b'
            });
            return;
        }
        data.checkout_date = data.checkin_date;
    }

    if (originalBookingType === 'overnight') {
        if (!data.checkout_date) {
            Swal.fire({
                icon: 'warning',
                title: 'Missing Check-out Date',
                text: 'Check-out date is required for overnight bookings',
                confirmButtonColor: '#f59e0b'
            });
            return;
        }
        if (data.checkout_date === data.checkin_date) {
            Swal.fire({
                icon: 'warning',
                title: 'Invalid Dates',
                text: 'For overnight bookings, check-out date must be after check-in date',
                confirmButtonColor: '#f59e0b'
            });
            return;
        }
    }

    // Show loading state
    showUpdateLoading();

    try {
        const response = await fetch(`/admin/bookings/${bookingId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        console.log('Update response:', result);
        
        // Hide loading state
        hideUpdateLoading();
        
        if (result.success) {
            // ✅ CLOSE MODAL FIRST (wait 300ms for animation)
            await closeEditModal();
            
            // ✅ THEN SHOW SWEETALERT
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Booking updated successfully! Email notification sent to guest.',
                confirmButtonColor: '#16a34a',
                confirmButtonText: 'OK'
            }).then(() => {
                // Reload bookings after user closes SweetAlert
                loadBookings(getCurrentStatus(), getCurrentSearch(), currentPage);
            });
        } else {
            // ✅ ERROR: CLOSE MODAL FIRST
            await closeEditModal();
            
            // ✅ THEN SHOW ERROR SWEETALERT
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: result.message || 'Failed to update booking',
                confirmButtonColor: '#dc2626'
            });
        }
    } catch (error) {
        console.error('Error:', error);
        hideUpdateLoading();
        
        // ✅ ERROR: CLOSE MODAL FIRST
        await closeEditModal();
        
        // ✅ THEN SHOW ERROR SWEETALERT
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Error updating booking',
            confirmButtonColor: '#dc2626'
        });
    }
});
</script>