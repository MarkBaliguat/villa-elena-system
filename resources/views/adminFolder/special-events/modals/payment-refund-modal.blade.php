{{-- Payment Modal with Refund for Special Events --}}
<div id="paymentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-800">Payment Management - Special Event</h3>
                <button onclick="closeSpecialEventPaymentModal()" class="text-gray-400 hover:text-gray-600">
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
            <div class="border-t border-gray-200 pt-6 mb-6">
                <h4 class="text-lg font-medium text-gray-800 mb-3">Add New Payment</h4>
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
                        <button type="submit" id="specialEventPaymentButton"
                                class="w-full px-4 py-2.5 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed">
                            <span id="specialEventPaymentText">Add Payment</span>
                            <span id="specialEventPaymentSpinner" class="hidden">
                                <i class="fas fa-spinner fa-spin mr-2"></i>
                                Processing Payment...
                            </span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Refund Section --}}
            <div class="border-t border-gray-200 pt-6 mb-6">
                <h4 class="text-lg font-medium text-gray-800 mb-3">Process Refund</h4>
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
                        <button type="submit" id="specialEventRefundButton"
                                class="w-full px-4 py-2.5 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed">
                            <span id="specialEventRefundText">Process Refund</span>
                            <span id="specialEventRefundSpinner" class="hidden">
                                <i class="fas fa-spinner fa-spin mr-2"></i>
                                Processing Refund...
                            </span>
                        </button>
                    </div>
                </form>
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
            <button onclick="closeSpecialEventPaymentModal()"
                    class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition">
                Close
            </button>
        </div>
    </div>
</div>

<script>
// ============================================
// SPECIAL EVENTS PAYMENT & REFUND MODAL SCRIPTS - NO LOADING EFFECT ON SWEETALERT
// ============================================

// ============================================
// SPECIAL EVENT PAYMENT BUTTON LOADING FUNCTIONS
// ============================================

function showSpecialEventPaymentLoading() {
    const paymentBtn = document.getElementById('specialEventPaymentButton');
    const paymentText = document.getElementById('specialEventPaymentText');
    const paymentSpinner = document.getElementById('specialEventPaymentSpinner');
    
    if (paymentBtn && paymentText && paymentSpinner) {
        paymentBtn.disabled = true;
        paymentText.classList.add('hidden');
        paymentSpinner.classList.remove('hidden');
    }
}

function hideSpecialEventPaymentLoading() {
    const paymentBtn = document.getElementById('specialEventPaymentButton');
    const paymentText = document.getElementById('specialEventPaymentText');
    const paymentSpinner = document.getElementById('specialEventPaymentSpinner');
    
    if (paymentBtn && paymentText && paymentSpinner) {
        paymentBtn.disabled = false;
        paymentText.classList.remove('hidden');
        paymentSpinner.classList.add('hidden');
    }
}

// ============================================
// SPECIAL EVENT REFUND BUTTON LOADING FUNCTIONS
// ============================================

function showSpecialEventRefundLoading() {
    const refundBtn = document.getElementById('specialEventRefundButton');
    const refundText = document.getElementById('specialEventRefundText');
    const refundSpinner = document.getElementById('specialEventRefundSpinner');
    
    if (refundBtn && refundText && refundSpinner) {
        refundBtn.disabled = true;
        refundText.classList.add('hidden');
        refundSpinner.classList.remove('hidden');
    }
}

function hideSpecialEventRefundLoading() {
    const refundBtn = document.getElementById('specialEventRefundButton');
    const refundText = document.getElementById('specialEventRefundText');
    const refundSpinner = document.getElementById('specialEventRefundSpinner');
    
    if (refundBtn && refundText && refundSpinner) {
        refundBtn.disabled = false;
        refundText.classList.remove('hidden');
        refundSpinner.classList.add('hidden');
    }
}

// ============================================
// MODAL FUNCTIONS
// ============================================

function openSpecialEventPaymentModal(bookingId) {
    console.log('Opening payment modal for special event:', bookingId);
    
    document.getElementById('payment_booking_id').value = bookingId;
    document.getElementById('paymentModal').classList.remove('hidden');
    document.getElementById('paymentModal').classList.add('flex');
    
    // Reset forms first
    document.getElementById('paymentForm').reset();
    document.getElementById('refundForm').reset();
    
    // Reset loading states
    hideSpecialEventPaymentLoading();
    hideSpecialEventRefundLoading();
    
    // Load booking details for payment
    loadSpecialEventForPayment(bookingId);
    
    // Add event listener for outside click
    setTimeout(() => {
        document.addEventListener('click', handlePaymentOutsideClick);
    }, 100);
}

function closeSpecialEventPaymentModal() {
    const modal = document.getElementById('paymentModal');
    
    // Start close animation
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    
    // Reset forms
    document.getElementById('paymentForm').reset();
    document.getElementById('refundForm').reset();
    
    // Reset loading states
    hideSpecialEventPaymentLoading();
    hideSpecialEventRefundLoading();
    
    // Remove event listener
    document.removeEventListener('click', handlePaymentOutsideClick);
}

// Handle outside click for payment modal
function handlePaymentOutsideClick(event) {
    const modal = document.getElementById('paymentModal');
    const modalContent = modal.querySelector('.bg-white');
    if (!modalContent.contains(event.target)) closeSpecialEventPaymentModal();
}

// Load special event details for payment
function loadSpecialEventForPayment(bookingId) {
    console.log('Loading special event for payment:', bookingId);
    
    fetch(`/admin/special-events/${bookingId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Special event payment data:', data);
            
            if (data.success) {
                const booking = data.data;
                const totalAmount = parseFloat(booking.total_price);
                
                document.getElementById('payment_total_amount').value = totalAmount.toFixed(2);
                document.getElementById('payment_remaining_balance').value = parseFloat(booking.remaining_balance || totalAmount).toFixed(2);
                document.getElementById('payment_total_paid').value = parseFloat(booking.total_paid || 0).toFixed(2);
                document.getElementById('payment_total_refunded').value = parseFloat(booking.total_refunded || 0).toFixed(2);
                document.getElementById('payment_net_paid').value = parseFloat(booking.net_paid || 0).toFixed(2);
                document.getElementById('payment_refundable_amount').value = parseFloat(booking.net_paid || 0).toFixed(2);
                
                // Set initial max value for payment amount
                const paymentAmountInput = document.getElementById('payment_amount');
                const remainingBalance = parseFloat(booking.remaining_balance || totalAmount);
                
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
                
                // Set refund amount max value
                const refundAmountInput = document.getElementById('refund_amount');
                const refundableAmount = parseFloat(booking.net_paid || 0);
                
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
                
                // Load payment history
                loadSpecialEventPaymentHistory(bookingId);
            } else {
                console.error('Error loading special event:', data.message);
                Swal.fire({
                    icon: 'error',
                    title: 'Error Loading Details',
                    text: data.message || 'Failed to load event details',
                    confirmButtonColor: '#7c3aed'
                });
            }
        })
        .catch(error => {
            console.error('Error loading special event for payment:', error);
            Swal.fire({
                icon: 'error',
                title: 'Connection Error',
                text: 'Error loading special event details',
                confirmButtonColor: '#7c3aed'
            });
        });
}

// Load payment history for special event
function loadSpecialEventPaymentHistory(bookingId) {
    console.log('Loading payment history for special event:', bookingId);
    
    fetch(`/admin/special-events/${bookingId}/payments`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const paymentHistory = document.getElementById('payment_history');
            const paymentAmountInput = document.getElementById('payment_amount');
            const refundAmountInput = document.getElementById('refund_amount');
            
            console.log('Payment history response:', data);
            
            if (data.success && data.data.length > 0) {
                let totalPaid = 0;
                let totalRefunded = 0;
                const paymentsHTML = data.data.map(payment => {
                    const paymentAmount = parseFloat(payment.amountPaid);
                    const isRefund = payment.paymentType === 'refund' || payment.isRefunded;
                    
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
                
                // Update payment information based on payment history
                const totalAmount = parseFloat(document.getElementById('payment_total_amount').value);
                const netPaid = totalPaid - totalRefunded;
                const remainingBalance = Math.max(0, totalAmount - netPaid);
                const refundableAmount = Math.max(0, netPaid);
                
                document.getElementById('payment_total_paid').value = totalPaid.toFixed(2);
                document.getElementById('payment_total_refunded').value = totalRefunded.toFixed(2);
                document.getElementById('payment_net_paid').value = netPaid.toFixed(2);
                document.getElementById('payment_remaining_balance').value = remainingBalance.toFixed(2);
                document.getElementById('payment_refundable_amount').value = refundableAmount.toFixed(2);
                
                // Update payment amount max value
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
                
                // Update refund amount max value
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
                
                // Set default values when no payment history
                const totalAmount = parseFloat(document.getElementById('payment_total_amount').value);
                document.getElementById('payment_total_paid').value = '0.00';
                document.getElementById('payment_total_refunded').value = '0.00';
                document.getElementById('payment_net_paid').value = '0.00';
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
            // Set safe defaults on error
            const totalAmount = parseFloat(document.getElementById('payment_total_amount').value);
            document.getElementById('payment_total_paid').value = '0.00';
            document.getElementById('payment_total_refunded').value = '0.00';
            document.getElementById('payment_net_paid').value = '0.00';
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

// Get payment status color
function getPaymentStatusColor(status) {
    switch(status.toLowerCase()) {
        case 'completed':
            return 'text-green-600';
        case 'pending':
            return 'text-yellow-600';
        case 'failed':
            return 'text-red-600';
        case 'refunded':
            return 'text-blue-600';
        default:
            return 'text-gray-600';
    }
}

// Handle payment type change
document.getElementById('payment_type').addEventListener('change', function() {
    const remainingBalance = parseFloat(document.getElementById('payment_remaining_balance').value) || 0;
    const paymentAmountInput = document.getElementById('payment_amount');
    
    if (this.value === 'full' && remainingBalance > 0) {
        paymentAmountInput.value = remainingBalance.toFixed(2);
        paymentAmountInput.readOnly = true;
    } else {
        paymentAmountInput.readOnly = false;
        paymentAmountInput.value = '';
        
        // Re-enable input if it was disabled due to zero balance
        if (remainingBalance > 0) {
            paymentAmountInput.disabled = false;
        }
    }
});

// Handle payment amount input
document.getElementById('payment_amount').addEventListener('input', function() {
    const remainingBalance = parseFloat(document.getElementById('payment_remaining_balance').value) || 0;
    const paymentAmount = parseFloat(this.value) || 0;
    
    if (paymentAmount > remainingBalance) {
        this.value = remainingBalance.toFixed(2);
    }
    
    // Ensure minimum value is respected
    if (paymentAmount < 0.01 && this.value !== '') {
        this.value = '0.01';
    }
});

// Handle refund amount input
document.getElementById('refund_amount').addEventListener('input', function() {
    const refundableAmount = parseFloat(document.getElementById('payment_refundable_amount').value) || 0;
    const refundAmount = parseFloat(this.value) || 0;
    
    if (refundAmount > refundableAmount) {
        this.value = refundableAmount.toFixed(2);
    }
    
    // Ensure minimum value is respected
    if (refundAmount < 0.01 && this.value !== '') {
        this.value = '0.01';
    }
});

// ============================================
// ADD PAYMENT - NO LOADING ON SWEETALERT
// ============================================

document.getElementById('paymentForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const bookingId = document.getElementById('payment_booking_id').value;
    const paymentAmount = parseFloat(document.getElementById('payment_amount').value);
    const remainingBalance = parseFloat(document.getElementById('payment_remaining_balance').value);
    
    // ✅ SWEETALERT VALIDATION: Payment amount
    if (paymentAmount <= 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Invalid Amount',
            text: 'Payment amount must be greater than 0!',
            confirmButtonColor: '#7c3aed'
        });
        return;
    }
    
    if (paymentAmount > remainingBalance) {
        Swal.fire({
            icon: 'warning',
            title: 'Amount Exceeds Balance',
            text: `Payment amount cannot exceed remaining balance of ₱${remainingBalance.toFixed(2)}`,
            confirmButtonColor: '#7c3aed'
        });
        return;
    }
    
    // Show loading state
    showSpecialEventPaymentLoading();
    
    // Create payment data object with correct field names
    const data = {
        payment_type: document.getElementById('payment_type').value,
        payment_method: document.getElementById('payment_method').value,
        amount_paid: paymentAmount,
        payment_date: document.getElementById('payment_date').value,
        payment_reference: document.getElementById('payment_reference').value || 'PAY-' + Date.now()
    };

    console.log('Adding payment to special event:', data);

    try {
        const response = await fetch(`/admin/special-events/${bookingId}/payments`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        console.log('Special event payment response:', result);
        
        // ✅ HIDE LOADING FIRST
        hideSpecialEventPaymentLoading();
        
        // ✅ CLOSE MODAL IMMEDIATELY
        closeSpecialEventPaymentModal();
        
        if (result.success) {
            // ✅ SHOW SWEETALERT WITHOUT LOADING
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Payment added successfully!',
                confirmButtonColor: '#16a34a',
                confirmButtonText: 'OK'
            });
            
            // Reload bookings immediately
            if (typeof loadBookings === 'function') {
                loadBookings(getCurrentStatus(), getCurrentSearch(), currentPage);
            }
        } else {
            // ✅ SHOW ERROR WITHOUT LOADING
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: result.message || 'Failed to add payment',
                confirmButtonColor: '#7c3aed'
            });
        }
    } catch (error) {
        // ✅ HIDE LOADING ON ERROR
        hideSpecialEventPaymentLoading();
        console.error('Error:', error);
        
        // ✅ CLOSE MODAL
        closeSpecialEventPaymentModal();
        
        // ✅ SHOW ERROR WITHOUT LOADING
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Error adding payment',
            confirmButtonColor: '#7c3aed'
        });
    }
});

// ============================================
// PROCESS REFUND - NO LOADING ON SWEETALERT
// ============================================

document.getElementById('refundForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const bookingId = document.getElementById('payment_booking_id').value;
    const refundAmount = parseFloat(document.getElementById('refund_amount').value);
    const refundableAmount = parseFloat(document.getElementById('payment_refundable_amount').value);
    
    // ✅ SWEETALERT VALIDATION: Refund amount
    if (refundAmount <= 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Invalid Amount',
            text: 'Refund amount must be greater than 0!',
            confirmButtonColor: '#7c3aed'
        });
        return;
    }
    
    if (refundAmount > refundableAmount) {
        Swal.fire({
            icon: 'warning',
            title: 'Amount Exceeds Limit',
            text: `Refund amount cannot exceed refundable amount of ₱${refundableAmount.toFixed(2)}`,
            confirmButtonColor: '#7c3aed'
        });
        return;
    }
    
    // ✅ SWEETALERT CONFIRMATION
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
    
    // Show loading state
    showSpecialEventRefundLoading();
    
    // Create refund data object
    const data = {
        refund_amount: refundAmount,
        refund_date: document.getElementById('refund_date').value,
        refund_reason: document.getElementById('refund_reason').value,
        refund_method: document.getElementById('refund_method').value
    };

    console.log('Processing refund for special event:', data);

    try {
        const response = await fetch(`/admin/special-events/${bookingId}/refund`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        console.log('Special event refund response:', result);
        
        // ✅ HIDE LOADING FIRST
        hideSpecialEventRefundLoading();
        
        // ✅ CLOSE MODAL IMMEDIATELY
        closeSpecialEventPaymentModal();
        
        if (result.success) {
            // ✅ SHOW SWEETALERT WITHOUT LOADING
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Refund processed successfully!',
                confirmButtonColor: '#16a34a',
                confirmButtonText: 'OK'
            });
            
            // Reload bookings immediately
            if (typeof loadBookings === 'function') {
                loadBookings(getCurrentStatus(), getCurrentSearch(), currentPage);
            }
        } else {
            // ✅ SHOW ERROR WITHOUT LOADING
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: result.message || 'Failed to process refund',
                confirmButtonColor: '#7c3aed'
            });
        }
    } catch (error) {
        // ✅ HIDE LOADING ON ERROR
        hideSpecialEventRefundLoading();
        console.error('Error:', error);
        
        // ✅ CLOSE MODAL
        closeSpecialEventPaymentModal();
        
        // ✅ SHOW ERROR WITHOUT LOADING
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Error processing refund',
            confirmButtonColor: '#7c3aed'
        });
    }
});

// ============================================
// HELPER FUNCTIONS
// ============================================

// Helper functions for main page compatibility
function getCurrentStatus() {
    const statusFilter = document.getElementById('statusFilter');
    return statusFilter ? statusFilter.value : 'all';
}

function getCurrentSearch() {
    const searchInput = document.getElementById('searchInput');
    return searchInput ? searchInput.value : '';
}

// Make functions available globally
window.openSpecialEventPaymentModal = openSpecialEventPaymentModal;
window.closeSpecialEventPaymentModal = closeSpecialEventPaymentModal;
window.loadSpecialEventForPayment = loadSpecialEventForPayment;
window.showSpecialEventPaymentLoading = showSpecialEventPaymentLoading;
window.hideSpecialEventPaymentLoading = hideSpecialEventPaymentLoading;
window.showSpecialEventRefundLoading = showSpecialEventRefundLoading;
window.hideSpecialEventRefundLoading = hideSpecialEventRefundLoading;

console.log('Special Events Payment Modal - No Loading Effect on SweetAlert loaded successfully');
</script>