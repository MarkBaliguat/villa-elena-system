<!-- Add Special Event Modal with Enhanced Conflict Checking -->
<div id="addBookingModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-800">Add Special Event</h3>
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

                <!-- Event Details -->
                <div class="col-span-2 mt-4">
                    <h4 class="text-lg font-medium text-gray-800 mb-3">Event Details</h4>
                </div>
                
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Event Type</label>
                    <select name="event_name" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Event Type</option>
                        <option value="Birthday">Birthday Celebration</option>
                        <option value="Wedding">Wedding Reception</option>
                        <option value="Corporate">Corporate Event</option>
                        <option value="Anniversary">Anniversary</option>
                        <option value="Christening">Christening</option>
                        <option value="Reunion">Family Reunion</option>
                        <option value="Other">Other Special Event</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Event Date</label>
                    <input type="date" name="checkin_date" id="checkin_date" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <!-- Conflict Warning -->
                    <div id="dateConflictWarning" class="hidden mt-2 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                            <span class="text-red-700 text-sm font-medium" id="conflictMessage"></span>
                        </div>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Number of Guests</label>
                    <input type="number" name="num_guests" id="num_guests" min="1" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Event Start Time</label>
                    <input type="time" name="event_start_time" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="08:00">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Event End Time</label>
                    <input type="time" name="event_end_time" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="17:00">
                </div>

                <!-- Special Event Unit Selection -->
                <div class="col-span-2 mt-4">
                    <h4 class="text-lg font-medium text-gray-800 mb-3">Event Venue Selection</h4>
                </div>
                
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Select Event Venue</label>
                    <select name="unit_id" id="unit_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select an event venue</option>
                    </select>
                    <!-- Unit Availability Status -->
                    <div id="unitAvailabilityStatus" class="mt-2 text-sm"></div>
                </div>
                
                <!-- Price Breakdown -->
                <div class="col-span-2 mt-4">
                    <h4 class="text-lg font-medium text-gray-800 mb-3">Price Breakdown</h4>
                </div>
                
                <div class="col-span-2">
                    <div class="bg-gray-50 p-4 rounded-lg space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Venue Price:</span>
                            <span class="text-sm font-medium" id="unit_price_display">₱0.00</span>
                        </div>
                        <div class="flex justify-between border-t border-gray-200 pt-2">
                            <span class="text-sm font-semibold text-gray-800">Total Price:</span>
                            <span class="text-sm font-bold text-violet-600" id="total_price_display">₱0.00</span>
                        </div>
                    </div>
                </div>
                
                <input type="hidden" name="total_price" id="total_price">
                <input type="hidden" name="booking_type" value="special-event">
                
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Event Requirements & Notes</label>
                    <textarea name="special_requirements" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Any special event requirements, setup needs, or additional notes..."></textarea>
                </div>
            </div>
            
            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeModal()"
                        class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="submit" id="submitButton"
                        class="flex-1 px-4 py-2.5 bg-violet-600 text-white rounded-lg font-medium hover:bg-violet-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed">
                    <span id="submitText">Create Special Event</span>
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
// ENHANCED SPECIAL EVENTS BOOKING WITH LOADING BUTTON
// ============================================

let currentDateConflict = false;

// Show loading state on submit button
function showSubmitLoading() {
    const submitBtn = document.getElementById('submitButton');
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
    const submitBtn = document.getElementById('submitButton');
    const submitText = document.getElementById('submitText');
    const submitSpinner = document.getElementById('submitSpinner');
    
    if (submitBtn && submitText && submitSpinner) {
        submitBtn.disabled = false;
        submitText.classList.remove('hidden');
        submitSpinner.classList.add('hidden');
    }
}

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

// Modal functions
function openModal() {
    document.getElementById('addBookingModal').classList.remove('hidden');
    document.getElementById('addBookingModal').classList.add('flex');
    initializeSpecialEventForm();
    loadSpecialEventUnits();
    
    // Setup email auto-lowercase only
    setupEmailAutoLowercase();
    
    setupPhoneValidation('phone');
    
    setTimeout(() => {
        document.addEventListener('click', handleOutsideClick);
    }, 100);
}

function closeModal() {
    document.getElementById('addBookingModal').classList.add('hidden');
    document.getElementById('addBookingModal').classList.remove('flex');
    document.getElementById('bookingForm').reset();
    
    // Reset conflict warnings
    hideDateConflictWarning();
    document.getElementById('unitAvailabilityStatus').innerHTML = '';
    document.getElementById('submitButton').disabled = false;
    currentDateConflict = false;
    
    // Reset time fields
    document.querySelector('input[name="event_start_time"]').value = '08:00';
    document.querySelector('input[name="event_end_time"]').value = '17:00';
    
    // Clear email error
    clearEmailError();
    
    // Reset loading button state
    hideSubmitLoading();
    
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.classList.remove('border-red-500', 'border-green-500');
        const errorDiv = phoneInput.parentNode.querySelector('.phone-error');
        if (errorDiv) errorDiv.remove();
    }
    
    document.removeEventListener('click', handleOutsideClick);
}

function handleOutsideClick(event) {
    const modal = document.getElementById('addBookingModal');
    const modalContent = modal.querySelector('.bg-white');
    if (!modalContent.contains(event.target)) closeModal();
}

// Initialize special event form
function initializeSpecialEventForm() {
    const checkinInput = document.getElementById('checkin_date');
    const today = new Date().toISOString().split('T')[0];
    checkinInput.min = today;
}

// ENHANCED: Check date availability in real-time - Now allows multiple events
function checkDateAvailability(date) {
    if (!date) return;

    console.log('Checking date availability for:', date);

    fetch(`/admin/special-events/check-date-availability?checkin_date=${date}`)
        .then(response => response.json())
        .then(data => {
            console.log('Date availability check:', data);
            
            if (data.success) {
                if (data.has_conflict) {
                    showDateConflictWarning(data.message);
                    currentDateConflict = true;
                    
                    // Clear unit selection pero HINDI disable dropdown
                    document.getElementById('unit_id').innerHTML = '<option value="">Select an event venue</option>';
                    document.getElementById('unitAvailabilityStatus').innerHTML = 
                        '<div class="text-yellow-600 flex items-center"><i class="fas fa-exclamation-triangle mr-1"></i> ' + data.message + '</div>';
                    
                    // Load available units para sa date na to
                    loadSpecialEventUnits();
                } else {
                    hideDateConflictWarning();
                    currentDateConflict = false;
                    document.getElementById('submitButton').disabled = false;
                    
                    // Load available units
                    loadSpecialEventUnits();
                }
            }
        })
        .catch(error => {
            console.error('Error checking date availability:', error);
        });
}

function showDateConflictWarning(message) {
    const warningDiv = document.getElementById('dateConflictWarning');
    const messageSpan = document.getElementById('conflictMessage');
    
    // Modify warning message to be less restrictive
    if (message.includes('This venue is already booked')) {
        messageSpan.textContent = 'Selected venue is already booked. Please choose a different venue.';
    } else if (message.includes('blocked for maintenance')) {
        messageSpan.textContent = message;
    } else {
        messageSpan.textContent = message;
    }
    
    warningDiv.classList.remove('hidden');
    
    // Add yellow border instead of red (warning, not error)
    document.getElementById('checkin_date').classList.add('border-yellow-500', 'border-2');
    document.getElementById('checkin_date').classList.remove('border-red-500');
}

function hideDateConflictWarning() {
    const warningDiv = document.getElementById('dateConflictWarning');
    warningDiv.classList.add('hidden');
    
    // Remove yellow border from date input
    document.getElementById('checkin_date').classList.remove('border-yellow-500', 'border-2', 'border-red-500');
}

// ENHANCED: Load special event units with availability checking
function loadSpecialEventUnits() {
    console.log('=== LOADING SPECIAL EVENT UNITS ===');

    const checkinDate = document.getElementById('checkin_date').value;
    if (!checkinDate) {
        console.log('No date selected, loading all special event units');
        loadAllSpecialEventUnits();
        return;
    }

    let url = `/admin/special-events/units/available?for_special_events=true&checkin_date=${checkinDate}&checkout_date=${checkinDate}&booking_type=special-event`;

    console.log('Fetch URL:', url);

    fetch(url)
        .then(response => response.json())
        .then(data => {
            console.log('Special event units response:', data);
            
            if (data.success) {
                const unitSelect = document.getElementById('unit_id');
                const availabilityStatus = document.getElementById('unitAvailabilityStatus');
                
                unitSelect.innerHTML = '<option value="">Select an event venue</option>';
                
                if (data.data.length === 0) {
                    unitSelect.innerHTML = '<option value="">No available event venues for selected date</option>';
                    availabilityStatus.innerHTML = '<div class="text-yellow-600 flex items-center"><i class="fas fa-info-circle mr-1"></i> No venues available for selected date</div>';
                } else {
                    console.log('Found', data.data.length, 'available special event units');
                    
                    data.data.forEach(unit => {
                        unitSelect.innerHTML += `
                            <option value="${unit.unitID}" data-price="${unit.unitRatePrice}">
                                ${unit.unitName} - ₱${parseFloat(unit.unitRatePrice).toFixed(2)} (Capacity: ${unit.capacity})
                            </option>
                        `;
                    });
                    
                    unitSelect.disabled = false;
                    availabilityStatus.innerHTML = `<div class="text-green-600 flex items-center"><i class="fas fa-check-circle mr-1"></i> ${data.data.length} venue(s) available</div>`;
                }
                
                updateTotalPrice();
            }
        })
        .catch(error => console.error('Error loading special event units:', error));
}

// Load all special event units without date filtering
function loadAllSpecialEventUnits() {
    fetch('/admin/special-events/units/available?for_special_events=true')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const unitSelect = document.getElementById('unit_id');
                unitSelect.innerHTML = '<option value="">Select an event venue</option>';
                
                data.data.forEach(unit => {
                    unitSelect.innerHTML += `
                        <option value="${unit.unitID}" data-price="${unit.unitRatePrice}">
                            ${unit.unitName} - ₱${parseFloat(unit.unitRatePrice).toFixed(2)} (Capacity: ${unit.capacity})
                        </option>
                    `;
                });
                
                updateTotalPrice();
            }
        })
        .catch(error => console.error('Error loading all special event units:', error));
}

// Update total price when unit changes
document.getElementById('unit_id').addEventListener('change', function() {
    console.log('Special event unit changed to:', this.value);
    updateTotalPrice();
});

// Update total price when guest count changes
document.getElementById('num_guests').addEventListener('input', function() {
    updateTotalPrice();
});

// ENHANCED: Handle event date change with conflict checking
document.getElementById('checkin_date').addEventListener('change', function() {
    console.log('Event date changed to:', this.value);
    
    // Check date availability first
    checkDateAvailability(this.value);
    
    // Then load available units for this date
    loadSpecialEventUnits();
    
    updateTotalPrice();
});

// Update total price for special events
function updateTotalPrice() {
    const unitSelect = document.getElementById('unit_id');
    const selectedOption = unitSelect.options[unitSelect.selectedIndex];
    const totalPriceInput = document.getElementById('total_price');
    const numGuestsInput = document.getElementById('num_guests');
    
    const unitPriceDisplay = document.getElementById('unit_price_display');
    const totalPriceDisplay = document.getElementById('total_price_display');
    
    if (selectedOption && selectedOption.value) {
        const unitPrice = parseFloat(selectedOption.getAttribute('data-price'));
        const numGuests = parseInt(numGuestsInput.value) || 1;
        
        const totalPrice = unitPrice;
        
        console.log('Special event price calculation:', {
            unitPrice,
            numGuests,
            totalPrice
        });
        
        unitPriceDisplay.textContent = '₱' + unitPrice.toFixed(2);
        totalPriceDisplay.textContent = '₱' + totalPrice.toFixed(2);
        totalPriceInput.value = totalPrice.toFixed(2);
        
        return;
    }
    
    unitPriceDisplay.textContent = '₱0.00';
    totalPriceDisplay.textContent = '₱0.00';
    totalPriceInput.value = '0.00';
}

// ENHANCED: Create special event booking with unit conflict check
document.getElementById('bookingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validate email first
    const emailValidation = validateFormEmail();
    if (!emailValidation.isValid) {
        return;
    }
    
    // Get unit and date
    const unitId = document.getElementById('unit_id').value;
    const checkinDate = document.getElementById('checkin_date').value;
    
    if (!unitId) {
        alert('Please select an event venue');
        return;
    }
    
    if (!checkinDate) {
        alert('Please select an event date');
        return;
    }
    
    // Show loading state
    showSubmitLoading();
    
    // Final unit availability check
    fetch(`/admin/special-events/check-date-availability?checkin_date=${checkinDate}&unit_id=${unitId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.has_conflict) {
                hideSubmitLoading();
                alert('Cannot create special event: ' + data.message);
                return;
            }
            
            // Continue with submission if no unit conflict
            submitBookingForm();
        })
        .catch(error => {
            console.error('Error checking unit availability:', error);
            submitBookingForm(); // Fallback: submit anyway
        });
});

function submitBookingForm() {
    if (!validateFormPhoneNumbers()) {
        hideSubmitLoading();
        alert('Please fix the phone number validation errors before submitting.');
        return;
    }
    
    const formData = new FormData(document.getElementById('bookingForm'));
    const data = Object.fromEntries(formData);

    console.log('Submitting special event booking:', data);

    // Additional validation
    if (!data.event_name) {
        hideSubmitLoading();
        alert('Please select an event type');
        return;
    }

    if (data.event_start_time >= data.event_end_time) {
        hideSubmitLoading();
        alert('Event end time must be after start time');
        return;
    }

    // Clean phone number
    if (data.phone) {
        data.phone = data.phone.replace(/\D/g, '');
    }

    // Ensure email is lowercase
    if (data.email) {
        data.email = data.email.toLowerCase().trim();
    }

    // Use special events endpoint
    fetch('/admin/special-events', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        console.log('Special event booking response:', data);
        if (data.success) {
            alert('Special event created successfully!');
            closeModal();
            loadBookings(getCurrentStatus(), getCurrentSearch(), currentPage);
        } else {
            hideSubmitLoading();
            let errorMessage = 'Failed to create special event';
            if (data.message) {
                errorMessage = data.message;
            }
            if (data.errors) {
                errorMessage += '\n' + Object.values(data.errors).flat().join('\n');
            }
            alert('Error: ' + errorMessage);
        }
    })
    .catch(error => {
        hideSubmitLoading();
        console.error('Error:', error);
        alert('Error creating special event: ' + error.message);
    });
}

// Phone validation functions
function setupPhoneValidation(inputId) {
    const phoneInput = document.getElementById(inputId);
    if (!phoneInput) return;

    phoneInput.addEventListener('input', function() {
        const phone = this.value.replace(/\D/g, '');
        const parent = this.parentNode;
        let errorDiv = parent.querySelector('.phone-error');

        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'phone-error text-xs mt-1';
            parent.appendChild(errorDiv);
        }

        if (phone.length === 0) {
            this.classList.remove('border-red-500', 'border-green-500');
            errorDiv.textContent = '';
        } else if (phone.length === 11 && phone.startsWith('09')) {
            this.classList.remove('border-red-500');
            this.classList.add('border-green-500');
            errorDiv.className = 'phone-error text-xs mt-1 text-green-600';
            errorDiv.textContent = '✓ Valid phone number';
        } else {
            this.classList.remove('border-green-500');
            this.classList.add('border-red-500');
            errorDiv.className = 'phone-error text-xs mt-1 text-red-600';
            errorDiv.textContent = 'Please enter a valid 11-digit phone number starting with 09';
        }
    });
}

function validateFormPhoneNumbers() {
    const phoneInputs = document.querySelectorAll('input[type="text"][name="phone"]');
    let allValid = true;

    phoneInputs.forEach(input => {
        const phone = input.value.replace(/\D/g, '');
        if (phone && (phone.length !== 11 || !phone.startsWith('09'))) {
            allValid = false;
            input.classList.add('border-red-500');
            
            const parent = input.parentNode;
            let errorDiv = parent.querySelector('.phone-error');
            if (!errorDiv) {
                errorDiv = document.createElement('div');
                errorDiv.className = 'phone-error text-xs mt-1 text-red-600';
                parent.appendChild(errorDiv);
            }
            errorDiv.textContent = 'Please enter a valid 11-digit phone number starting with 09';
        }
    });

    return allValid;
}
</script>