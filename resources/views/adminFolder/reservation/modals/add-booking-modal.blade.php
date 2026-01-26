<!-- Add Booking Modal -->
<div id="addBookingModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-800">Add New Booking</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        
        <form id="bookingForm" class="p-6">
            <div class="grid grid-cols-2 gap-4 mb-6">
                <!-- Guest Information -->
                <div class="col-span-2">
                    <h4 class="text-lg font-medium text-gray-800 mb-3">Guest Information</h4>
                </div>
                
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Guest Name</label>
                    <input type="text" name="guest_name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 email-input">
                    <!-- Email error message container - hidden by default -->
                    <div id="email-error-message" class="mt-1 text-sm hidden"></div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="phone" id="phone" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Booking Details -->
                <div class="col-span-2 mt-4">
                    <h4 class="text-lg font-medium text-gray-800 mb-3">Booking Details</h4>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Booking Type</label>
                    <select name="booking_type" id="booking_type" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="day-use">Day Use</option>
                        <option value="overnight">Overnight</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Number of Guests</label>
                    <input type="number" name="num_guests" id="num_guests" min="1" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Check-in Date</label>
                    <input type="date" name="checkin_date" id="checkin_date" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div id="checkout_date_wrapper">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Check-out Date</label>
                    <input type="date" name="checkout_date" id="checkout_date"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Unit Selection -->
                <div class="col-span-2 mt-4">
                    <h4 class="text-lg font-medium text-gray-800 mb-3">Unit Selection</h4>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit Type</label>
                    <select name="unit_type" id="unit_type" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="room">Room</option>
                        <option value="cottage">Cottage</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Select Unit</label>
                    <select name="unit_id" id="unit_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select a unit</option>
                    </select>
                    <!-- Unit Availability Notes -->
                    <div id="unitAvailabilityNotes" class="mt-2 text-sm"></div>
                </div>
                
                <!-- Price Breakdown -->
                <div class="col-span-2 mt-4">
                    <h4 class="text-lg font-medium text-gray-800 mb-3">Price Breakdown</h4>
                </div>
                
                <div class="col-span-2">
                    <div class="bg-gray-50 p-4 rounded-lg space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Unit Price:</span>
                            <span class="text-sm font-medium" id="unit_price_display">₱0.00</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Entrance Fee (<span id="entrance_fee_value">₱0.00</span> × <span id="guest_count">0</span> guests):</span>
                            <span class="text-sm font-medium" id="entrance_fee_total">₱0.00</span>
                        </div>
                        <div class="flex justify-between border-t border-gray-200 pt-2">
                            <span class="text-sm font-semibold text-gray-800">Total Price:</span>
                            <span class="text-sm font-bold text-blue-600" id="total_price_display">₱0.00</span>
                        </div>
                    </div>
                </div>
                
                <input type="hidden" name="total_price" id="total_price">
                
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Special Requirements</label>
                    <textarea name="special_requirements" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Any special requirements or notes..."></textarea>
                </div>
            </div>
            
            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeModal()"
                        class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="submit" id="submitBookingBtn"
                        class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed">
                    <!-- Loading spinner - hidden by default -->
                    <span id="submitText">Create Booking</span>
                    <span id="submitSpinner" class="hidden">
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        Creating...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// ============================================
// ADD BOOKING MODAL SCRIPTS - ENHANCED CONFLICT CHECKING
// ============================================

let currentEntranceFee = 0;
let hasSpecialEventConflict = false;

// Email validation function
function validateEmail(email) {
    const lowerEmail = email.toLowerCase().trim();
    
    // Check if empty
    if (!lowerEmail) {
        return {
            isValid: false,
            message: 'Email is required.',
            correctedEmail: null
        };
    }
    
    // Check if it's a @gmail.com email
    if (!lowerEmail.endsWith('@gmail.com')) {
        return {
            isValid: false,
            message: 'Only @gmail.com email addresses are accepted.',
            correctedEmail: null
        };
    }
    
    // Check if original email had uppercase letters
    if (email !== lowerEmail) {
        return {
            isValid: false,
            message: 'Email must be in lowercase letters.',
            correctedEmail: lowerEmail
        };
    }
    
    // Basic email format validation
    const emailRegex = /^[a-z0-9._%+-]+@gmail\.com$/;
    if (!emailRegex.test(lowerEmail)) {
        return {
            isValid: false,
            message: 'Please enter a valid @gmail.com email address.',
            correctedEmail: null
        };
    }
    
    return {
        isValid: true,
        message: 'Valid email address.',
        correctedEmail: lowerEmail
    };
}

// Setup email auto-lowercase (without showing error messages)
function setupEmailAutoLowercase() {
    const emailInput = document.querySelector('input[name="email"]');
    if (!emailInput) return;
    
    // Auto-convert to lowercase as user types
    emailInput.addEventListener('input', function() {
        const email = this.value;
        
        if (email !== email.toLowerCase()) {
            const cursorPos = this.selectionStart;
            this.value = email.toLowerCase();
            this.setSelectionRange(cursorPos, cursorPos);
        }
    });
}

// Show email error message
function showEmailError(message) {
    const errorDiv = document.getElementById('email-error-message');
    const emailInput = document.querySelector('input[name="email"]');
    
    if (errorDiv && emailInput) {
        errorDiv.innerHTML = `<div class="flex items-center text-red-600">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <span>${message}</span>
        </div>`;
        errorDiv.classList.remove('hidden');
        emailInput.classList.add('border-red-500');
        emailInput.classList.remove('border-green-500');
        
        // Focus on email input
        emailInput.focus();
    }
}

// Clear email error
function clearEmailError() {
    const errorDiv = document.getElementById('email-error-message');
    const emailInput = document.querySelector('input[name="email"]');
    
    if (errorDiv) {
        errorDiv.innerHTML = '';
        errorDiv.classList.add('hidden');
    }
    
    if (emailInput) {
        emailInput.classList.remove('border-red-500', 'border-green-500');
    }
}

// Validate email before form submission
function validateFormEmail() {
    const emailInput = document.querySelector('input[name="email"]');
    const email = emailInput.value.trim();
    
    // Clear previous error
    clearEmailError();
    
    if (!email) {
        showEmailError('Email is required.');
        return {
            isValid: false,
            message: 'Email is required.'
        };
    }
    
    const validation = validateEmail(email);
    
    // Auto-correct if possible
    if (!validation.isValid && validation.correctedEmail) {
        emailInput.value = validation.correctedEmail;
    }
    
    // Show error if invalid
    if (!validation.isValid) {
        showEmailError(validation.message);
    }
    
    return validation;
}

// Show loading state on submit button
function showSubmitLoading() {
    const submitBtn = document.getElementById('submitBookingBtn');
    const submitText = document.getElementById('submitText');
    const submitSpinner = document.getElementById('submitSpinner');
    
    if (submitBtn && submitText && submitSpinner) {
        submitBtn.disabled = true;
        submitText.classList.add('hidden');
        submitSpinner.classList.remove('hidden');
    }
}

// Hide loading state on submit button
function hideSubmitLoading() {
    const submitBtn = document.getElementById('submitBookingBtn');
    const submitText = document.getElementById('submitText');
    const submitSpinner = document.getElementById('submitSpinner');
    
    if (submitBtn && submitText && submitSpinner) {
        submitBtn.disabled = false;
        submitText.classList.remove('hidden');
        submitSpinner.classList.add('hidden');
    }
}

// Modal functions
function openModal() {
    document.getElementById('addBookingModal').classList.remove('hidden');
    document.getElementById('addBookingModal').classList.add('flex');
    initializeBookingForm();
    loadAvailableUnits();
    
    // Setup email auto-lowercase only
    setupEmailAutoLowercase();
    
    // Setup phone validation for add form
    setupPhoneValidation('phone');
    
    // Add event listener for outside click
    setTimeout(() => {
        document.addEventListener('click', handleOutsideClick);
    }, 100);
}

function closeModal() {
    document.getElementById('addBookingModal').classList.add('hidden');
    document.getElementById('addBookingModal').classList.remove('flex');
    document.getElementById('bookingForm').reset();
    clearConflictMessage();
    clearUnitNotes();
    
    // Clear email error
    clearEmailError();
    
    // Reset button loading state
    hideSubmitLoading();
    
    // Reset special event conflict flag
    hasSpecialEventConflict = false;
    
    // Clear phone validation styling
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.classList.remove('border-red-500', 'border-green-500');
        const errorDiv = phoneInput.parentNode.querySelector('.phone-error');
        if (errorDiv) errorDiv.remove();
    }
    
    // Remove event listener
    document.removeEventListener('click', handleOutsideClick);
}

// Handle outside click
function handleOutsideClick(event) {
    const modal = document.getElementById('addBookingModal');
    const modalContent = modal.querySelector('.bg-white');
    if (!modalContent.contains(event.target)) closeModal();
}

// Initialize booking form
function initializeBookingForm() {
    const bookingType = document.getElementById('booking_type');
    const checkoutWrapper = document.getElementById('checkout_date_wrapper');
    const checkoutInput = document.getElementById('checkout_date');
    const checkinInput = document.getElementById('checkin_date');
    
    // Set today as minimum date
    const today = new Date().toISOString().split('T')[0];
    checkinInput.min = today;
    checkoutInput.min = today;
    
    // Initialize booking type state
    if (bookingType.value === 'day-use') {
        checkoutInput.disabled = true;
        checkoutWrapper.style.opacity = '0.5';
        checkoutInput.removeAttribute('required');
        
        // Set checkout to checkin if checkin is already set
        if (checkinInput.value) {
            checkoutInput.value = checkinInput.value;
        }
    } else {
        checkoutInput.disabled = false;
        checkoutWrapper.style.opacity = '1';
        checkoutInput.setAttribute('required', 'required');
    }
}

// Handle booking type change
document.getElementById('booking_type').addEventListener('change', function() {
    console.log('Booking type changed to:', this.value);
    const checkoutWrapper = document.getElementById('checkout_date_wrapper');
    const checkoutInput = document.getElementById('checkout_date');
    const checkinInput = document.getElementById('checkin_date');
    const unitType = document.getElementById('unit_type').value;
    
    // VALIDATION: Cottage cannot be used for overnight
    if (unitType === 'cottage' && this.value === 'overnight') {
        alert('Cottage units are only available for day-use bookings');
        this.value = 'day-use';
        return;
    }
    
    if (this.value === 'day-use') {
        checkoutInput.removeAttribute('required');
        checkoutWrapper.style.opacity = '0.5';
        checkoutInput.disabled = true;
        
        // Set checkout date to same as checkin for day-use
        if (checkinInput.value) {
            checkoutInput.value = checkinInput.value;
        }
    } else {
        checkoutInput.setAttribute('required', 'required');
        checkoutWrapper.style.opacity = '1';
        checkoutInput.disabled = false;
        
        // Clear checkout date when switching to overnight
        checkoutInput.value = '';
    }
    
    clearConflictMessage();
    loadAvailableUnits();
    updateTotalPrice(); // Update price when booking type changes
    // Check conflict after a short delay
    setTimeout(checkDateConflict, 100);
});

// Handle checkin date change
document.getElementById('checkin_date').addEventListener('change', function() {
    console.log('Checkin date changed to:', this.value);
    const bookingType = document.getElementById('booking_type').value;
    const checkoutInput = document.getElementById('checkout_date');
    
    // Update checkout min date to checkin date
    checkoutInput.min = this.value;
    
    // For day-use, automatically set checkout to same date
    if (bookingType === 'day-use' && this.value) {
        checkoutInput.value = this.value;
    }
    
    clearConflictMessage();
    loadAvailableUnits();
    updateTotalPrice(); // Update price when dates change
    // Check conflict after a short delay
    setTimeout(checkDateConflict, 100);
});

// Handle checkout date change
document.getElementById('checkout_date').addEventListener('change', function() {
    console.log('Checkout date changed to:', this.value);
    clearConflictMessage();
    updateTotalPrice(); // Update price when dates change
    // Check conflict after a short delay
    setTimeout(checkDateConflict, 100);
});

// Clear conflict message
function clearConflictMessage() {
    const conflictMessage = document.getElementById('conflict-message');
    if (conflictMessage) {
        conflictMessage.remove();
    }
    const submitBtn = document.getElementById('submitBookingBtn');
    if (submitBtn) {
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    }
}

// Clear unit notes
function clearUnitNotes() {
    const notesDiv = document.getElementById('unitAvailabilityNotes');
    if (notesDiv) {
        notesDiv.innerHTML = '';
        notesDiv.className = 'mt-2 text-sm';
    }
}

// Create conflict message element
function createConflictMessage() {
    const messageDiv = document.createElement('div');
    messageDiv.id = 'conflict-message';
    const form = document.getElementById('bookingForm');
    const lastField = form.querySelector('.grid > .col-span-2:last-of-type');
    lastField.parentNode.insertBefore(messageDiv, lastField.nextSibling);
    return messageDiv;
}

// Show unit availability notes
function showUnitNotes(message, type = 'info') {
    const notesDiv = document.getElementById('unitAvailabilityNotes');
    if (notesDiv) {
        if (type === 'error') {
            notesDiv.innerHTML = `<div class="flex items-center text-red-600"><i class="fas fa-exclamation-circle mr-2"></i> ${message}</div>`;
            notesDiv.className = 'mt-2 text-sm text-red-600';
        } else if (type === 'warning') {
            notesDiv.innerHTML = `<div class="flex items-center text-yellow-600"><i class="fas fa-info-circle mr-2"></i> ${message}</div>`;
            notesDiv.className = 'mt-2 text-sm text-yellow-600';
        } else {
            notesDiv.innerHTML = `<div class="flex items-center text-green-600"><i class="fas fa-check-circle mr-2"></i> ${message}</div>`;
            notesDiv.className = 'mt-2 text-sm text-green-600';
        }
    }
}

// Enhanced conflict checking with specific messages
function checkDateConflict() {
    const unitId = document.getElementById('unit_id').value;
    const checkinDate = document.getElementById('checkin_date').value;
    const checkoutDate = document.getElementById('checkout_date').value;
    const bookingType = document.getElementById('booking_type').value;
    const unitName = document.getElementById('unit_id').options[document.getElementById('unit_id').selectedIndex]?.text.split(' - ')[0] || 'Selected unit';

    console.log('=== ENHANCED CONFLICT CHECK START ===');
    console.log('Unit:', unitName, 'ID:', unitId);
    console.log('Checkin:', checkinDate);
    console.log('Checkout:', checkoutDate);
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
        booking_type: bookingType
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
            
            const submitBtn = document.getElementById('submitBookingBtn');
            const conflictMessage = document.getElementById('conflict-message') || createConflictMessage();
            
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
                console.log('❌ CONFLICT DETECTED');
                
                if (data.conflict_type === 'special_event') {
                    conflictMessage.innerHTML = `
                        <div class="flex items-start">
                            <i class="fas fa-calendar-times text-orange-500 mt-0.5 mr-2"></i>
                            <div>
                                <strong class="block">Special Event Conflict</strong>
                                <span class="text-sm">Cannot book ${unitName} due to special event on selected dates.</span>
                                <div class="mt-1 text-xs text-gray-600">
                                    Requested: ${bookingType === 'day-use' ? checkinFormatted : checkinFormatted + ' to ' + checkoutFormatted}
                                </div>
                            </div>
                        </div>
                    `;
                    conflictMessage.className = 'col-span-2 p-3 bg-orange-50 border border-orange-200 rounded-lg text-orange-800 text-sm';
                } else {
                    const conflictMessageText = bookingType === 'day-use'
                        ? `${unitName} is already booked for ${checkinFormatted}.`
                        : `${unitName} is already booked from ${checkinFormatted} to ${checkoutFormatted}.`;
                    
                    conflictMessage.innerHTML = `
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-red-500 mt-0.5 mr-2"></i>
                            <div>
                                <strong class="block">Date Conflict</strong>
                                <span class="text-sm">${conflictMessageText}</span>
                                <div class="mt-1 text-xs text-gray-600">
                                    Please choose different dates or select another unit.
                                </div>
                            </div>
                        </div>
                    `;
                    conflictMessage.className = 'col-span-2 p-3 bg-red-50 border border-red-200 rounded-lg text-red-800 text-sm';
                }
                
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                console.log('✅ NO CONFLICT - Available');
                
                const availableMessage = bookingType === 'day-use'
                    ? `${unitName} is available for ${checkinFormatted}.`
                    : `${unitName} is available from ${checkinFormatted} to ${checkoutFormatted}.`;
                
                conflictMessage.innerHTML = `
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mt-0.5 mr-2"></i>
                        <div>
                            <strong class="block">Unit Available</strong>
                            <span class="text-sm">${availableMessage}</span>
                            <div class="mt-1 text-xs text-gray-600">
                                You can proceed with booking.
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
            console.error('❌ Error checking availability:', error);
            // Don't block submission if there's an error checking availability
            const submitBtn = document.getElementById('submitBookingBtn');
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        });
}

// ENHANCED: Load available units with special event notes
function loadAvailableUnits() {
    const unitType = document.getElementById('unit_type').value;
    const checkinDate = document.getElementById('checkin_date').value;
    const checkoutDate = document.getElementById('checkout_date').value;
    const bookingType = document.getElementById('booking_type').value;

    console.log('=== LOADING AVAILABLE UNITS ===');
    console.log('Unit Type:', unitType);
    console.log('Checkin Date:', checkinDate);
    console.log('Checkout Date:', checkoutDate);
    console.log('Booking Type:', bookingType);

    // VALIDATION: Cottage cannot be used for overnight
    if (unitType === 'cottage' && bookingType === 'overnight') {
        const unitSelect = document.getElementById('unit_id');
        unitSelect.innerHTML = '<option value="">Cottage units are only available for day-use bookings</option>';
        showUnitNotes('Cottage units are not available for overnight bookings', 'error');
        updateTotalPrice();
        return;
    }

    let url = `/admin/bookings/units/available?unit_type=${unitType}`;
    
    if (checkinDate) {
        // For day-use, use checkin date as checkout date
        const finalCheckoutDate = (bookingType === 'day-use') ? checkinDate : (checkoutDate || checkinDate);
        url += `&checkin_date=${checkinDate}&checkout_date=${finalCheckoutDate}&booking_type=${bookingType}`;
    }

    console.log('Fetch URL:', url);

    fetch(url)
        .then(response => response.json())
        .then(data => {
            console.log('Available units response:', data);
            
            if (data.success) {
                const unitSelect = document.getElementById('unit_id');
                const currentUnitId = unitSelect.value;
                const submitBtn = document.getElementById('submitBookingBtn');
                
                // Store entrance fee for price calculation
                if (data.entrance_fee) {
                    currentEntranceFee = parseFloat(data.entrance_fee);
                    document.getElementById('entrance_fee_value').textContent = '₱' + currentEntranceFee.toFixed(2);
                }
                
                unitSelect.innerHTML = '<option value="">Select a unit</option>';
                
                if (data.data.length === 0) {
                    if (data.has_special_event_conflict) {
                        unitSelect.innerHTML = '<option value="">No available booking on selected date</option>';
                        showUnitNotes('There is a special event on the selected date. No units available for normal bookings.', 'error');
                        hasSpecialEventConflict = true;
                    } else {
                        unitSelect.innerHTML = '<option value="">No available units for selected dates</option>';
                        showUnitNotes('All units are booked for the selected dates. Please choose different dates.', 'warning');
                        hasSpecialEventConflict = false;
                    }
                    console.log('No units available');
                    
                    // Disable submit button when no units available
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    }
                } else {
                    console.log('Found', data.data.length, 'available units');
                    data.data.forEach(unit => {
                        unitSelect.innerHTML += `
                            <option value="${unit.unitID}" data-price="${unit.unitRatePrice}">
                                ${unit.unitName} - ₱${parseFloat(unit.unitRatePrice).toFixed(2)} (Capacity: ${unit.capacity})
                            </option>
                        `;
                    });
                    
                    // Show success message
                    showUnitNotes(`${data.data.length} unit(s) available for selected dates`, 'success');
                    hasSpecialEventConflict = false;
                    
                    // Try to restore previous selection if still available
                    if (currentUnitId) {
                        const optionExists = Array.from(unitSelect.options).some(option => option.value === currentUnitId);
                        if (optionExists) {
                            unitSelect.value = currentUnitId;
                        }
                    }
                    
                    // Enable submit button when units are available
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    }
                }
                
                // Update total price
                updateTotalPrice();
                // Check conflict after units are loaded
                setTimeout(checkDateConflict, 100);
            }
        })
        .catch(error => {
            console.error('Error loading units:', error);
            showUnitNotes('Error loading units. Please try again.', 'error');
        });
}

// Update total price when unit changes
document.getElementById('unit_id').addEventListener('change', function() {
    console.log('Unit changed to:', this.value);
    updateTotalPrice();
    checkDateConflict();
});

// Update total price when guest count changes
document.getElementById('num_guests').addEventListener('input', function() {
    updateTotalPrice();
});

// Calculate number of days between checkin and checkout
function calculateDaysCount() {
    const checkinDate = document.getElementById('checkin_date').value;
    const checkoutDate = document.getElementById('checkout_date').value;
    const bookingType = document.getElementById('booking_type').value;
    
    if (!checkinDate) return 1;
    
    if (bookingType === 'day-use') {
        return 1;
    }
    
    if (!checkoutDate || checkoutDate === checkinDate) {
        return 1;
    }
    
    const checkin = new Date(checkinDate);
    const checkout = new Date(checkoutDate);
    const timeDiff = checkout.getTime() - checkin.getTime();
    const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));
    
    return Math.max(1, daysDiff);
}

// Update total price based on selected unit and guest count
function updateTotalPrice() {
    const unitSelect = document.getElementById('unit_id');
    const selectedOption = unitSelect.options[unitSelect.selectedIndex];
    const totalPriceInput = document.getElementById('total_price');
    const numGuestsInput = document.getElementById('num_guests');
    const unitTypeSelect = document.getElementById('unit_type');
    const bookingTypeSelect = document.getElementById('booking_type');
    
    const unitPriceDisplay = document.getElementById('unit_price_display');
    const entranceFeeTotal = document.getElementById('entrance_fee_total');
    const totalPriceDisplay = document.getElementById('total_price_display');
    const guestCount = document.getElementById('guest_count');
    
    if (selectedOption && selectedOption.value) {
        const unitPrice = parseFloat(selectedOption.getAttribute('data-price'));
        const numGuests = parseInt(numGuestsInput.value) || 1;
        const unitType = unitTypeSelect.value;
        const bookingType = bookingTypeSelect.value;
        
        // Update guest count display
        guestCount.textContent = numGuests;
        
        let totalPrice = 0;
        let entranceFeeAmount = 0;
        let daysCount = 1;
        
        // Calculate days count for overnight bookings
        if (bookingType === 'overnight') {
            daysCount = calculateDaysCount();
        }
        
        console.log('Price calculation:', {
            unitType,
            bookingType,
            unitPrice,
            numGuests,
            daysCount,
            currentEntranceFee
        });
        
        if (unitType === 'cottage') {
            // COTTAGE: (entrance fee * number of guests) + cottage price
            entranceFeeAmount = currentEntranceFee * numGuests;
            totalPrice = entranceFeeAmount + unitPrice;
            
            // Update displays
            unitPriceDisplay.textContent = '₱' + unitPrice.toFixed(2);
            entranceFeeTotal.textContent = '₱' + entranceFeeAmount.toFixed(2);
            totalPriceDisplay.textContent = '₱' + totalPrice.toFixed(2);
        } else {
            // ROOM: Different calculation based on booking type
            if (bookingType === 'day-use') {
                if (numGuests === 1) {
                    totalPrice = unitPrice * 2;
                    unitPriceDisplay.textContent = '₱' + unitPrice.toFixed(2) + ' × 2 (single guest)';
                } else {
                    totalPrice = unitPrice * numGuests;
                    unitPriceDisplay.textContent = '₱' + unitPrice.toFixed(2) + ' × ' + numGuests + ' guests';
                }
            } else {
                totalPrice = unitPrice * numGuests * daysCount;
                unitPriceDisplay.textContent = '₱' + unitPrice.toFixed(2) + ' × ' + numGuests + ' guests × ' + daysCount + ' days';
            }
            
            // No entrance fee for rooms
            entranceFeeTotal.textContent = '₱0.00';
            totalPriceDisplay.textContent = '₱' + totalPrice.toFixed(2);
        }
        
        totalPriceInput.value = totalPrice.toFixed(2);
        return;
    }
    
    // Reset displays if no unit selected
    unitPriceDisplay.textContent = '₱0.00';
    entranceFeeTotal.textContent = '₱0.00';
    totalPriceDisplay.textContent = '₱0.00';
    totalPriceInput.value = '0.00';
}

// Load available units when unit type changes
document.getElementById('unit_type').addEventListener('change', function() {
    console.log('Unit type changed to:', this.value);
    
    // VALIDATION: If switching to cottage and booking type is overnight, change to day-use
    const bookingType = document.getElementById('booking_type').value;
    if (this.value === 'cottage' && bookingType === 'overnight') {
        document.getElementById('booking_type').value = 'day-use';
        // Trigger booking type change to update UI
        document.getElementById('booking_type').dispatchEvent(new Event('change'));
    }
    
    clearConflictMessage();
    loadAvailableUnits();
});

// Create booking
document.getElementById('bookingForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    // Validate email first
    const emailValidation = validateFormEmail();
    if (!emailValidation.isValid) {
        // Error message already shown by validateFormEmail()
        return;
    }
    
    // Prevent submission if there's a special event conflict
    if (hasSpecialEventConflict) {
        alert('Cannot create booking due to special event conflict. Please choose different dates.');
        return;
    }
    
    // Validate phone numbers before submission
    if (!validateFormPhoneNumbers()) {
        alert('Please fix the phone number validation errors before submitting.');
        return;
    }
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);

    console.log('Submitting booking:', data);

    // Validate cottage cannot be used for overnight
    const unitType = document.getElementById('unit_type').value;
    if (unitType === 'cottage' && data.booking_type === 'overnight') {
        alert('Cottage units are only available for day-use bookings');
        return;
    }

    // Validate day-use booking
    if (data.booking_type === 'day-use') {
        if (data.checkout_date && data.checkout_date !== data.checkin_date) {
            alert('For day-use bookings, check-out date must be the same as check-in date');
            return;
        }
        data.checkout_date = data.checkin_date;
    }

    // Validate overnight booking
    if (data.booking_type === 'overnight') {
        if (!data.checkout_date) {
            alert('Check-out date is required for overnight bookings');
            return;
        }
        if (data.checkout_date === data.checkin_date) {
            alert('For overnight bookings, check-out date must be after check-in date');
            return;
        }
    }

    // Check if there's still a conflict before submitting
    const conflictMessage = document.getElementById('conflict-message');
    if (conflictMessage && conflictMessage.textContent.includes('already booked')) {
        alert('Please resolve the date conflict before submitting the booking.');
        return;
    }

    // Clean phone number before sending
    if (data.phone) {
        data.phone = data.phone.replace(/\D/g, '');
    }

    // Ensure email is lowercase
    if (data.email) {
        data.email = data.email.toLowerCase().trim();
    }

    // Show loading state
    showSubmitLoading();

    try {
        const response = await fetch('/admin/bookings', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        console.log('Booking response:', result);
        
        if (result.success) {
            alert('Booking created successfully!');
            closeModal();
            loadBookings(getCurrentStatus(), getCurrentSearch(), currentPage);
        } else {
            alert('Error: ' + (result.message || 'Failed to create booking'));
            // Hide loading state on error
            hideSubmitLoading();
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error creating booking');
        // Hide loading state on error
        hideSubmitLoading();
    }
});
</script>