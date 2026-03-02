<div id="editBookingModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-800">Edit Special Event</h3>
                <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <form id="editBookingForm" class="p-6">
            <input type="hidden" id="edit_booking_id">

            {{-- Pass role to JS safely --}}
            <input type="hidden" id="edit_user_role" value="{{ auth()->user()->role }}">

            <div class="grid grid-cols-2 gap-4 mb-6">

                {{-- ===== GUEST INFORMATION ===== --}}
                <div class="col-span-2">
                    <h4 class="text-lg font-medium text-gray-800 mb-3">Guest Information</h4>
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Guest Name</label>
                    <input type="text" id="edit_guest_name" required readonly
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-500 bg-gray-50 cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="edit_email" required readonly
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-500 bg-gray-50 cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" id="edit_phone" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-500">
                </div>

                {{-- ===== EVENT DETAILS ===== --}}
                <div class="col-span-2 mt-4">
                    <h4 class="text-lg font-medium text-gray-800 mb-3">Event Details</h4>
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Event Type</label>
                    <select id="edit_event_name" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-500">
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
                    <select id="edit_booking_status" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-500">
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Number of Guests</label>
                    <input type="number" id="edit_num_guests" min="1" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Event Date</label>
                    <input type="date" id="edit_checkin_date" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Event Start Time</label>
                    <input type="time" id="edit_event_start_time" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Event End Time</label>
                    <input type="time" id="edit_event_end_time" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-500">
                </div>

                {{-- ===== VENUE SELECTION ===== --}}
                <div class="col-span-2 mt-4">
                    <h4 class="text-lg font-medium text-gray-800 mb-3">Event Venue</h4>
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Select Event Venue</label>
                    <select id="edit_unit_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-500">
                        <option value="">Loading venues...</option>
                    </select>
                    <div id="editUnitAvailabilityNotes" class="mt-2 text-sm"></div>
                </div>

                {{-- ===== PRICE BREAKDOWN ===== --}}
                <div class="col-span-2 mt-4">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-lg font-medium text-gray-800">Price Breakdown</h4>

                        {{-- Manager-only toggle — rendered conditionally by Blade --}}
                        @if(auth()->user()->role === 'manager')
                            <label class="flex items-center gap-2 cursor-pointer select-none" title="Manager only: Override computed price">
                                <span class="text-sm text-gray-500">Manual override</span>
                                <div class="relative">
                                    <input type="checkbox" id="edit_price_override_toggle" class="sr-only peer">
                                    <div class="w-10 h-5 bg-gray-300 rounded-full peer peer-checked:bg-violet-500 transition"></div>
                                    <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition peer-checked:translate-x-5"></div>
                                </div>
                            </label>
                        @endif
                    </div>
                </div>

                <div class="col-span-2">
                    <div class="bg-gray-50 p-4 rounded-lg space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Venue Price:</span>
                            <span class="text-sm font-medium" id="edit_unit_price_display">₱0.00</span>
                        </div>
                        <div class="flex justify-between border-t border-gray-200 pt-2">
                            <span class="text-sm font-semibold text-gray-800">Total Price:</span>
                            <span class="text-sm font-bold text-violet-600" id="edit_total_price_display">₱0.00</span>
                        </div>

                        {{-- Manual override input — only rendered for manager, hidden by default --}}
                        @if(auth()->user()->role === 'manager')
                            <div id="edit_manual_price_wrapper" class="hidden border-t border-violet-200 pt-3 mt-1">
                                <label class="block text-sm font-medium text-violet-700 mb-1">
                                    <i class="fas fa-edit mr-1"></i>
                                    Override Total Price (₱)
                                </label>
                                <input type="number" id="edit_manual_total_price"
                                       step="0.01" min="0"
                                       class="w-full px-3 py-2 border-2 border-violet-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-500 text-violet-700 font-semibold text-sm"
                                       placeholder="Enter custom total price">
                                <p class="text-xs text-violet-500 mt-1">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Auto-computed: <span id="edit_auto_price_hint">₱0.00</span>. Overriding will use your entered value instead.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ===== CANCELLATION FIELDS ===== --}}
                <div id="edit_cancellation_fields" class="col-span-2 hidden">
                    <h4 class="text-lg font-medium text-gray-800 mb-3 mt-2">Cancellation Details</h4>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cancellation Reason</label>
                        <input type="text" id="edit_cancellation_reason"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-500"
                               placeholder="Optional reason for cancellation">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Refund Amount</label>
                            <input type="number" id="edit_refund_amount" step="0.01" min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-500"
                                   value="0">
                            <p class="text-xs text-gray-500 mt-1">Max refundable: <span id="edit_max_refund_display">₱0.00</span></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Refund Method</label>
                            <select id="edit_refund_method"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-500">
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
                            <strong>Note:</strong> Changing status to "Cancelled" will trigger a cancellation email to the guest.
                        </p>
                    </div>
                </div>

                {{-- ===== COMPLETED NOTE ===== --}}
                <div id="edit_completed_note" class="col-span-2 hidden">
                    <div class="mt-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                        <p class="text-sm text-green-700">
                            <i class="fas fa-check-circle mr-2"></i>
                            <strong>Note:</strong> Changing status to "Completed" will trigger a thank you email to the guest.
                        </p>
                    </div>
                </div>

                {{-- ===== SPECIAL REQUIREMENTS ===== --}}
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Event Requirements & Notes</label>
                    <textarea id="edit_special_requirements" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-500"
                              placeholder="Any special event requirements, setup needs, or additional notes..."></textarea>
                </div>
            </div>

            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeEditModal()"
                        class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="submit" id="editEventSubmitBtn"
                        class="flex-1 px-4 py-2.5 bg-violet-600 text-white rounded-lg font-medium hover:bg-violet-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed">
                    <span id="editEventSubmitText">Update Event</span>
                    <span id="editEventSubmitSpinner" class="hidden">
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        Updating...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// ============================================================
// EDIT SPECIAL EVENT MODAL — COMPLETE FIX
// ✅ Manager-only price override with proper null checks
// ============================================================

console.log('🔧 Loading FIXED Edit Special Event Modal');

// ---------- Detect current user role from hidden input ----------
const EDIT_USER_ROLE = (document.getElementById('edit_user_role')?.value || '').trim();
const EDIT_IS_MANAGER = EDIT_USER_ROLE === 'manager';

console.log('👤 User role:', EDIT_USER_ROLE, '| Is manager:', EDIT_IS_MANAGER);

// ---------- State Variables ----------
let editEventOriginalUnitId    = '';
let editEventOriginalUnitPrice = 0;
let editEventMaxRefund         = 0;
let editEventOriginalName      = '';
let editEventOriginalEmail     = '';

// ---------- Loading State Functions ----------
function showEditEventLoading() {
    const btn     = document.getElementById('editEventSubmitBtn');
    const text    = document.getElementById('editEventSubmitText');
    const spinner = document.getElementById('editEventSubmitSpinner');

    if (btn)     btn.disabled = true;
    if (text)    text.classList.add('hidden');
    if (spinner) spinner.classList.remove('hidden');
}

function hideEditEventLoading() {
    const btn     = document.getElementById('editEventSubmitBtn');
    const text    = document.getElementById('editEventSubmitText');
    const spinner = document.getElementById('editEventSubmitSpinner');

    if (btn)     btn.disabled = false;
    if (text)    text.classList.remove('hidden');
    if (spinner) spinner.classList.add('hidden');
}

// ---------- Modal Open / Close ----------
function openEditModal() {
    console.log('✅ Opening edit modal');
    const modal = document.getElementById('editBookingModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        if (typeof setupPhoneValidation === 'function') {
            setupPhoneValidation('edit_phone');
        }

        setTimeout(() => {
            document.addEventListener('click', handleEditOutsideClick);
        }, 100);
    }
}

function closeEditModal() {
    console.log('✅ Closing edit modal');
    return new Promise((resolve) => {
        const modal = document.getElementById('editBookingModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Clear phone validation
        const phoneInput = document.getElementById('edit_phone');
        if (phoneInput) {
            phoneInput.classList.remove('border-red-500', 'border-green-500');
            const errDiv = phoneInput.parentNode.querySelector('.phone-error');
            if (errDiv) errDiv.remove();
        }

        resetEditEventModalState();
        document.removeEventListener('click', handleEditOutsideClick);
        setTimeout(() => resolve(), 300);
    });
}

function resetEditEventModalState() {
    editEventOriginalUnitId    = '';
    editEventOriginalUnitPrice = 0;
    editEventMaxRefund         = 0;
    editEventOriginalName      = '';
    editEventOriginalEmail     = '';

    // Hide dynamic sections
    const cancellationFields = document.getElementById('edit_cancellation_fields');
    const completedNote      = document.getElementById('edit_completed_note');
    if (cancellationFields) cancellationFields.classList.add('hidden');
    if (completedNote)      completedNote.classList.add('hidden');

    // Reset price override (manager only — safe null checks)
    const toggle      = document.getElementById('edit_price_override_toggle');
    const wrapper     = document.getElementById('edit_manual_price_wrapper');
    const manualInput = document.getElementById('edit_manual_total_price');

    if (toggle)      toggle.checked    = false;
    if (wrapper)     wrapper.classList.add('hidden');
    if (manualInput) manualInput.value = '';

    // Reset displays
    const unitPriceDisplay  = document.getElementById('edit_unit_price_display');
    const totalPriceDisplay = document.getElementById('edit_total_price_display');
    const autoHint          = document.getElementById('edit_auto_price_hint');

    if (unitPriceDisplay)  unitPriceDisplay.textContent  = '₱0.00';
    if (totalPriceDisplay) totalPriceDisplay.textContent = '₱0.00';
    if (autoHint)          autoHint.textContent           = '₱0.00';

    clearEditEventUnitNotes();
    hideEditEventLoading();
}

function handleEditOutsideClick(event) {
    const modal = document.getElementById('editBookingModal');
    if (!modal) return;
    const modalContent = modal.querySelector('.bg-white');
    if (modalContent && !modalContent.contains(event.target)) {
        closeEditModal();
    }
}

// ---------- Booking Status Change Handler ----------
const editBookingStatusEl = document.getElementById('edit_booking_status');
if (editBookingStatusEl) {
    editBookingStatusEl.addEventListener('change', function () {
        const status             = this.value;
        const cancellationFields = document.getElementById('edit_cancellation_fields');
        const completedNote      = document.getElementById('edit_completed_note');

        if (!cancellationFields || !completedNote) return;

        if (status === 'cancelled') {
            cancellationFields.classList.remove('hidden');
            completedNote.classList.add('hidden');
            if (editEventMaxRefund > 0) {
                const refundInput = document.getElementById('edit_refund_amount');
                if (refundInput) refundInput.value = editEventMaxRefund.toFixed(2);
            }
        } else if (status === 'completed') {
            cancellationFields.classList.add('hidden');
            completedNote.classList.remove('hidden');
        } else {
            cancellationFields.classList.add('hidden');
            completedNote.classList.add('hidden');
        }
    });
}

// ---------- Price Override Toggle Handler (Manager only) ----------
// Safe: the toggle element only exists in the DOM when the user is a manager
const editPriceToggleEl = document.getElementById('edit_price_override_toggle');
if (EDIT_IS_MANAGER && editPriceToggleEl) {
    editPriceToggleEl.addEventListener('change', function () {
        const wrapper      = document.getElementById('edit_manual_price_wrapper');
        const manualInput  = document.getElementById('edit_manual_total_price');
        const autoHint     = document.getElementById('edit_auto_price_hint');
        const totalDisplay = document.getElementById('edit_total_price_display');

        if (!wrapper || !manualInput) return;

        if (this.checked) {
            wrapper.classList.remove('hidden');
            const currentAuto = totalDisplay
                ? totalDisplay.textContent.replace('₱', '').replace(/,/g, '')
                : '0';
            manualInput.value = parseFloat(currentAuto || 0).toFixed(2);
            if (autoHint) autoHint.textContent = '₱' + parseFloat(currentAuto || 0).toFixed(2);
            manualInput.focus();
        } else {
            wrapper.classList.add('hidden');
            manualInput.value = '';
        }
    });
} else if (!EDIT_IS_MANAGER) {
    console.log('ℹ️ Price override toggle not available (manager only)');
}

// ---------- Unit Notes Helpers ----------
function clearEditEventUnitNotes() {
    const notesDiv = document.getElementById('editUnitAvailabilityNotes');
    if (notesDiv) {
        notesDiv.innerHTML = '';
        notesDiv.className = 'mt-2 text-sm';
    }
}

function showEditEventUnitNotes(message, type = 'info') {
    const notesDiv = document.getElementById('editUnitAvailabilityNotes');
    if (!notesDiv) return;

    const icons  = { error: 'fas fa-exclamation-circle', warning: 'fas fa-info-circle', success: 'fas fa-check-circle' };
    const colors = { error: 'text-red-600', warning: 'text-yellow-600', success: 'text-green-600' };

    notesDiv.innerHTML = `
        <div class="flex items-center ${colors[type] || colors.success}">
            <i class="${icons[type] || icons.success} mr-2"></i>${message}
        </div>`;
    notesDiv.className = `mt-2 text-sm ${colors[type] || colors.success}`;
}

// ---------- Date / Time Helpers ----------
function editEventFormatDateForInput(dateString) {
    if (!dateString) return '';
    if (dateString.match(/^\d{4}-\d{2}-\d{2}$/)) return dateString;
    try {
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return '';
        const y = date.getFullYear();
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const d = String(date.getDate()).padStart(2, '0');
        return `${y}-${m}-${d}`;
    } catch (e) {
        console.error('Date format error:', e);
        return '';
    }
}

function editEventFormatTimeForInput(timeString) {
    if (!timeString) return '08:00';
    if (timeString.match(/^\d{2}:\d{2}$/))    return timeString;
    if (timeString.match(/^\d{2}:\d{2}:\d{2}$/)) return timeString.substring(0, 5);
    try {
        const testDate = new Date('1970-01-01T' + timeString + 'Z');
        if (!isNaN(testDate.getTime())) return testDate.toTimeString().substring(0, 5);
    } catch (e) {
        console.error('Time format error:', e);
    }
    return '08:00';
}

// ---------- Load Payment Summary ----------
function loadEditEventPaymentSummary(bookingId) {
    console.log('📊 Loading payment summary for booking:', bookingId);

    fetch(`/admin/special-events/${bookingId}/payments`)
        .then(r => r.json())
        .then(data => {
            if (!data.success) return;

            let totalPaid     = 0;
            let totalRefunded = 0;

            (data.data || []).forEach(p => {
                if (p.paymentStatus === 'completed') {
                    if (p.paymentType === 'refund') {
                        totalRefunded += parseFloat(p.amountPaid || 0);
                    } else {
                        totalPaid += parseFloat(p.amountPaid || 0);
                    }
                }
            });

            const netPaid       = Math.max(0, totalPaid - totalRefunded);
            editEventMaxRefund  = netPaid;

            const refundInput = document.getElementById('edit_refund_amount');
            const maxDisplay  = document.getElementById('edit_max_refund_display');

            if (refundInput) {
                refundInput.value = netPaid > 0 ? netPaid.toFixed(2) : '0.00';
                refundInput.setAttribute('max', netPaid);
            }
            if (maxDisplay) {
                maxDisplay.textContent = '₱' + netPaid.toFixed(2);
            }

            console.log('✅ Payment summary loaded:', { totalPaid, totalRefunded, netPaid });
        })
        .catch(err => console.error('❌ Error loading payment summary:', err));
}

// ---------- Load Available Units ----------
function loadEditEventAvailableUnits() {
    const checkinDate = document.getElementById('edit_checkin_date');
    const unitSelect  = document.getElementById('edit_unit_id');

    if (!checkinDate || !unitSelect) {
        console.error('❌ Required elements not found');
        return;
    }

    const checkinValue = checkinDate.value;
    console.log('📍 Loading units for date:', checkinValue, '| Original unit:', editEventOriginalUnitId);

    clearEditEventUnitNotes();

    if (!checkinValue) {
        fetch('/admin/special-events/units/available?for_special_events=true')
            .then(r => r.json())
            .then(data => {
                if (!data.success) return;
                populateEditEventUnitSelect(data.data, []);
                showEditEventUnitNotes('Select an event date to check availability', 'warning');
            })
            .catch(err => console.error('❌ Error loading all units:', err));
        return;
    }

    const url = `/admin/special-events/units/available?for_special_events=true&checkin_date=${checkinValue}&checkout_date=${checkinValue}&booking_type=special-event`;

    fetch(url)
        .then(r => r.json())
        .then(data => {
            console.log('✅ Units response:', data);

            if (!data.success) {
                showEditEventUnitNotes('Error loading venues', 'error');
                return;
            }

            const available = data.data || [];
            const dateAvail = data.date_availability || {};

            if (dateAvail.available === false) {
                populateEditEventUnitSelect([], available);
                showEditEventUnitNotes(
                    'This date has existing bookings. You can keep the current venue or choose a different date.',
                    'warning'
                );
            } else if (available.length === 0) {
                populateEditEventUnitSelect([], []);
                showEditEventUnitNotes(
                    'No available venues for selected date. You can keep the current venue or choose a different date.',
                    'warning'
                );
            } else {
                populateEditEventUnitSelect(available, []);
                showEditEventUnitNotes(`${available.length} venue(s) available for selected date`, 'success');
            }

            updateEditEventTotalPrice();
        })
        .catch(err => {
            console.error('❌ Error loading units:', err);
            showEditEventUnitNotes('Error loading venues. Please try again.', 'error');
        });
}

// ---------- Populate Unit Select ----------
function populateEditEventUnitSelect(availableUnits, allUnits) {
    const unitSelect = document.getElementById('edit_unit_id');
    if (!unitSelect) return;

    unitSelect.innerHTML = '';

    const list              = availableUnits.length > 0 ? availableUnits : allUnits;
    const isOriginalInList  = list.some(u => String(u.unitID) === String(editEventOriginalUnitId));
    const currentLabel      = isOriginalInList
        ? 'Current Venue (Available)'
        : 'Current Venue (Keep Current)';

    unitSelect.innerHTML += `
        <option value="${editEventOriginalUnitId}" data-price="${editEventOriginalUnitPrice}">
            ${currentLabel}
        </option>`;

    list.forEach(unit => {
        if (String(unit.unitID) !== String(editEventOriginalUnitId)) {
            unitSelect.innerHTML += `
                <option value="${unit.unitID}" data-price="${unit.unitRatePrice}">
                    ${unit.unitName} - ₱${parseFloat(unit.unitRatePrice).toFixed(2)} (Capacity: ${unit.capacity})
                </option>`;
        }
    });

    unitSelect.value = editEventOriginalUnitId;
    console.log('✅ Unit select populated with', list.length, 'units');
}

// ---------- Price Calculation ----------
function updateEditEventTotalPrice() {
    const unitSelect        = document.getElementById('edit_unit_id');
    const unitPriceDisplay  = document.getElementById('edit_unit_price_display');
    const totalPriceDisplay = document.getElementById('edit_total_price_display');
    const autoHint          = document.getElementById('edit_auto_price_hint');

    if (!unitSelect || !unitPriceDisplay || !totalPriceDisplay) return;

    const selectedOption = unitSelect.options[unitSelect.selectedIndex];
    if (!selectedOption || !selectedOption.value) {
        unitPriceDisplay.textContent  = '₱0.00';
        totalPriceDisplay.textContent = '₱0.00';
        if (autoHint) autoHint.textContent = '₱0.00';
        return;
    }

    let unitPrice = parseFloat(selectedOption.getAttribute('data-price'));
    if (isNaN(unitPrice) || unitPrice <= 0) {
        unitPrice = editEventOriginalUnitPrice;
    }

    const totalPrice = unitPrice; // special events: total = venue price only

    unitPriceDisplay.textContent  = '₱' + unitPrice.toFixed(2);
    totalPriceDisplay.textContent = '₱' + totalPrice.toFixed(2);
    if (autoHint) autoHint.textContent = '₱' + totalPrice.toFixed(2);

    console.log('💰 Price updated:', { unitPrice, totalPrice });
}

// ---------- Unit Change Listener ----------
const editUnitSelectEl = document.getElementById('edit_unit_id');
if (editUnitSelectEl) {
    editUnitSelectEl.addEventListener('change', function () {
        console.log('🔄 Unit changed to:', this.value);
        updateEditEventTotalPrice();
    });
}

// ---------- MAIN editEvent Function ----------
window.editEvent = function (bookingId) {
    console.log('📝 Opening edit modal for booking:', bookingId);

    fetch(`/admin/special-events/${bookingId}`)
        .then(r => {
            if (!r.ok) throw new Error('Network error: ' + r.status);
            return r.json();
        })
        .then(data => {
            console.log('✅ Booking data received:', data);

            if (!data.success) throw new Error(data.message || 'Failed to load booking');

            const booking = data.data;

            // Store originals
            editEventOriginalName  = booking.guest_name || '';
            editEventOriginalEmail = booking.email      || '';

            // Get unit ID and price
            if (Array.isArray(booking.units) && booking.units.length > 0) {
                const firstUnit = booking.units[0];
                editEventOriginalUnitId    = String(firstUnit.unitID);
                editEventOriginalUnitPrice = parseFloat(booking.total_price) || 0;

                console.log('📍 Unit from array:', {
                    unitID: firstUnit.unitID,
                    unitName: firstUnit.unitName,
                    backCalculatedPrice: editEventOriginalUnitPrice
                });
            } else {
                editEventOriginalUnitId    = '';
                editEventOriginalUnitPrice = parseFloat(booking.total_price) || 0;
            }

            console.log('💾 Stored:', {
                unitId: editEventOriginalUnitId,
                unitPrice: editEventOriginalUnitPrice,
                totalPrice: booking.total_price
            });

            // Populate form fields
            const fields = {
                'edit_booking_id':         booking.bookingID,
                'edit_guest_name':         editEventOriginalName,
                'edit_email':              editEventOriginalEmail,
                'edit_phone':              booking.phone || '',
                'edit_booking_status':     booking.booking_status || 'pending',
                'edit_num_guests':         booking.num_guests || 1,
                'edit_special_requirements': booking.special_requirements || ''
            };

            Object.keys(fields).forEach(id => {
                const el = document.getElementById(id);
                if (el) el.value = fields[id];
            });

            // Event type
            const eventNameSelect = document.getElementById('edit_event_name');
            if (eventNameSelect) {
                const eventTypeVal = (booking.event_name || '').toLowerCase();
                eventNameSelect.value = eventTypeVal;

                if (eventTypeVal && !eventNameSelect.querySelector(`option[value="${eventTypeVal}"]`)) {
                    const opt       = document.createElement('option');
                    opt.value       = eventTypeVal;
                    opt.textContent = eventTypeVal.charAt(0).toUpperCase() + eventTypeVal.slice(1);
                    eventNameSelect.appendChild(opt);
                    eventNameSelect.value = eventTypeVal;
                }
            }

            // Dates & times
            const checkinInput   = document.getElementById('edit_checkin_date');
            const startTimeInput = document.getElementById('edit_event_start_time');
            const endTimeInput   = document.getElementById('edit_event_end_time');

            if (checkinInput) {
                checkinInput.value = editEventFormatDateForInput(booking.checkin_date);
                checkinInput.min   = new Date().toISOString().split('T')[0];
            }

            if (startTimeInput) startTimeInput.value = editEventFormatTimeForInput(booking.event_start_time);
            if (endTimeInput)   endTimeInput.value   = editEventFormatTimeForInput(booking.event_end_time);

            // Setup date change listener (clone to remove stale listeners)
            if (checkinInput) {
                const newDateInput = checkinInput.cloneNode(true);
                checkinInput.parentNode.replaceChild(newDateInput, checkinInput);

                newDateInput.addEventListener('change', function () {
                    console.log('📅 Date changed to:', this.value);
                    clearEditEventUnitNotes();
                    loadEditEventAvailableUnits();
                });
            }

            // Load payment summary
            loadEditEventPaymentSummary(bookingId);

            // Load units immediately
            loadEditEventAvailableUnits();

            // Open modal
            openEditModal();
        })
        .catch(err => {
            console.error('❌ Error loading event:', err);

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error Loading Event',
                    text: err.message || 'Failed to load event details',
                    confirmButtonColor: '#7c3aed'
                });
            } else {
                alert('Error loading event: ' + err.message);
            }
        });
};

// ---------- Form Submit Handler ----------
const editFormEl = document.getElementById('editBookingForm');
if (editFormEl) {
    editFormEl.addEventListener('submit', async function (e) {
        e.preventDefault();

        console.log('📤 Submitting edit form');

        // Phone validation
        if (typeof validateFormPhoneNumbers === 'function') {
            if (!validateFormPhoneNumbers()) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid Phone Number',
                        text: 'Please fix the phone number validation errors.',
                        confirmButtonColor: '#7c3aed'
                    });
                }
                return;
            }
        }

        // Time validation
        const startTime = document.getElementById('edit_event_start_time')?.value || '';
        const endTime   = document.getElementById('edit_event_end_time')?.value   || '';

        if (startTime && endTime && startTime >= endTime) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid Time',
                    text: 'Event end time must be after start time.',
                    confirmButtonColor: '#7c3aed'
                });
            }
            return;
        }

        // Gather form data
        const bookingId   = document.getElementById('edit_booking_id')?.value        || '';
        const newStatus   = document.getElementById('edit_booking_status')?.value    || 'pending';
        const checkinDate = document.getElementById('edit_checkin_date')?.value      || '';
        const numGuests   = parseInt(document.getElementById('edit_num_guests')?.value || 1);
        const unitId      = document.getElementById('edit_unit_id')?.value           || '';
        const phoneRaw    = document.getElementById('edit_phone')?.value             || '';
        const phone       = phoneRaw.replace(/\D/g, '');
        const specialReqs = document.getElementById('edit_special_requirements')?.value || '';
        const eventName   = document.getElementById('edit_event_name')?.value        || '';

        // Cancellation data
        const cancellationReason = document.getElementById('edit_cancellation_reason')?.value  || '';
        const refundAmount       = parseFloat(document.getElementById('edit_refund_amount')?.value || 0);
        const refundMethod       = document.getElementById('edit_refund_method')?.value || '';

        // ✅ KEY FIX: Safe null check for toggle — only manager has this element
        const toggleEl       = document.getElementById('edit_price_override_toggle');
        const isOverride     = EDIT_IS_MANAGER && toggleEl ? toggleEl.checked : false;
        const manualPriceVal = document.getElementById('edit_manual_total_price')?.value || '';

        let totalPrice = 0;
        if (isOverride && manualPriceVal !== '' && parseFloat(manualPriceVal) >= 0) {
            totalPrice = parseFloat(manualPriceVal);
            console.log('💰 Using manager override price:', totalPrice);
        } else {
            const autoDisplayVal = document.getElementById('edit_total_price_display')?.textContent || '0';
            totalPrice = parseFloat(autoDisplayVal.replace('₱', '').replace(/,/g, '')) || 0;
            console.log('💰 Using auto-computed price:', totalPrice);
        }

        // Validation
        if (!unitId) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Venue Selected',
                    text: 'Please select an event venue.',
                    confirmButtonColor: '#7c3aed'
                });
            }
            return;
        }

        if (newStatus === 'cancelled' && refundAmount > editEventMaxRefund) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid Refund Amount',
                    text: `Refund amount cannot exceed ₱${editEventMaxRefund.toFixed(2)}.`,
                    confirmButtonColor: '#7c3aed'
                });
            }
            return;
        }

        // Status confirmations
        if (newStatus === 'cancelled' && typeof Swal !== 'undefined') {
            const result = await Swal.fire({
                icon: 'warning',
                title: 'Cancel Event?',
                text: 'This will send a cancellation email to the guest.',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, cancel event'
            });
            if (!result.isConfirmed) return;
        }

        if (newStatus === 'completed' && typeof Swal !== 'undefined') {
            const result = await Swal.fire({
                icon: 'question',
                title: 'Mark as Completed?',
                text: 'This will send a thank you email to the guest.',
                showCancelButton: true,
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, mark as completed'
            });
            if (!result.isConfirmed) return;
        }

        const payload = {
            guest_name:           editEventOriginalName,
            email:                editEventOriginalEmail,
            phone:                phone,
            event_name:           eventName,
            booking_status:       newStatus,
            checkin_date:         checkinDate,
            num_guests:           numGuests,
            total_price:          totalPrice,
            event_start_time:     startTime,
            event_end_time:       endTime,
            special_requirements: specialReqs,
            unit_id:              unitId,
            cancellation_reason:  cancellationReason,
            refund_amount:        refundAmount,
            refund_method:        refundMethod
        };

        console.log('📦 Payload:', payload);

        showEditEventLoading();

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

            const response = await fetch(`/admin/special-events/${bookingId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(payload)
            });

            const result = await response.json();
            console.log('✅ Update response:', result);

            hideEditEventLoading();

            if (result.success) {
                await closeEditModal();

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Event Updated!',
                        text: 'Special event updated successfully.',
                        confirmButtonColor: '#7c3aed'
                    }).then(() => {
                        if (
                            typeof loadBookings === 'function' &&
                            typeof getCurrentStatus === 'function' &&
                            typeof getCurrentSearch === 'function' &&
                            typeof currentPage !== 'undefined'
                        ) {
                            loadBookings(getCurrentStatus(), getCurrentSearch(), currentPage);
                        } else {
                            location.reload();
                        }
                    });
                } else {
                    if (typeof loadBookings === 'function') {
                        loadBookings(getCurrentStatus(), getCurrentSearch(), currentPage);
                    } else {
                        location.reload();
                    }
                }
            } else {
                await closeEditModal();

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Update Failed',
                        text: result.message || 'Failed to update event',
                        confirmButtonColor: '#dc2626'
                    });
                }
            }
        } catch (error) {
            console.error('❌ Submit error:', error);
            hideEditEventLoading();
            await closeEditModal();

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Connection Error',
                    text: 'Error updating event: ' + error.message,
                    confirmButtonColor: '#dc2626'
                });
            }
        }
    });
}

// Expose globally
window.closeEditModal = closeEditModal;
window.openEditModal  = openEditModal;

console.log('✅ Edit Special Event Modal — FULLY LOADED AND READY');
</script>