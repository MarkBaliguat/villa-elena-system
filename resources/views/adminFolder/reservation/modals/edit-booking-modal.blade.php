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
            <input type="hidden" id="edit_booking_id">

            {{-- Pass role to JS safely --}}
            <input type="hidden" id="edit_booking_user_role" value="{{ auth()->user()->role }}">
            
            <div class="grid grid-cols-2 gap-4 mb-6">

                <!-- ===== GUEST INFORMATION ===== -->
                <div class="col-span-2">
                    <h4 class="text-lg font-medium text-gray-800 mb-3">Guest Information</h4>
                </div>
                
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Guest Name</label>
                    <input type="text" id="edit_guest_name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50 cursor-not-allowed"
                           readonly>
                    <div id="name-change-warning" class="mt-1 text-sm text-amber-600 hidden">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        Name cannot be changed once booking is created
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="edit_email" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50 cursor-not-allowed"
                           readonly>
                    <div id="email-change-warning" class="mt-1 text-sm text-amber-600 hidden">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        Email cannot be changed once booking is created
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" id="edit_phone" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- ===== BOOKING DETAILS ===== -->
                <div class="col-span-2 mt-4">
                    <h4 class="text-lg font-medium text-gray-800 mb-3">Booking Details</h4>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Booking Status</label>
                    <select id="edit_booking_status" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Number of Guests</label>
                    <input type="number" id="edit_num_guests" min="1" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Check-in Date</label>
                    <input type="date" id="edit_checkin_date" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div id="edit_checkout_date_wrapper">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Check-out Date</label>
                    <input type="date" id="edit_checkout_date"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- ===== UNIT INFORMATION ===== -->
                <div class="col-span-2 mt-4">
                    <h4 class="text-lg font-medium text-gray-800 mb-3">Unit Information</h4>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit Type</label>
                    <input type="text" id="edit_unit_type_display" readonly
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Booking Type</label>
                    <input type="text" id="edit_booking_type_display" readonly
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed">
                </div>
                
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Select Unit</label>
                    <select id="edit_unit_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Loading units...</option>
                    </select>
                    <div id="editUnitAvailabilityNotes" class="mt-2 text-sm"></div>
                </div>

                <!-- ===== PRICE BREAKDOWN ===== -->
                <div class="col-span-2 mt-4">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-lg font-medium text-gray-800">Price Breakdown</h4>

                        {{-- Manager-only toggle --}}
                        @if(auth()->user()->role === 'manager')
                            <label class="flex items-center gap-2 cursor-pointer select-none" title="Manager only: Override computed price">
                                <span class="text-sm text-gray-500">Manual override</span>
                                <div class="relative">
                                    <input type="checkbox" id="edit_price_override_toggle" class="sr-only peer">
                                    <div class="w-10 h-5 bg-gray-300 rounded-full peer peer-checked:bg-blue-500 transition"></div>
                                    <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition peer-checked:translate-x-5"></div>
                                </div>
                            </label>
                        @endif
                    </div>
                </div>
                
                <div class="col-span-2">
                    <div class="bg-gray-50 p-4 rounded-lg space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Unit Price:</span>
                            <span class="text-sm font-medium" id="edit_unit_price_display">₱0.00</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">
                                Entrance Fee (<span id="edit_entrance_fee_value">₱0.00</span> × <span id="edit_guest_count">0</span> guests):
                            </span>
                            <span class="text-sm font-medium" id="edit_entrance_fee_total">₱0.00</span>
                        </div>
                        <div class="flex justify-between items-center border-t border-gray-200 pt-2">
                            <span class="text-sm font-semibold text-gray-800">Total Price:</span>
                            <span class="text-sm font-bold text-blue-600" id="edit_total_price_display">₱0.00</span>
                        </div>

                        {{-- Manual override input — manager only, hidden by default --}}
                        @if(auth()->user()->role === 'manager')
                            <div id="edit_manual_price_wrapper" class="hidden border-t border-blue-200 pt-3 mt-1">
                                <label class="block text-sm font-medium text-blue-700 mb-1">
                                    <i class="fas fa-edit mr-1"></i>
                                    Override Total Price (₱)
                                </label>
                                <input type="number" id="edit_manual_total_price"
                                       step="0.01" min="0"
                                       class="w-full px-3 py-2 border-2 border-blue-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-blue-700 font-semibold text-sm"
                                       placeholder="Enter custom total price">
                                <p class="text-xs text-blue-500 mt-1">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Auto-computed: <span id="edit_auto_price_hint">₱0.00</span>. Overriding will use your entered value instead.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- ===== CANCELLATION FIELDS ===== -->
                <div id="cancellation_fields" class="col-span-2 hidden">
                    <h4 class="text-lg font-medium text-gray-800 mb-3 mt-2">Cancellation Details</h4>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cancellation Reason</label>
                        <input type="text" id="edit_cancellation_reason"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Optional reason for cancellation">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Refund Amount</label>
                            <input type="number" id="edit_refund_amount" step="0.01" min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   value="0">
                            <p class="text-xs text-gray-500 mt-1">Max refundable: <span id="edit_max_refund_display">₱0.00</span></p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Refund Method</label>
                            <select id="edit_refund_method"
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

                <!-- ===== COMPLETED NOTE ===== -->
                <div id="completed_note" class="col-span-2 hidden">
                    <div class="mt-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                        <p class="text-sm text-green-700">
                            <i class="fas fa-check-circle mr-2"></i>
                            <strong>Note:</strong> Changing status to "Completed" will trigger a thank you email to the guest.
                        </p>
                    </div>
                </div>
                
                <!-- ===== SPECIAL REQUIREMENTS ===== -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Special Requirements</label>
                    <textarea id="edit_special_requirements" rows="3"
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
// EDIT BOOKING MODAL SCRIPTS
// ============================================

// ── Detect user role from hidden input (safe for all roles) ──
const EDIT_BOOKING_USER_ROLE = (document.getElementById('edit_booking_user_role')?.value || '').trim();
const EDIT_BOOKING_IS_MANAGER = EDIT_BOOKING_USER_ROLE === 'manager';

console.log('👤 Edit Booking | User role:', EDIT_BOOKING_USER_ROLE, '| Is manager:', EDIT_BOOKING_IS_MANAGER);

// State variables
let originalName         = '';
let originalEmail        = '';
let originalBookingType  = '';
let originalUnitId       = '';
let originalUnitType     = '';
let originalBookingId    = '';
let editCurrentEntranceFee = 0;
let hasEditSpecialEventConflict = false;
let originalUnitPrice    = 0;
let editMaxRefund        = 0;

// ─────────────────────────────────────────
// LOADING STATE
// ─────────────────────────────────────────
function showUpdateLoading() {
    const btn     = document.getElementById('updateBookingBtn');
    const text    = document.getElementById('updateText');
    const spinner = document.getElementById('updateSpinner');
    if (btn && text && spinner) {
        btn.disabled = true;
        text.classList.add('hidden');
        spinner.classList.remove('hidden');
    }
}

function hideUpdateLoading() {
    const btn     = document.getElementById('updateBookingBtn');
    const text    = document.getElementById('updateText');
    const spinner = document.getElementById('updateSpinner');
    if (btn && text && spinner) {
        btn.disabled = false;
        text.classList.remove('hidden');
        spinner.classList.add('hidden');
    }
}

// ─────────────────────────────────────────
// OPEN / CLOSE MODAL
// ─────────────────────────────────────────
function openEditModal() {
    const modal = document.getElementById('editBookingModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    setupPhoneValidation('edit_phone');

    setTimeout(() => {
        document.addEventListener('click', handleEditOutsideClick);
    }, 100);
}

function closeEditModal() {
    return new Promise((resolve) => {
        const modal = document.getElementById('editBookingModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');

        // Clear phone validation styling
        const phoneInput = document.getElementById('edit_phone');
        if (phoneInput) {
            phoneInput.classList.remove('border-red-500', 'border-green-500');
            const errorDiv = phoneInput.parentNode.querySelector('.phone-error');
            if (errorDiv) errorDiv.remove();
        }

        resetEditModalState();
        document.removeEventListener('click', handleEditOutsideClick);
        setTimeout(() => resolve(), 300);
    });
}

function resetEditModalState() {
    originalName               = '';
    originalEmail              = '';
    originalBookingType        = '';
    originalUnitId             = '';
    originalUnitType           = '';
    originalBookingId          = '';
    editCurrentEntranceFee     = 0;
    hasEditSpecialEventConflict = false;
    originalUnitPrice          = 0;
    editMaxRefund              = 0;

    // Hide dynamic sections
    document.getElementById('cancellation_fields')?.classList.add('hidden');
    document.getElementById('completed_note')?.classList.add('hidden');
    document.getElementById('name-change-warning')?.classList.add('hidden');
    document.getElementById('email-change-warning')?.classList.add('hidden');

    clearEditConflictMessage();
    clearEditUnitNotes();
    hideUpdateLoading();

    // Reset price displays
    const priceFields = {
        'edit_unit_price_display':  '₱0.00',
        'edit_entrance_fee_total':  '₱0.00',
        'edit_total_price_display': '₱0.00',
        'edit_entrance_fee_value':  '₱0.00',
        'edit_guest_count':         '0',
        'edit_auto_price_hint':     '₱0.00'
    };
    Object.entries(priceFields).forEach(([id, val]) => {
        const el = document.getElementById(id);
        if (el) el.textContent = val;
    });

    // ✅ Safe null checks — toggle/wrapper/input only exist for managers
    const toggle      = document.getElementById('edit_price_override_toggle');
    const wrapper     = document.getElementById('edit_manual_price_wrapper');
    const manualInput = document.getElementById('edit_manual_total_price');
    if (toggle)      toggle.checked    = false;
    if (wrapper)     wrapper.classList.add('hidden');
    if (manualInput) manualInput.value = '';
}

function handleEditOutsideClick(event) {
    const modal        = document.getElementById('editBookingModal');
    const modalContent = modal.querySelector('.bg-white');
    if (!modalContent.contains(event.target)) closeEditModal();
}

// ─────────────────────────────────────────
// BOOKING STATUS CHANGE LISTENER
// ─────────────────────────────────────────
document.getElementById('edit_booking_status').addEventListener('change', function () {
    const status             = this.value;
    const cancellationFields = document.getElementById('cancellation_fields');
    const completedNote      = document.getElementById('completed_note');

    if (status === 'cancelled') {
        cancellationFields.classList.remove('hidden');
        completedNote.classList.add('hidden');
        if (editMaxRefund > 0) {
            const refundInput = document.getElementById('edit_refund_amount');
            if (refundInput) refundInput.value = editMaxRefund.toFixed(2);
        }
    } else if (status === 'completed') {
        cancellationFields.classList.add('hidden');
        completedNote.classList.remove('hidden');
    } else {
        cancellationFields.classList.add('hidden');
        completedNote.classList.add('hidden');
    }
});

// ─────────────────────────────────────────
// MANUAL PRICE OVERRIDE TOGGLE (Manager only)
// Safe: element only exists in DOM when user is manager
// ─────────────────────────────────────────
const editPriceOverrideToggle = document.getElementById('edit_price_override_toggle');
if (EDIT_BOOKING_IS_MANAGER && editPriceOverrideToggle) {
    editPriceOverrideToggle.addEventListener('change', function () {
        const wrapper     = document.getElementById('edit_manual_price_wrapper');
        const manualInput = document.getElementById('edit_manual_total_price');
        const autoHint    = document.getElementById('edit_auto_price_hint');

        if (!wrapper || !manualInput) return;

        if (this.checked) {
            wrapper.classList.remove('hidden');
            const currentAuto = document.getElementById('edit_total_price_display')?.textContent
                .replace('₱', '').replace(/,/g, '') || '0';
            manualInput.value = parseFloat(currentAuto || 0).toFixed(2);
            if (autoHint) autoHint.textContent = '₱' + parseFloat(currentAuto || 0).toFixed(2);
            manualInput.focus();
        } else {
            wrapper.classList.add('hidden');
            manualInput.value = '';
        }
    });
} else if (!EDIT_BOOKING_IS_MANAGER) {
    console.log('ℹ️ Price override toggle not available (manager only)');
}

// ─────────────────────────────────────────
// CONFLICT MESSAGE HELPERS
// ─────────────────────────────────────────
function clearEditConflictMessage() {
    const msg = document.getElementById('edit-conflict-message');
    if (msg) msg.remove();

    const btn = document.getElementById('updateBookingBtn');
    if (btn) {
        btn.disabled = false;
        btn.classList.remove('opacity-50', 'cursor-not-allowed');
    }
}

function createEditConflictMessage() {
    const div  = document.createElement('div');
    div.id     = 'edit-conflict-message';
    div.className = 'col-span-2';

    const textarea = document.getElementById('edit_special_requirements');
    const wrapper  = textarea?.closest('.col-span-2');
    if (wrapper && wrapper.parentNode) {
        wrapper.parentNode.insertBefore(div, wrapper);
    }
    return div;
}

function clearEditUnitNotes() {
    const notesDiv = document.getElementById('editUnitAvailabilityNotes');
    if (notesDiv) {
        notesDiv.innerHTML = '';
        notesDiv.className = 'mt-2 text-sm';
    }
}

function showEditUnitNotes(message, type = 'info') {
    const notesDiv = document.getElementById('editUnitAvailabilityNotes');
    if (!notesDiv) return;

    const icons  = { error: 'fas fa-exclamation-circle', warning: 'fas fa-info-circle', success: 'fas fa-check-circle' };
    const colors = { error: 'text-red-600', warning: 'text-yellow-600', success: 'text-green-600' };

    notesDiv.innerHTML = `<div class="flex items-center ${colors[type] || colors.success}"><i class="${icons[type] || icons.success} mr-2"></i>${message}</div>`;
    notesDiv.className = `mt-2 text-sm ${colors[type] || colors.success}`;
}

// ─────────────────────────────────────────
// DATE FORMAT HELPER
// ─────────────────────────────────────────
function formatDateForInput(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    if (isNaN(date.getTime())) return '';
    const year  = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day   = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

// ─────────────────────────────────────────
// LOAD PAYMENT SUMMARY (for refund max)
// ─────────────────────────────────────────
function loadPaymentSummaryForRefund(bookingId) {
    fetch(`/admin/bookings/${bookingId}/payment-summary`)
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const netPaid = parseFloat(data.data.net_paid || 0);
                editMaxRefund = netPaid;

                const refundInput = document.getElementById('edit_refund_amount');
                const maxDisplay  = document.getElementById('edit_max_refund_display');

                if (refundInput) {
                    refundInput.value = netPaid > 0 ? netPaid.toFixed(2) : '0.00';
                    refundInput.setAttribute('max', netPaid);
                }
                if (maxDisplay) maxDisplay.textContent = '₱' + netPaid.toFixed(2);
            }
        })
        .catch(err => console.error('Error loading payment summary:', err));
}

// ─────────────────────────────────────────
// LOAD AVAILABLE UNITS FOR EDIT
// ─────────────────────────────────────────
function loadEditAvailableUnits() {
    const unitType    = originalUnitType;
    const bookingType = originalBookingType;
    const checkinDate  = document.getElementById('edit_checkin_date').value;
    const checkoutDate = document.getElementById('edit_checkout_date').value;

    console.log('=== LOADING EDIT AVAILABLE UNITS ===');
    console.log({ unitType, bookingType, checkinDate, checkoutDate, originalUnitId });

    if (!unitType) return;

    if (unitType === 'cottage' && bookingType === 'overnight') {
        const sel = document.getElementById('edit_unit_id');
        sel.innerHTML = '<option value="">Cottage units are only available for day-use bookings</option>';
        showEditUnitNotes('Cottage units are not available for overnight bookings', 'error');
        updateEditTotalPrice();
        return;
    }

    let url = `/admin/bookings/units/available?unit_type=${unitType}`;
    if (checkinDate) {
        const finalCheckout = (bookingType === 'day-use') ? checkinDate : (checkoutDate || checkinDate);
        url += `&checkin_date=${checkinDate}&checkout_date=${finalCheckout}&booking_type=${bookingType}`;
    }

    console.log('Fetch URL:', url);

    fetch(url)
        .then(r => r.json())
        .then(data => {
            console.log('Available units response:', data);
            if (!data.success) return;

            const unitSelect = document.getElementById('edit_unit_id');

            if (data.entrance_fee) {
                editCurrentEntranceFee = parseFloat(data.entrance_fee);
                const entranceFeeEl = document.getElementById('edit_entrance_fee_value');
                if (entranceFeeEl) entranceFeeEl.textContent = '₱' + editCurrentEntranceFee.toFixed(2);
            }

            unitSelect.innerHTML = '';

            const isOriginalAvailable = data.data.some(u => u.unitID == originalUnitId);
            let currentLabel = 'Current Unit';

            if (data.has_special_event_conflict) {
                currentLabel += ' (Special Event - Keep Current)';
                hasEditSpecialEventConflict = true;
                showEditUnitNotes('There is a special event on the selected dates. You can keep the current unit or choose different dates.', 'warning');
            } else if (data.data.length === 0) {
                currentLabel += ' (No Other Units Available)';
                hasEditSpecialEventConflict = false;
                showEditUnitNotes('No other units available for selected dates. You can keep current unit or choose different dates.', 'warning');
            } else {
                currentLabel += isOriginalAvailable ? ' (Available)' : ' (Keep Current)';
                hasEditSpecialEventConflict = false;
                showEditUnitNotes(`${data.data.length} unit(s) available for selected dates`, 'success');
            }

            unitSelect.innerHTML += `<option value="${originalUnitId}" data-price="${originalUnitPrice}">${currentLabel}</option>`;

            data.data.forEach(unit => {
                if (unit.unitID != originalUnitId) {
                    unitSelect.innerHTML += `
                        <option value="${unit.unitID}" data-price="${unit.unitRatePrice}">
                            ${unit.unitName} - ₱${parseFloat(unit.unitRatePrice).toFixed(2)} (Capacity: ${unit.capacity})
                        </option>`;
                }
            });

            unitSelect.value = originalUnitId;

            updateEditTotalPrice();
            setTimeout(checkEditDateConflict, 100);
        })
        .catch(err => {
            console.error('Error loading units:', err);
            showEditUnitNotes('Error loading units. Please try again.', 'error');
        });
}

// ─────────────────────────────────────────
// PRICE CALCULATION
// ─────────────────────────────────────────
function calculateEditDaysCount() {
    const checkinDate  = document.getElementById('edit_checkin_date').value;
    const checkoutDate = document.getElementById('edit_checkout_date').value;

    if (!checkinDate || originalBookingType === 'day-use') return 1;
    if (!checkoutDate || checkoutDate === checkinDate) return 1;

    const checkin  = new Date(checkinDate);
    const checkout = new Date(checkoutDate);
    const days     = Math.ceil((checkout - checkin) / (1000 * 3600 * 24));
    return Math.max(1, days);
}

function updateEditTotalPrice() {
    const unitSelect     = document.getElementById('edit_unit_id');
    const selectedOption = unitSelect.options[unitSelect.selectedIndex];
    const numGuests      = parseInt(document.getElementById('edit_num_guests').value) || 1;
    const bookingType    = originalBookingType;
    const unitType       = originalUnitType;

    const unitPriceDisplay  = document.getElementById('edit_unit_price_display');
    const entranceFeeTotal  = document.getElementById('edit_entrance_fee_total');
    const totalPriceDisplay = document.getElementById('edit_total_price_display');
    const guestCount        = document.getElementById('edit_guest_count');

    if (guestCount) guestCount.textContent = numGuests;

    if (!selectedOption || !selectedOption.value) {
        if (unitPriceDisplay)  unitPriceDisplay.textContent  = '₱0.00';
        if (entranceFeeTotal)  entranceFeeTotal.textContent  = '₱0.00';
        if (totalPriceDisplay) totalPriceDisplay.textContent = '₱0.00';
        return;
    }

    let unitPrice = parseFloat(selectedOption.getAttribute('data-price'));
    if (!unitPrice || isNaN(unitPrice)) unitPrice = originalUnitPrice;

    let totalPrice        = 0;
    let entranceFeeAmount = 0;
    const daysCount       = bookingType === 'overnight' ? calculateEditDaysCount() : 1;

    console.log('Edit Price calculation:', { unitType, bookingType, unitPrice, numGuests, daysCount, editCurrentEntranceFee });

    if (unitType === 'cottage') {
        entranceFeeAmount = editCurrentEntranceFee * numGuests;
        totalPrice        = entranceFeeAmount + unitPrice;
        if (unitPriceDisplay) unitPriceDisplay.textContent = '₱' + unitPrice.toFixed(2);
        if (entranceFeeTotal) entranceFeeTotal.textContent = '₱' + entranceFeeAmount.toFixed(2);
    } else {
        if (bookingType === 'day-use') {
            if (numGuests === 1) {
                totalPrice = unitPrice * 2;
                if (unitPriceDisplay) unitPriceDisplay.textContent = '₱' + unitPrice.toFixed(2) + ' × 2 (single guest)';
            } else {
                totalPrice = unitPrice * numGuests;
                if (unitPriceDisplay) unitPriceDisplay.textContent = '₱' + unitPrice.toFixed(2) + ' × ' + numGuests + ' guests';
            }
        } else {
            totalPrice = unitPrice * numGuests * daysCount;
            if (unitPriceDisplay) unitPriceDisplay.textContent = '₱' + unitPrice.toFixed(2) + ' × ' + numGuests + ' guests × ' + daysCount + ' days';
        }
        if (entranceFeeTotal) entranceFeeTotal.textContent = '₱0.00';
    }

    if (totalPriceDisplay) totalPriceDisplay.textContent = '₱' + totalPrice.toFixed(2);

    // Sync auto price hint (only exists for managers — safe null check)
    const autoHint = document.getElementById('edit_auto_price_hint');
    if (autoHint) autoHint.textContent = '₱' + totalPrice.toFixed(2);

    console.log('Computed total price:', totalPrice);
}

// ─────────────────────────────────────────
// UNIT & GUEST CHANGE LISTENERS
// ─────────────────────────────────────────
document.getElementById('edit_unit_id').addEventListener('change', function () {
    console.log('Edit unit changed to:', this.value);
    clearEditConflictMessage();
    updateEditTotalPrice();
    setTimeout(checkEditDateConflict, 100);
});

document.getElementById('edit_num_guests').addEventListener('input', function () {
    updateEditTotalPrice();
});

// ─────────────────────────────────────────
// CONFLICT CHECK
// ─────────────────────────────────────────
function checkEditDateConflict() {
    const bookingId    = document.getElementById('edit_booking_id').value;
    const unitId       = document.getElementById('edit_unit_id').value;
    const checkinDate  = document.getElementById('edit_checkin_date').value;
    const checkoutDate = document.getElementById('edit_checkout_date').value;
    const bookingType  = originalBookingType;

    console.log('=== EDIT CONFLICT CHECK ===', { bookingId, unitId, checkinDate, checkoutDate, bookingType });

    if (!unitId || !checkinDate) {
        console.log('Missing unit or checkin - skipping');
        return;
    }

    const finalCheckout = (bookingType === 'day-use') ? checkinDate : (checkoutDate || checkinDate);

    const params = new URLSearchParams({
        unit_id:            unitId,
        checkin_date:       checkinDate,
        checkout_date:      finalCheckout,
        booking_type:       bookingType,
        exclude_booking_id: bookingId
    });

    fetch(`/admin/bookings/check-availability?${params}`)
        .then(r => {
            if (!r.ok) throw new Error('Network error');
            return r.json();
        })
        .then(data => {
            console.log('Conflict check response:', data);

            const submitBtn   = document.getElementById('updateBookingBtn');
            const conflictDiv = document.getElementById('edit-conflict-message') || createEditConflictMessage();
            const unitSelect  = document.getElementById('edit_unit_id');
            const unitName    = unitSelect.options[unitSelect.selectedIndex]?.text.split(' - ')[0] || 'Selected unit';

            const checkinFmt  = new Date(checkinDate).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            const checkoutFmt = (bookingType === 'overnight' && finalCheckout !== checkinDate)
                ? new Date(finalCheckout).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
                : checkinFmt;

            if (!data.available) {
                console.log('❌ EDIT CONFLICT DETECTED:', data.conflict_type);

                let html = '';
                if (data.conflict_type === 'special_event') {
                    html = `
                        <div class="flex items-start">
                            <i class="fas fa-calendar-times text-orange-500 mt-0.5 mr-2"></i>
                            <div>
                                <strong class="block">Special Event Conflict</strong>
                                <span class="text-sm">Cannot update to ${unitName} due to special event on selected dates.</span>
                                <div class="mt-1 text-xs text-gray-600">Requested: ${bookingType === 'day-use' ? checkinFmt : checkinFmt + ' to ' + checkoutFmt}</div>
                            </div>
                        </div>`;
                    conflictDiv.className = 'col-span-2 p-3 bg-orange-50 border border-orange-200 rounded-lg text-orange-800 text-sm';
                } else if (data.conflict_type === 'unit_blocked') {
                    html = `
                        <div class="flex items-start">
                            <i class="fas fa-ban text-red-500 mt-0.5 mr-2"></i>
                            <div>
                                <strong class="block">Unit Blocked</strong>
                                <span class="text-sm">${data.message}</span>
                            </div>
                        </div>`;
                    conflictDiv.className = 'col-span-2 p-3 bg-red-50 border border-red-200 rounded-lg text-red-800 text-sm';
                } else {
                    const msg = bookingType === 'day-use'
                        ? `${unitName} is already booked for ${checkinFmt}.`
                        : `${unitName} is already booked from ${checkinFmt} to ${checkoutFmt}.`;
                    html = `
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-red-500 mt-0.5 mr-2"></i>
                            <div>
                                <strong class="block">Date Conflict</strong>
                                <span class="text-sm">${msg}</span>
                                <div class="mt-1 text-xs text-gray-600">Please choose different dates or another unit.</div>
                            </div>
                        </div>`;
                    conflictDiv.className = 'col-span-2 p-3 bg-red-50 border border-red-200 rounded-lg text-red-800 text-sm';
                }

                conflictDiv.innerHTML = html;
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');

            } else {
                console.log('✅ NO EDIT CONFLICT');

                const msg = bookingType === 'day-use'
                    ? `${unitName} is available for ${checkinFmt}.`
                    : `${unitName} is available from ${checkinFmt} to ${checkoutFmt}.`;

                conflictDiv.innerHTML = `
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mt-0.5 mr-2"></i>
                        <div>
                            <strong class="block">Unit Available</strong>
                            <span class="text-sm">${msg}</span>
                            <div class="mt-1 text-xs text-gray-600">You can proceed with updating the booking.</div>
                        </div>
                    </div>`;
                conflictDiv.className = 'col-span-2 p-3 bg-green-50 border border-green-200 rounded-lg text-green-800 text-sm';
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        })
        .catch(err => {
            console.error('Error checking availability:', err);
            const btn = document.getElementById('updateBookingBtn');
            if (btn) {
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        });
}

// ─────────────────────────────────────────
// MAIN EDIT BOOKING FUNCTION
// ─────────────────────────────────────────
function editBooking(bookingId) {
    console.log('Opening edit for booking ID:', bookingId);

    fetch(`/admin/bookings/${bookingId}`)
        .then(r => {
            if (!r.ok) throw new Error('Network error');
            return r.json();
        })
        .then(data => {
            console.log('Booking data:', data);

            if (!data.success) {
                Swal.fire({ icon: 'error', title: 'Error!', text: data.message || 'Failed to load booking details', confirmButtonColor: '#dc2626' });
                return;
            }

            const booking = data.data;

            // Store originals
            originalName        = booking.guest_name || '';
            originalEmail       = booking.email       || '';
            originalBookingId   = booking.bookingID;
            originalBookingType = booking.booking_type;

            if (booking.units && booking.units.length > 0) {
                originalUnitId   = booking.units[0].unitID;
                originalUnitType = booking.units[0].unitType;

                const totalPrice = parseFloat(booking.total_price);
                const numGuests  = parseInt(booking.num_guests);

                if (originalUnitType === 'cottage') {
                    originalUnitPrice = totalPrice;
                } else {
                    if (originalBookingType === 'day-use') {
                        originalUnitPrice = numGuests === 1 ? totalPrice / 2 : totalPrice / numGuests;
                    } else {
                        const checkin  = new Date(booking.checkin_date);
                        const checkout = new Date(booking.checkout_date);
                        const days     = Math.max(1, Math.ceil((checkout - checkin) / (1000 * 3600 * 24)));
                        originalUnitPrice = totalPrice / (numGuests * days);
                    }
                }
            } else {
                originalUnitId    = '';
                originalUnitType  = '';
                originalUnitPrice = 0;
            }

            // Populate form fields
            document.getElementById('edit_booking_id').value           = booking.bookingID;
            document.getElementById('edit_guest_name').value           = originalName;
            document.getElementById('edit_email').value                = originalEmail;
            document.getElementById('edit_phone').value                = booking.phone;
            document.getElementById('edit_booking_status').value       = booking.booking_status;
            document.getElementById('edit_num_guests').value           = booking.num_guests;
            document.getElementById('edit_special_requirements').value = booking.special_requirements || '';

            document.getElementById('edit_unit_type_display').value =
                originalUnitType.charAt(0).toUpperCase() + originalUnitType.slice(1);
            document.getElementById('edit_booking_type_display').value =
                originalBookingType === 'day-use' ? 'Day Use' : 'Overnight';

            // Dates
            const checkinDate  = formatDateForInput(booking.checkin_date);
            const checkoutDate = booking.checkout_date ? formatDateForInput(booking.checkout_date) : '';

            const checkinInput  = document.getElementById('edit_checkin_date');
            const checkoutInput = document.getElementById('edit_checkout_date');

            checkinInput.value  = checkinDate;
            checkoutInput.value = checkoutDate;

            const today = new Date().toISOString().split('T')[0];
            checkinInput.min  = today;
            checkoutInput.min = checkinDate;

            if (originalBookingType === 'day-use') {
                checkoutInput.value    = checkinDate;
                checkoutInput.disabled = true;
                document.getElementById('edit_checkout_date_wrapper').style.opacity = '0.5';
            } else {
                checkoutInput.disabled = false;
                document.getElementById('edit_checkout_date_wrapper').style.opacity = '1';
            }

            // Clone inputs to remove stale listeners
            const newCheckinInput  = checkinInput.cloneNode(true);
            const newCheckoutInput = checkoutInput.cloneNode(true);
            checkinInput.parentNode.replaceChild(newCheckinInput, checkinInput);
            checkoutInput.parentNode.replaceChild(newCheckoutInput, checkoutInput);

            // ✅ Check-in date change — with past date validation
            newCheckinInput.addEventListener('change', function () {
                const today = new Date().toISOString().split('T')[0];

                // ✅ Reject manually typed past dates
                if (this.value < today) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid Date',
                        text: 'Check-in date cannot be in the past.',
                        confirmButtonColor: '#f59e0b'
                    });
                    this.value = today;
                    return;
                }

                newCheckoutInput.min = this.value;
                if (originalBookingType === 'day-use') newCheckoutInput.value = this.value;
                clearEditConflictMessage();
                loadEditAvailableUnits();
            });

            // ✅ Check-out date change — with past date + before checkin validation
            newCheckoutInput.addEventListener('change', function () {
                const today = new Date().toISOString().split('T')[0];
                const checkinVal = newCheckinInput.value;

                // ✅ Reject manually typed past dates
                if (this.value < today) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid Date',
                        text: 'Check-out date cannot be in the past.',
                        confirmButtonColor: '#f59e0b'
                    });
                    this.value = checkinVal || today;
                    return;
                }

                // ✅ Reject checkout before checkin
                if (checkinVal && this.value < checkinVal) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid Date',
                        text: 'Check-out date cannot be before check-in date.',
                        confirmButtonColor: '#f59e0b'
                    });
                    this.value = checkinVal;
                    return;
                }

                clearEditConflictMessage();
                loadEditAvailableUnits();
            });

            loadPaymentSummaryForRefund(bookingId);
            loadEditAvailableUnits();
            openEditModal();
        })
        .catch(err => {
            console.error('Error loading booking:', err);
            Swal.fire({ icon: 'error', title: 'Error!', text: 'Error loading booking details', confirmButtonColor: '#dc2626' });
        });
}

// ─────────────────────────────────────────
// FORM SUBMIT
// ─────────────────────────────────────────
document.getElementById('editBookingForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    // Check for active conflict
    const conflictMsg = document.getElementById('edit-conflict-message');
    if (conflictMsg && (
        conflictMsg.textContent.includes('already booked') ||
        conflictMsg.textContent.includes('Special Event Conflict') ||
        conflictMsg.textContent.includes('Unit Blocked')
    )) {
        Swal.fire({ icon: 'error', title: 'Conflict Detected', text: 'Please resolve the conflict before updating the booking.', confirmButtonColor: '#dc2626' });
        return;
    }

    // Phone validation
    if (typeof validateFormPhoneNumbers === 'function' && !validateFormPhoneNumbers()) {
        Swal.fire({ icon: 'warning', title: 'Invalid Phone Number', text: 'Please fix the phone number validation errors before submitting.', confirmButtonColor: '#f59e0b' });
        return;
    }

    // Gather form data
    const bookingId    = document.getElementById('edit_booking_id')?.value           || '';
    const newStatus    = document.getElementById('edit_booking_status')?.value       || 'pending';
    const checkinDate  = document.getElementById('edit_checkin_date')?.value         || '';
    const checkoutDate = document.getElementById('edit_checkout_date')?.value        || '';
    const numGuests    = parseInt(document.getElementById('edit_num_guests')?.value  || 1);
    const unitId       = document.getElementById('edit_unit_id')?.value              || '';
    const phone        = (document.getElementById('edit_phone')?.value || '').replace(/\D/g, '');
    const specialReqs  = document.getElementById('edit_special_requirements')?.value || '';

    const cancellationReason = document.getElementById('edit_cancellation_reason')?.value || '';
    const refundAmount       = parseFloat(document.getElementById('edit_refund_amount')?.value || 0);
    const refundMethod       = document.getElementById('edit_refund_method')?.value || '';

    // ✅ KEY FIX: Safe null check — toggle only exists for managers
    const toggleEl       = document.getElementById('edit_price_override_toggle');
    const isOverride     = EDIT_BOOKING_IS_MANAGER && toggleEl ? toggleEl.checked : false;
    const manualPriceVal = document.getElementById('edit_manual_total_price')?.value || '';
    const autoDisplayVal = document.getElementById('edit_total_price_display')?.textContent || '0';

    let totalPrice = 0;
    if (isOverride && manualPriceVal !== '' && parseFloat(manualPriceVal) >= 0) {
        totalPrice = parseFloat(manualPriceVal);
        console.log('💰 Using manager override price:', totalPrice);
    } else {
        totalPrice = parseFloat(autoDisplayVal.replace('₱', '').replace(/,/g, '')) || 0;
        console.log('💰 Using auto-computed price:', totalPrice);
    }

    // Validate override price if active
    if (isOverride && (manualPriceVal === '' || isNaN(totalPrice) || totalPrice < 0)) {
        Swal.fire({ icon: 'warning', title: 'Invalid Price', text: 'Please enter a valid total price.', confirmButtonColor: '#f59e0b' });
        return;
    }

    if (!unitId) {
        Swal.fire({ icon: 'warning', title: 'No Unit Selected', text: 'Please select a unit before updating.', confirmButtonColor: '#f59e0b' });
        return;
    }

    if (originalBookingType === 'overnight') {
        if (!checkoutDate) {
            Swal.fire({ icon: 'warning', title: 'Missing Check-out Date', text: 'Check-out date is required for overnight bookings.', confirmButtonColor: '#f59e0b' });
            return;
        }
        if (checkoutDate === checkinDate) {
            Swal.fire({ icon: 'warning', title: 'Invalid Dates', text: 'For overnight bookings, check-out date must be after check-in date.', confirmButtonColor: '#f59e0b' });
            return;
        }
    }

    if (newStatus === 'cancelled' && refundAmount > editMaxRefund) {
        Swal.fire({ icon: 'warning', title: 'Invalid Refund Amount', text: `Refund amount cannot exceed ₱${editMaxRefund.toFixed(2)} (net paid amount).`, confirmButtonColor: '#f59e0b' });
        return;
    }

    // Status confirmations
    if (newStatus === 'cancelled') {
        const result = await Swal.fire({
            icon: 'warning', title: 'Cancel Booking?',
            text: 'This will send a cancellation email to the guest. Are you sure?',
            showCancelButton: true,
            confirmButtonColor: '#dc2626', cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, cancel booking', cancelButtonText: 'No, go back'
        });
        if (!result.isConfirmed) return;
    } else if (newStatus === 'completed') {
        const result = await Swal.fire({
            icon: 'question', title: 'Mark as Completed?',
            text: 'This will send a thank you email to the guest. Are you sure?',
            showCancelButton: true,
            confirmButtonColor: '#16a34a', cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, mark as completed', cancelButtonText: 'No, go back'
        });
        if (!result.isConfirmed) return;
    }

    const payload = {
        guest_name:           originalName,
        email:                originalEmail,
        phone:                phone,
        booking_status:       newStatus,
        checkin_date:         checkinDate,
        checkout_date:        originalBookingType === 'day-use' ? checkinDate : checkoutDate,
        num_guests:           numGuests,
        total_price:          totalPrice,
        special_requirements: specialReqs,
        unit_id:              unitId,
        unit_type:            originalUnitType,
        booking_type:         originalBookingType,
        cancellation_reason:  cancellationReason,
        refund_amount:        refundAmount,
        refund_method:        refundMethod
    };

    console.log('=== SUBMITTING UPDATE ===');
    console.log('Booking ID:', bookingId);
    console.log('Payload:', payload);

    showUpdateLoading();

    try {
        const response = await fetch(`/admin/bookings/${bookingId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(payload)
        });

        const result = await response.json();
        console.log('Update response:', result);

        hideUpdateLoading();

        if (result.success) {
            await closeEditModal();
            Swal.fire({
                icon: 'success', title: 'Success!',
                text: 'Booking updated successfully!',
                confirmButtonColor: '#16a34a', confirmButtonText: 'OK'
            }).then(() => {
                loadBookings(getCurrentStatus(), getCurrentSearch(), currentPage);
            });
        } else {
            await closeEditModal();
            Swal.fire({ icon: 'error', title: 'Error!', text: result.message || 'Failed to update booking', confirmButtonColor: '#dc2626' });
        }
    } catch (error) {
        console.error('Submit error:', error);
        hideUpdateLoading();
        await closeEditModal();
        Swal.fire({ icon: 'error', title: 'Error!', text: 'Error updating booking', confirmButtonColor: '#dc2626' });
    }
});
</script>