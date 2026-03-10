{{-- Payment Modal with Refund --}}
<div id="paymentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-800">Payment Management</h3>
                <button onclick="closePaymentModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        
        <div class="p-6">
            <input type="hidden" id="payment_booking_id">
            
            {{-- Payment Information Section --}}
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="col-span-2">
                    <h4 class="text-lg font-medium text-gray-800 mb-3">Payment Information</h4>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Total Amount</label>
                    <input type="number" id="payment_total_amount" step="0.01" min="0" readonly
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Remaining Balance</label>
                    <input type="number" id="payment_remaining_balance" step="0.01" min="0" readonly
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Total Paid</label>
                    <input type="number" id="payment_total_paid" step="0.01" min="0" readonly
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Total Refunded</label>
                    <input type="number" id="payment_total_refunded" step="0.01" min="0" readonly
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Net Paid</label>
                    <input type="number" id="payment_net_paid" step="0.01" min="0" readonly
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Refundable Amount</label>
                    <input type="number" id="payment_refundable_amount" step="0.01" min="0" readonly
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            {{-- Add Payment Section --}}
            <div class="border-t border-gray-200 pt-6 mb-4">
                {{-- Toggle Button --}}
                <button type="button" id="togglePaymentFormBtn"
                        onclick="toggleSection('paymentFormBody', 'paymentChevron', 'togglePaymentFormBtn')"
                        class="w-full flex items-center justify-between px-4 py-3 bg-green-50 hover:bg-green-100 border border-green-200 rounded-lg transition-all duration-200 group">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-plus text-white text-sm"></i>
                        </div>
                        <span class="text-base font-semibold text-green-800">Add New Payment</span>
                    </div>
                    <i id="paymentChevron" class="fas fa-chevron-down text-green-600 transition-transform duration-300"></i>
                </button>

                {{-- Collapsible Form Body --}}
                <div id="paymentFormBody" class="hidden mt-4">
                    <form id="paymentForm" class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Payment Type</label>
                            <select id="payment_type" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="downpayment">Downpayment</option>
                                <option value="full">Full Payment</option>
                                <option value="remaining">Remaining Balance</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                            <select id="payment_method" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="cash">Cash</option>
                                <option value="gcash">GCash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="credit_card">Credit Card</option>
                                <option value="debit_card">Debit Card</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Amount Paid</label>
                            <input type="number" id="payment_amount" step="0.01" min="0.01" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Payment Date</label>
                            <input type="date" id="payment_date" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   value="{{ date('Y-m-d') }}">
                        </div>
                        
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Payment Reference</label>
                            <input type="text" id="payment_reference" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Reference number (optional)">
                        </div>
                        
                        <div class="col-span-2">
                            <button type="submit" id="regularPaymentButton"
                                    class="w-full px-4 py-2.5 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed">
                                <span id="regularPaymentText">Add Payment</span>
                                <span id="regularPaymentSpinner" class="hidden">
                                    <i class="fas fa-spinner fa-spin mr-2"></i>
                                    Processing Payment...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Refund Section --}}
            <div class="border-t border-gray-200 pt-4 mb-6">
                {{-- Toggle Button --}}
                <button type="button" id="toggleRefundFormBtn"
                        onclick="toggleSection('refundFormBody', 'refundChevron', 'toggleRefundFormBtn')"
                        class="w-full flex items-center justify-between px-4 py-3 bg-red-50 hover:bg-red-100 border border-red-200 rounded-lg transition-all duration-200 group">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-red-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-undo text-white text-sm"></i>
                        </div>
                        <span class="text-base font-semibold text-red-800">Process Refund</span>
                    </div>
                    <i id="refundChevron" class="fas fa-chevron-down text-red-600 transition-transform duration-300"></i>
                </button>

                {{-- Collapsible Form Body --}}
                <div id="refundFormBody" class="hidden mt-4">
                    <form id="refundForm" class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Refund Amount</label>
                            <input type="number" id="refund_amount" step="0.01" min="0.01" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Refund Date</label>
                            <input type="date" id="refund_date" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   value="{{ date('Y-m-d') }}">
                        </div>
                        
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Refund Reason</label>
                            <textarea id="refund_reason" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Reason for refund (optional)"></textarea>
                        </div>
                        
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Refund Method</label>
                            <select id="refund_method" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="cash">Cash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="gcash">GCash</option>
                                <option value="credit_card">Credit Card Reversal</option>
                            </select>
                        </div>
                        
                        <div class="col-span-2">
                            <button type="submit" id="regularRefundButton"
                                    class="w-full px-4 py-2.5 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed">
                                <span id="regularRefundText">Process Refund</span>
                                <span id="regularRefundSpinner" class="hidden">
                                    <i class="fas fa-spinner fa-spin mr-2"></i>
                                    Processing Refund...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Payment History --}}
            <div class="border-t border-gray-200 pt-6">
                <h4 class="text-lg font-medium text-gray-800 mb-3">Payment History</h4>
                <div id="payment_history" class="space-y-3 max-h-60 overflow-y-auto">
                    <p class="text-gray-500 text-center py-4">No payment history</p>
                </div>
            </div>
        </div>
        
        <div class="flex gap-3 p-6 border-t border-gray-200">
            <button onclick="closePaymentModal()"
                    class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition">
                Close
            </button>
        </div>
    </div>
</div>

<script>
// ============================================
// TOGGLE SECTION (Add Payment / Process Refund)
// ============================================

function toggleSection(bodyId, chevronId, btnId) {
    const body    = document.getElementById(bodyId);
    const chevron = document.getElementById(chevronId);

    const isHidden = body.classList.contains('hidden');

    if (isHidden) {
        // OPEN
        body.classList.remove('hidden');
        // Animate: start from height 0, expand
        body.style.overflow  = 'hidden';
        body.style.maxHeight = '0px';
        body.style.opacity   = '0';
        body.style.transition = 'max-height 0.35s ease, opacity 0.3s ease';

        // Force reflow then animate
        requestAnimationFrame(() => {
            body.style.maxHeight = body.scrollHeight + 'px';
            body.style.opacity   = '1';
        });

        // Rotate chevron down → up
        chevron.style.transform = 'rotate(180deg)';

        // After animation, remove inline style constraints
        setTimeout(() => {
            body.style.maxHeight = 'none';
            body.style.overflow  = '';
        }, 350);
    } else {
        // CLOSE
        body.style.overflow  = 'hidden';
        body.style.maxHeight = body.scrollHeight + 'px';
        body.style.opacity   = '1';
        body.style.transition = 'max-height 0.35s ease, opacity 0.3s ease';

        requestAnimationFrame(() => {
            body.style.maxHeight = '0px';
            body.style.opacity   = '0';
        });

        // Rotate chevron up → down
        chevron.style.transform = 'rotate(0deg)';

        setTimeout(() => {
            body.classList.add('hidden');
            body.style.maxHeight = '';
            body.style.overflow  = '';
            body.style.opacity   = '';
        }, 350);
    }
}

// Reset both sections (collapsed) when modal opens
function resetCollapsibleSections() {
    const paymentBody  = document.getElementById('paymentFormBody');
    const refundBody   = document.getElementById('refundFormBody');
    const paymentChev  = document.getElementById('paymentChevron');
    const refundChev   = document.getElementById('refundChevron');

    [paymentBody, refundBody].forEach(el => {
        el.classList.add('hidden');
        el.style.maxHeight = '';
        el.style.opacity   = '';
        el.style.overflow  = '';
    });

    [paymentChev, refundChev].forEach(el => {
        el.style.transform = 'rotate(0deg)';
    });
}

// ============================================
// REGULAR PAYMENT BUTTON LOADING FUNCTIONS
// ============================================

function showRegularPaymentLoading() {
    const paymentBtn     = document.getElementById('regularPaymentButton');
    const paymentText    = document.getElementById('regularPaymentText');
    const paymentSpinner = document.getElementById('regularPaymentSpinner');
    
    if (paymentBtn && paymentText && paymentSpinner) {
        paymentBtn.disabled = true;
        paymentText.classList.add('hidden');
        paymentSpinner.classList.remove('hidden');
    }
}

function hideRegularPaymentLoading() {
    const paymentBtn     = document.getElementById('regularPaymentButton');
    const paymentText    = document.getElementById('regularPaymentText');
    const paymentSpinner = document.getElementById('regularPaymentSpinner');
    
    if (paymentBtn && paymentText && paymentSpinner) {
        paymentBtn.disabled = false;
        paymentText.classList.remove('hidden');
        paymentSpinner.classList.add('hidden');
    }
}

// ============================================
// REGULAR REFUND BUTTON LOADING FUNCTIONS
// ============================================

function showRegularRefundLoading() {
    const refundBtn     = document.getElementById('regularRefundButton');
    const refundText    = document.getElementById('regularRefundText');
    const refundSpinner = document.getElementById('regularRefundSpinner');
    
    if (refundBtn && refundText && refundSpinner) {
        refundBtn.disabled = true;
        refundText.classList.add('hidden');
        refundSpinner.classList.remove('hidden');
    }
}

function hideRegularRefundLoading() {
    const refundBtn     = document.getElementById('regularRefundButton');
    const refundText    = document.getElementById('regularRefundText');
    const refundSpinner = document.getElementById('regularRefundSpinner');
    
    if (refundBtn && refundText && refundSpinner) {
        refundBtn.disabled = false;
        refundText.classList.remove('hidden');
        refundSpinner.classList.add('hidden');
    }
}

// ============================================
// MODAL FUNCTIONS
// ============================================

function openPaymentModal(bookingId) {
    document.getElementById('payment_booking_id').value = bookingId;
    document.getElementById('paymentModal').classList.remove('hidden');
    document.getElementById('paymentModal').classList.add('flex');
    
    // Reset forms
    document.getElementById('paymentForm').reset();
    document.getElementById('refundForm').reset();
    
    // Reset collapsible sections to closed
    resetCollapsibleSections();

    // Reset loading states
    hideRegularPaymentLoading();
    hideRegularRefundLoading();
    
    // Load booking details for payment
    loadBookingForPayment(bookingId);
    
    // Add event listener for outside click
    setTimeout(() => {
        document.addEventListener('click', handlePaymentOutsideClick);
    }, 100);
}

// ✅ PROMISE-BASED MODAL CLOSE
function closePaymentModal() {
    return new Promise((resolve) => {
        const modal = document.getElementById('paymentModal');
        
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        
        document.getElementById('paymentForm').reset();
        document.getElementById('refundForm').reset();
        
        // Collapse sections on close too
        resetCollapsibleSections();

        hideRegularPaymentLoading();
        hideRegularRefundLoading();
        
        document.removeEventListener('click', handlePaymentOutsideClick);
        
        setTimeout(() => {
            resolve();
        }, 300);
    });
}

// Handle outside click for payment modal
function handlePaymentOutsideClick(event) {
    const modal        = document.getElementById('paymentModal');
    const modalContent = modal.querySelector('.bg-white');
    if (!modalContent.contains(event.target)) closePaymentModal();
}

// Load booking details for payment
function loadBookingForPayment(bookingId) {
    fetch(`/admin/bookings/${bookingId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const booking      = data.data;
                const totalAmount  = parseFloat(booking.total_price);
                
                document.getElementById('payment_total_amount').value      = totalAmount.toFixed(2);
                document.getElementById('payment_total_paid').value        = parseFloat(booking.total_paid || 0).toFixed(2);
                document.getElementById('payment_total_refunded').value    = parseFloat(booking.total_refunded || 0).toFixed(2);
                document.getElementById('payment_net_paid').value          = parseFloat(booking.net_paid || 0).toFixed(2);
                document.getElementById('payment_remaining_balance').value = parseFloat(booking.remaining_balance || totalAmount).toFixed(2);
                document.getElementById('payment_refundable_amount').value = parseFloat(booking.net_paid || 0).toFixed(2);
                
                const paymentAmountInput = document.getElementById('payment_amount');
                const remainingBalance   = parseFloat(booking.remaining_balance || totalAmount);
                
                if (remainingBalance > 0) {
                    paymentAmountInput.max = remainingBalance;
                    paymentAmountInput.setAttribute('max', remainingBalance);
                    paymentAmountInput.disabled = false;
                    paymentAmountInput.setAttribute('min', '0.01');
                } else {
                    paymentAmountInput.max = 0;
                    paymentAmountInput.setAttribute('max', 0);
                    paymentAmountInput.disabled = true;
                    paymentAmountInput.value = '0.00';
                    paymentAmountInput.removeAttribute('min');
                }
                
                const refundAmountInput = document.getElementById('refund_amount');
                const refundableAmount  = parseFloat(booking.net_paid || 0);
                
                if (refundableAmount > 0) {
                    refundAmountInput.max = refundableAmount;
                    refundAmountInput.setAttribute('max', refundableAmount);
                    refundAmountInput.disabled = false;
                    refundAmountInput.setAttribute('min', '0.01');
                } else {
                    refundAmountInput.max = 0;
                    refundAmountInput.setAttribute('max', 0);
                    refundAmountInput.disabled = true;
                    refundAmountInput.value = '0.00';
                    refundAmountInput.removeAttribute('min');
                }
                
                loadPaymentHistory(bookingId);
            }
        })
        .catch(error => {
            console.error('Error loading booking for payment:', error);
            Swal.fire({ icon: 'error', title: 'Error!', text: 'Failed to load booking information', confirmButtonColor: '#dc2626' });
            
            const paymentAmountInput = document.getElementById('payment_amount');
            paymentAmountInput.max = 0;
            paymentAmountInput.setAttribute('max', 0);
            
            const refundAmountInput = document.getElementById('refund_amount');
            refundAmountInput.max = 0;
            refundAmountInput.setAttribute('max', 0);
        });
}

// Load payment history
function loadPaymentHistory(bookingId) {
    fetch(`/admin/bookings/${bookingId}/payments`)
        .then(response => response.json())
        .then(data => {
            const paymentHistory     = document.getElementById('payment_history');
            const paymentAmountInput = document.getElementById('payment_amount');
            const refundAmountInput  = document.getElementById('refund_amount');
            
            if (data.success && data.data.length > 0) {
                let totalPaid     = 0;
                let totalRefunded = 0;

                const paymentsHTML = data.data.map(payment => {
                    const paymentAmount = parseFloat(payment.amountPaid);
                    const isRefund      = payment.paymentType === 'refund' || payment.isRefunded;
                    
                    if (isRefund) {
                        totalRefunded += Math.abs(paymentAmount);
                    } else if (payment.paymentStatus === 'completed') {
                        totalPaid += paymentAmount;
                    }
                    
                    return `
                        <div class="flex justify-between items-center p-3 ${isRefund ? 'bg-red-50 border border-red-200' : 'bg-gray-50'} rounded-lg">
                            <div>
                                <p class="font-medium">${payment.paymentType.toUpperCase()} - ${payment.paymentMethod}</p>
                                <p class="text-sm text-gray-600">${payment.paymentDate} • Ref: ${payment.paymentReference}</p>
                                ${isRefund ? `<p class="text-sm text-red-600 font-medium">REFUND</p>` : ''}
                                ${payment.refundDate ? `<p class="text-sm text-red-600">Refunded on: ${payment.refundDate}</p>` : ''}
                                ${payment.refundAmount ? `<p class="text-sm text-red-600">Refund Amount: ₱${parseFloat(payment.refundAmount).toFixed(2)}</p>` : ''}
                                ${payment.refundReason ? `<p class="text-sm text-red-600">Reason: ${payment.refundReason}</p>` : ''}
                            </div>
                            <div class="text-right">
                                <p class="font-bold ${isRefund ? 'text-red-600' : 'text-green-600'}">
                                    ${isRefund ? '-' : ''}₱${Math.abs(paymentAmount).toFixed(2)}
                                </p>
                                <p class="text-sm text-gray-600">Balance: ₱${parseFloat(payment.remainingBalance).toFixed(2)}</p>
                                <p class="text-xs ${getPaymentStatusColor(payment.paymentStatus)}">${payment.paymentStatus.toUpperCase()}</p>
                            </div>
                        </div>
                    `;
                }).join('');
                
                paymentHistory.innerHTML = paymentsHTML;
                
                const totalAmount       = parseFloat(document.getElementById('payment_total_amount').value);
                const netPaid           = totalPaid - totalRefunded;
                const remainingBalance  = Math.max(0, totalAmount - netPaid);
                const refundableAmount  = Math.max(0, netPaid);
                
                document.getElementById('payment_total_paid').value        = totalPaid.toFixed(2);
                document.getElementById('payment_total_refunded').value    = totalRefunded.toFixed(2);
                document.getElementById('payment_net_paid').value          = netPaid.toFixed(2);
                document.getElementById('payment_remaining_balance').value = remainingBalance.toFixed(2);
                document.getElementById('payment_refundable_amount').value = refundableAmount.toFixed(2);
                
                if (remainingBalance > 0) {
                    paymentAmountInput.max = remainingBalance;
                    paymentAmountInput.setAttribute('max', remainingBalance);
                    paymentAmountInput.disabled = false;
                    paymentAmountInput.setAttribute('min', '0.01');
                } else {
                    paymentAmountInput.max = 0;
                    paymentAmountInput.setAttribute('max', 0);
                    paymentAmountInput.disabled = true;
                    paymentAmountInput.value = '0.00';
                    paymentAmountInput.removeAttribute('min');
                }
                
                if (refundableAmount > 0) {
                    refundAmountInput.max = refundableAmount;
                    refundAmountInput.setAttribute('max', refundableAmount);
                    refundAmountInput.disabled = false;
                    refundAmountInput.setAttribute('min', '0.01');
                } else {
                    refundAmountInput.max = 0;
                    refundAmountInput.setAttribute('max', 0);
                    refundAmountInput.disabled = true;
                    refundAmountInput.value = '0.00';
                    refundAmountInput.removeAttribute('min');
                }
            } else {
                paymentHistory.innerHTML = '<p class="text-gray-500 text-center py-4">No payment history</p>';
                
                const totalAmount = parseFloat(document.getElementById('payment_total_amount').value);
                document.getElementById('payment_total_paid').value        = '0.00';
                document.getElementById('payment_total_refunded').value    = '0.00';
                document.getElementById('payment_net_paid').value          = '0.00';
                document.getElementById('payment_remaining_balance').value = totalAmount.toFixed(2);
                document.getElementById('payment_refundable_amount').value = '0.00';
                
                paymentAmountInput.max = totalAmount;
                paymentAmountInput.setAttribute('max', totalAmount);
                paymentAmountInput.disabled = false;
                paymentAmountInput.setAttribute('min', '0.01');
                
                refundAmountInput.max = 0;
                refundAmountInput.setAttribute('max', 0);
                refundAmountInput.disabled = true;
            }
        })
        .catch(error => {
            console.error('Error loading payment history:', error);
            const totalAmount = parseFloat(document.getElementById('payment_total_amount').value);
            document.getElementById('payment_total_paid').value        = '0.00';
            document.getElementById('payment_total_refunded').value    = '0.00';
            document.getElementById('payment_net_paid').value          = '0.00';
            document.getElementById('payment_remaining_balance').value = totalAmount.toFixed(2);
            document.getElementById('payment_refundable_amount').value = '0.00';
            
            const paymentAmountInput = document.getElementById('payment_amount');
            paymentAmountInput.max = totalAmount;
            paymentAmountInput.setAttribute('max', totalAmount);
            paymentAmountInput.disabled = false;
            paymentAmountInput.setAttribute('min', '0.01');
            
            const refundAmountInput = document.getElementById('refund_amount');
            refundAmountInput.max = 0;
            refundAmountInput.setAttribute('max', 0);
            refundAmountInput.disabled = true;
        });
}

// Handle payment type change
document.getElementById('payment_type').addEventListener('change', function() {
    const remainingBalance   = parseFloat(document.getElementById('payment_remaining_balance').value) || 0;
    const paymentAmountInput = document.getElementById('payment_amount');
    
    if (this.value === 'full' && remainingBalance > 0) {
        paymentAmountInput.value    = remainingBalance.toFixed(2);
        paymentAmountInput.readOnly = true;
    } else {
        paymentAmountInput.readOnly = false;
        paymentAmountInput.value    = '';
        if (remainingBalance > 0) paymentAmountInput.disabled = false;
    }
});

// Handle payment amount input
document.getElementById('payment_amount').addEventListener('input', function() {
    const remainingBalance = parseFloat(document.getElementById('payment_remaining_balance').value) || 0;
    const paymentAmount    = parseFloat(this.value) || 0;
    
    if (paymentAmount > remainingBalance) this.value = remainingBalance.toFixed(2);
    if (paymentAmount < 0.01 && this.value !== '') this.value = '0.01';
});

// Handle refund amount input
document.getElementById('refund_amount').addEventListener('input', function() {
    const refundableAmount = parseFloat(document.getElementById('payment_refundable_amount').value) || 0;
    const refundAmount     = parseFloat(this.value) || 0;
    
    if (refundAmount > refundableAmount) this.value = refundableAmount.toFixed(2);
    if (refundAmount < 0.01 && this.value !== '') this.value = '0.01';
});

// ============================================
// ADD PAYMENT
// ============================================

document.getElementById('paymentForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const bookingId        = document.getElementById('payment_booking_id').value;
    const paymentAmount    = parseFloat(document.getElementById('payment_amount').value);
    const remainingBalance = parseFloat(document.getElementById('payment_remaining_balance').value);
    
    if (paymentAmount <= 0) {
        Swal.fire({ icon: 'warning', title: 'Invalid Amount', text: 'Payment amount must be greater than 0!', confirmButtonColor: '#f59e0b' });
        return;
    }
    
    if (paymentAmount > remainingBalance) {
        Swal.fire({ icon: 'warning', title: 'Amount Exceeds Balance', text: `Payment amount cannot exceed remaining balance of ₱${remainingBalance.toFixed(2)}`, confirmButtonColor: '#f59e0b' });
        return;
    }
    
    showRegularPaymentLoading();
    
    const data = {
        payment_type:      document.getElementById('payment_type').value,
        payment_method:    document.getElementById('payment_method').value,
        amount_paid:       paymentAmount,
        payment_date:      document.getElementById('payment_date').value,
        payment_reference: document.getElementById('payment_reference').value || 'PAY-' + Date.now()
    };

    try {
        const response = await fetch(`/admin/bookings/${bookingId}/payments`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        hideRegularPaymentLoading();
        
        if (result.success) {
            await closePaymentModal();
            Swal.fire({ icon: 'success', title: 'Success!', text: 'Payment added successfully!', confirmButtonColor: '#16a34a', confirmButtonText: 'OK' })
                .then(() => loadBookings(getCurrentStatus(), getCurrentSearch(), currentPage));
        } else {
            await closePaymentModal();
            Swal.fire({ icon: 'error', title: 'Error!', text: result.message || 'Failed to add payment', confirmButtonColor: '#dc2626' });
        }
    } catch (error) {
        hideRegularPaymentLoading();
        await closePaymentModal();
        Swal.fire({ icon: 'error', title: 'Error!', text: 'Error adding payment', confirmButtonColor: '#dc2626' });
    }
});

// ============================================
// PROCESS REFUND
// ============================================

document.getElementById('refundForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const bookingId       = document.getElementById('payment_booking_id').value;
    const refundAmount    = parseFloat(document.getElementById('refund_amount').value);
    const refundableAmount = parseFloat(document.getElementById('payment_refundable_amount').value);
    
    if (refundAmount <= 0) {
        Swal.fire({ icon: 'warning', title: 'Invalid Amount', text: 'Refund amount must be greater than 0!', confirmButtonColor: '#f59e0b' });
        return;
    }
    
    if (refundAmount > refundableAmount) {
        Swal.fire({ icon: 'warning', title: 'Amount Exceeds Limit', text: `Refund amount cannot exceed refundable amount of ₱${refundableAmount.toFixed(2)}`, confirmButtonColor: '#f59e0b' });
        return;
    }
    
    const confirmResult = await Swal.fire({
        icon: 'warning',
        title: 'Process Refund?',
        text: `Are you sure you want to process a refund of ₱${refundAmount.toFixed(2)}?`,
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, process refund',
        cancelButtonText: 'Cancel'
    });
    
    if (!confirmResult.isConfirmed) return;
    
    showRegularRefundLoading();
    
    const data = {
        refund_amount: refundAmount,
        refund_date:   document.getElementById('refund_date').value,
        refund_reason: document.getElementById('refund_reason').value,
        refund_method: document.getElementById('refund_method').value
    };

    try {
        const response = await fetch(`/admin/bookings/${bookingId}/refund`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        hideRegularRefundLoading();
        
        if (result.success) {
            await closePaymentModal();
            Swal.fire({ icon: 'success', title: 'Success!', text: 'Refund processed successfully!', confirmButtonColor: '#16a34a', confirmButtonText: 'OK' })
                .then(() => loadBookings(getCurrentStatus(), getCurrentSearch(), currentPage));
        } else {
            await closePaymentModal();
            Swal.fire({ icon: 'error', title: 'Error!', text: result.message || 'Failed to process refund', confirmButtonColor: '#dc2626' });
        }
    } catch (error) {
        hideRegularRefundLoading();
        await closePaymentModal();
        Swal.fire({ icon: 'error', title: 'Error!', text: 'Error processing refund', confirmButtonColor: '#dc2626' });
    }
});
</script>