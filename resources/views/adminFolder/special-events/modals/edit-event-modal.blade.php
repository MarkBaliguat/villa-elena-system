<!-- Edit Special Event Modal -->
<div id="editBookingModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-800">Edit Special Event</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        
        <form id="editBookingForm" class="p-6">
            <input type="hidden" name="booking_id" id="edit_booking_id">
            
            <div class="grid grid-cols-2 gap-4 mb-6">
                <!-- Guest Information -->
                <div class="col-span-2">
                    <h4 class="text-lg font-medium text-gray-800 mb-3">Guest Information</h4>
                </div>
                
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Guest Name</label>
                    <input type="text" name="guest_name" id="edit_guest_name" required readonly
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50 cursor-not-allowed">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="edit_email" required readonly
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50 cursor-not-allowed">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="phone" id="edit_phone" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Event Details -->
                <div class="col-span-2 mt-4">
                    <h4 class="text-lg font-medium text-gray-800 mb-3">Event Details</h4>
                </div>
                
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Event Type</label>
                    <select name="event_name" id="edit_event_name" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Event Type</option>
                        <option value="birthday">Birthday Celebration</option>
                        <option value="wedding">Wedding Reception</option>
                        <option value="corporate">Corporate Event</option>
                        <option value="anniversary">Anniversary</option>
                        <option value="christening">Christening</option>
                        <option value="reunion">Family Reunion</option>
                        <option value="other">Other Special Event</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Event Status</label>
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Event Date</label>
                    <input type="date" name="checkin_date" id="edit_checkin_date" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Event Start Time</label>
                    <input type="time" name="event_start_time" id="edit_event_start_time" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Event End Time</label>
                    <input type="time" name="event_end_time" id="edit_event_end_time" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Total Price</label>
                    <input type="number" name="total_price" id="edit_total_price" step="0.01" min="0" required readonly
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50 cursor-not-allowed">
                    <p class="text-xs text-gray-500 mt-1">Price is calculated based on venue selection and cannot be modified</p>
                </div>
                
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Event Requirements & Notes</label>
                    <textarea name="special_requirements" id="edit_special_requirements" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Any special event requirements, setup needs, or additional notes..."></textarea>
                </div>
            </div>
            
            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeEditModal()"
                        class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 px-4 py-2.5 bg-violet-600 text-white rounded-lg font-medium hover:bg-violet-700 transition">
                    Update Event
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// ============================================
// EDIT SPECIAL EVENT MODAL SCRIPTS - NO LOADING EFFECT ON SWEETALERT
// ============================================

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

function closeEditModal() {
    document.getElementById('editBookingModal').classList.add('hidden');
    document.getElementById('editBookingModal').classList.remove('flex');
    
    // Clear phone validation styling
    const phoneInput = document.getElementById('edit_phone');
    if (phoneInput) {
        phoneInput.classList.remove('border-red-500', 'border-green-500');
        const errorDiv = phoneInput.parentNode.querySelector('.phone-error');
        if (errorDiv) errorDiv.remove();
    }
    
    // Remove event listener
    document.removeEventListener('click', handleEditOutsideClick);
}

// Handle outside click for edit modal
function handleEditOutsideClick(event) {
    const modal = document.getElementById('editBookingModal');
    const modalContent = modal.querySelector('.bg-white');
    if (!modalContent.contains(event.target)) closeEditModal();
}

// Format date for HTML date input (YYYY-MM-DD)
function formatDateForInput(dateString) {
    if (!dateString) return '';
    
    const date = new Date(dateString);
    
    if (isNaN(date.getTime())) {
        console.error('Invalid date:', dateString);
        return '';
    }
    
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    
    return `${year}-${month}-${day}`;
}

// Format time for HTML time input (HH:MM)
function formatTimeForInput(timeString) {
    if (!timeString) return '08:00'; // Default time
    
    console.log('Original time string:', timeString);
    
    // If time is already in HH:MM format
    if (timeString.match(/^\d{2}:\d{2}$/)) {
        return timeString;
    }
    
    // If time is in HH:MM:SS format
    if (timeString.match(/^\d{1,2}:\d{2}:\d{2}$/)) {
        const [hours, minutes] = timeString.split(':');
        return `${hours.padStart(2, '0')}:${minutes}`;
    }
    
    // If time is in 12-hour format with AM/PM
    if (timeString.match(/^\d{1,2}:\d{2}\s?(AM|PM)$/i)) {
        const timeParts = timeString.split(/(?=[AP]M)/i);
        const [time, period] = timeParts;
        const [hours, minutes] = time.split(':');
        
        let hour24 = parseInt(hours);
        if (period.toLowerCase() === 'pm' && hour24 < 12) {
            hour24 += 12;
        } else if (period.toLowerCase() === 'am' && hour24 === 12) {
            hour24 = 0;
        }
        
        return `${hour24.toString().padStart(2, '0')}:${minutes}`;
    }
    
    // If it's just hours and minutes without seconds (single digit hours)
    if (timeString.match(/^\d{1,2}:\d{2}$/)) {
        const [hours, minutes] = timeString.split(':');
        return `${hours.padStart(2, '0')}:${minutes}`;
    }
    
    // If it's a time object or has unexpected format, try to parse it
    try {
        // Try to create a date object with the time
        const testDate = new Date('1970-01-01T' + timeString + 'Z');
        if (!isNaN(testDate.getTime())) {
            return testDate.toTimeString().substring(0, 5);
        }
    } catch (e) {
        console.warn('Could not parse time:', timeString, e);
    }
    
    console.warn('Using default time for:', timeString);
    return '08:00'; // Fallback to default time
}

// Edit special event function - NO LOADING ON SWEETALERT
function editEvent(bookingId) {
    console.log('Editing special event ID:', bookingId);
    
    fetch(`/admin/special-events/${bookingId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log('Edit special event API response:', data);
            
            if (data.success) {
                const booking = data.data;
                
                console.log('Booking data for edit:', {
                    eventType: booking.event_name,
                    startTime: booking.event_start_time,
                    endTime: booking.event_end_time,
                    fullData: booking
                });
                
                // Set form values
                document.getElementById('edit_booking_id').value = booking.bookingID;
                document.getElementById('edit_guest_name').value = booking.guest_name || '';
                document.getElementById('edit_email').value = booking.email || '';
                document.getElementById('edit_phone').value = booking.phone || '';
                
                // EVENT TYPE - Use lowercase value to match database enum
                const eventNameSelect = document.getElementById('edit_event_name');
                let eventTypeValue = (booking.event_name || '').toLowerCase();
                
                console.log('Event type value to set:', eventTypeValue);
                
                if (eventNameSelect) {
                    // Set the value directly (now matches because dropdown uses lowercase)
                    eventNameSelect.value = eventTypeValue;
                    
                    // If value not in options and not empty, add it as a new option
                    if (eventTypeValue && !eventNameSelect.querySelector(`option[value="${eventTypeValue}"]`)) {
                        console.log('Adding new event type option:', eventTypeValue);
                        const newOption = document.createElement('option');
                        newOption.value = eventTypeValue;
                        newOption.textContent = eventTypeValue.charAt(0).toUpperCase() + eventTypeValue.slice(1);
                        eventNameSelect.appendChild(newOption);
                        eventNameSelect.value = eventTypeValue;
                    }
                    
                    // If still not set, set to "other"
                    if (!eventNameSelect.value && eventTypeValue) {
                        eventNameSelect.value = 'other';
                    }
                }
                
                document.getElementById('edit_booking_status').value = booking.booking_status || 'pending';
                document.getElementById('edit_num_guests').value = booking.num_guests || 1;
                
                // Set total price as readonly
                const totalPriceInput = document.getElementById('edit_total_price');
                totalPriceInput.value = parseFloat(booking.total_price || 0).toFixed(2);
                totalPriceInput.readOnly = true;
                
                document.getElementById('edit_special_requirements').value = booking.special_requirements || '';
                
                // Format date properly for date input
                const eventDate = formatDateForInput(booking.checkin_date);
                document.getElementById('edit_checkin_date').value = eventDate;
                console.log('Event date set to:', eventDate);
                
                // TIME FIELDS - Format properly
                const startTime = formatTimeForInput(booking.event_start_time);
                const endTime = formatTimeForInput(booking.event_end_time);
                
                console.log('Time fields processing:', {
                    rawStart: booking.event_start_time,
                    rawEnd: booking.event_end_time,
                    formattedStart: startTime,
                    formattedEnd: endTime
                });
                
                document.getElementById('edit_event_start_time').value = startTime;
                document.getElementById('edit_event_end_time').value = endTime;
                
                // Verify the values were set correctly
                setTimeout(() => {
                    console.log('Final form values:', {
                        eventType: document.getElementById('edit_event_name').value,
                        startTime: document.getElementById('edit_event_start_time').value,
                        endTime: document.getElementById('edit_event_end_time').value,
                        date: document.getElementById('edit_checkin_date').value,
                        price: document.getElementById('edit_total_price').value
                    });
                }, 100);
                
                openEditModal();
            } else {
                // ✅ SWEETALERT - Error loading event (NO LOADING)
                Swal.fire({
                    icon: 'error',
                    title: 'Error Loading Event',
                    text: data.message || 'Unknown error occurred',
                    confirmButtonColor: '#7c3aed'
                });
            }
        })
        .catch(error => {
            console.error('Error loading special event:', error);
            // ✅ SWEETALERT - Network error (NO LOADING)
            Swal.fire({
                icon: 'error',
                title: 'Connection Error',
                text: 'Failed to load event details: ' + error.message,
                confirmButtonColor: '#7c3aed'
            });
        });
}

// Update special event - NO LOADING ON SWEETALERT
document.getElementById('editBookingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validate phone numbers before submission
    if (!validateFormPhoneNumbers()) {
        Swal.fire({
            icon: 'warning',
            title: 'Invalid Phone Number',
            text: 'Please fix the phone number validation errors before submitting.',
            confirmButtonColor: '#7c3aed'
        });
        return;
    }
    
    // Validate time
    const startTime = document.getElementById('edit_event_start_time').value;
    const endTime = document.getElementById('edit_event_end_time').value;
    if (startTime >= endTime) {
        Swal.fire({
            icon: 'warning',
            title: 'Invalid Time',
            text: 'Event end time must be after start time',
            confirmButtonColor: '#7c3aed'
        });
        return;
    }
    
    const bookingId = document.getElementById('edit_booking_id').value;
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    console.log('Updating special event data:', {
        bookingId: bookingId,
        formData: data,
        eventType: data.event_name,
        startTime: data.event_start_time,
        endTime: data.event_end_time
    });

    // Clean phone number before sending
    if (data.phone) {
        data.phone = data.phone.replace(/\D/g, '');
    }

    // Remove booking_id from data before sending
    delete data.booking_id;

    // ✅ CLOSE MODAL IMMEDIATELY
    closeEditModal();

    fetch(`/admin/special-events/${bookingId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        console.log('Update response:', data);
        if (data.success) {
            // ✅ SWEETALERT - Success (NO LOADING)
            Swal.fire({
                icon: 'success',
                title: 'Event Updated!',
                text: 'Special event has been updated successfully',
                confirmButtonColor: '#7c3aed'
            });
            
            // Reload bookings immediately
            loadBookings(getCurrentStatus(), getCurrentSearch(), currentPage);
        } else {
            // ✅ SWEETALERT - Update error (NO LOADING)
            Swal.fire({
                icon: 'error',
                title: 'Update Failed',
                text: data.message || 'Failed to update special event',
                confirmButtonColor: '#7c3aed'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // ✅ SWEETALERT - Network error (NO LOADING)
        Swal.fire({
            icon: 'error',
            title: 'Connection Error',
            text: 'Error updating special event: ' + error.message,
            confirmButtonColor: '#7c3aed'
        });
    });
});

// Phone validation function
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

// Validate all phone numbers in form
function validateFormPhoneNumbers() {
    const phoneInputs = document.querySelectorAll('input[type="text"][name="phone"]');
    let allValid = true;

    phoneInputs.forEach(input => {
        const phone = input.value.replace(/\D/g, '');
        if (phone && (phone.length !== 11 || !phone.startsWith('09'))) {
            allValid = false;
            input.classList.add('border-red-500');
            
            // Ensure error message is shown
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

// Make functions available globally
window.editEvent = editEvent;
window.closeEditModal = closeEditModal;
window.openEditModal = openEditModal;

console.log('Edit Special Event Modal - No Loading Effect on SweetAlert loaded successfully');
</script>