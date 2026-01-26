{{-- Block/Unblock Dates Modal --}}
@if(auth()->user()->role === 'manager' || auth()->user()->role === 'staff')
<div id="blockModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md relative">
        {{-- Close Button --}}
        <button type="button" onclick="closeBlockModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition">
            <i class="fas fa-times text-xl"></i>
        </button>
        
        <h3 id="modalTitle" class="text-xl font-bold mb-4 pr-8">Block Dates</h3>
        <form id="blockForm" method="POST" action="{{ route('admin.units.block-dates') }}">
            @csrf
            <div id="selectedUnitsField"></div>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Selected Units</label>
                    <div id="selectedUnitsList" class="bg-gray-50 border border-gray-200 rounded-lg p-3 max-h-32 overflow-y-auto">
                        <!-- Selected units will be listed here -->
                    </div>
                </div>
                
                {{-- Block dates section (hidden for unblock) --}}
                <div id="blockDatesSection">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Block Start Date</label>
                        <input type="date" name="blockStartDate" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Block End Date</label>
                        <input type="date" name="blockEndDate" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Block Reason</label>
                        <textarea name="blockReason" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Reason for blocking..."></textarea>
                    </div>
                </div>

                {{-- Unblock confirmation section (hidden for block) --}}
                <div id="unblockConfirmSection" class="hidden">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-yellow-500 mt-1 mr-3"></i>
                            <div>
                                <p class="text-sm text-yellow-800 font-medium">Are you sure you want to unblock these units?</p>
                                <p class="text-xs text-yellow-700 mt-1">This will make them available for booking again.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closeBlockModal()" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white py-2 rounded-lg font-medium transition">
                    Cancel
                </button>
                <button type="submit" id="submitBtn" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg font-medium transition">
                    Block Dates
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// ============================================
// BLOCK/UNBLOCK DATES MODAL FUNCTIONS
// ============================================

/**
 * Opens the block/unblock dates modal with selected units
 */
function openBlockModal() {
    if (selectedUnits.size === 0) {
        alert('Please select at least one unit.');
        return;
    }
    
    // Check if selected units are blocked or available
    const hasBlockedUnits = checkIfUnitsBlocked();
    const hasAvailableUnits = checkIfUnitsAvailable();
    
    // Determine modal mode
    if (hasBlockedUnits && !hasAvailableUnits) {
        // All selected units are blocked - show unblock mode
        setupUnblockMode();
    } else if (hasAvailableUnits && !hasBlockedUnits) {
        // All selected units are available - show block mode
        setupBlockMode();
    } else {
        // Mixed selection
        alert('Please select only blocked units to unblock, or only available units to block.');
        return;
    }
    
    updateSelectedUnitsList();
    document.getElementById('blockModal').classList.remove('hidden');
}

/**
 * Check if any selected units are blocked
 */
function checkIfUnitsBlocked() {
    let hasBlocked = false;
    selectedUnits.forEach(unitId => {
        const card = document.querySelector(`.unit-card[data-unit-id="${unitId}"]`);
        const statusBadge = card?.querySelector('.px-3.py-1.text-xs.font-semibold.rounded-full');
        if (statusBadge && statusBadge.textContent.trim().toLowerCase() === 'blocked') {
            hasBlocked = true;
        }
    });
    return hasBlocked;
}

/**
 * Check if any selected units are available
 */
function checkIfUnitsAvailable() {
    let hasAvailable = false;
    selectedUnits.forEach(unitId => {
        const card = document.querySelector(`.unit-card[data-unit-id="${unitId}"]`);
        const statusBadge = card?.querySelector('.px-3.py-1.text-xs.font-semibold.rounded-full');
        if (statusBadge && statusBadge.textContent.trim().toLowerCase() === 'available') {
            hasAvailable = true;
        }
    });
    return hasAvailable;
}

/**
 * Setup modal for block mode
 */
function setupBlockMode() {
    const modalTitle = document.getElementById('modalTitle');
    const submitBtn = document.getElementById('submitBtn');
    const blockDatesSection = document.getElementById('blockDatesSection');
    const unblockConfirmSection = document.getElementById('unblockConfirmSection');
    const blockForm = document.getElementById('blockForm');
    
    modalTitle.textContent = 'Block Dates';
    submitBtn.textContent = 'Block Dates';
    submitBtn.className = 'flex-1 bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg font-medium transition';
    
    blockDatesSection.classList.remove('hidden');
    unblockConfirmSection.classList.add('hidden');
    
    // Set required attributes
    document.querySelector('input[name="blockStartDate"]').required = true;
    document.querySelector('input[name="blockEndDate"]').required = true;
    document.querySelector('textarea[name="blockReason"]').required = true;
    
    blockForm.action = '{{ route("admin.units.block-dates") }}';
}

/**
 * Setup modal for unblock mode
 */
function setupUnblockMode() {
    const modalTitle = document.getElementById('modalTitle');
    const submitBtn = document.getElementById('submitBtn');
    const blockDatesSection = document.getElementById('blockDatesSection');
    const unblockConfirmSection = document.getElementById('unblockConfirmSection');
    const blockForm = document.getElementById('blockForm');
    
    modalTitle.textContent = 'Unblock Units';
    submitBtn.textContent = 'Unblock Units';
    submitBtn.className = 'flex-1 bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg font-medium transition';
    
    blockDatesSection.classList.add('hidden');
    unblockConfirmSection.classList.remove('hidden');
    
    // Remove required attributes
    document.querySelector('input[name="blockStartDate"]').required = false;
    document.querySelector('input[name="blockEndDate"]').required = false;
    document.querySelector('textarea[name="blockReason"]').required = false;
    
    blockForm.action = '{{ route("admin.units.unblock-dates") }}';
}

/**
 * Updates the list of selected units in the modal
 */
function updateSelectedUnitsList() {
    const selectedUnitsList = document.getElementById('selectedUnitsList');
    const selectedUnitsField = document.getElementById('selectedUnitsField');
    
    // Clear existing content
    selectedUnitsList.innerHTML = '';
    selectedUnitsField.innerHTML = '';
    
    selectedUnits.forEach(unitId => {
        addUnitToBlockList(unitId, selectedUnitsList, selectedUnitsField);
    });
}

/**
 * Adds a unit to the selected units list in block modal
 */
function addUnitToBlockList(unitId, listContainer, fieldContainer) {
    const unitCard = document.querySelector(`.unit-card[data-unit-id="${unitId}"]`);
    
    if (!unitCard) {
        console.warn(`Unit card not found for ID: ${unitId}`);
        return;
    }
    
    const unitName = unitCard.querySelector('h3')?.textContent || 'Unknown Unit';
    const statusBadge = unitCard.querySelector('.px-3.py-1.text-xs.font-semibold.rounded-full');
    const status = statusBadge ? statusBadge.textContent.trim() : 'Unknown';
    
    // Create visual list item
    const listItem = document.createElement('div');
    listItem.className = 'text-sm text-gray-700 py-1 px-2 hover:bg-gray-100 rounded flex items-center justify-between';
    listItem.innerHTML = `
        <div>
            <i class="fas fa-home text-blue-500 mr-2"></i>
            <span>${unitName}</span>
        </div>
        <span class="text-xs ${status.toLowerCase() === 'blocked' ? 'text-red-600' : 'text-green-600'} font-medium">
            ${status}
        </span>
    `;
    listContainer.appendChild(listItem);
    
    // Create hidden input for form submission
    const hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = 'unitIDs[]';
    hiddenInput.value = unitId;
    fieldContainer.appendChild(hiddenInput);
}

/**
 * Closes the block dates modal and resets form
 */
function closeBlockModal() {
    const blockModal = document.getElementById('blockModal');
    const blockForm = document.getElementById('blockForm');
    
    blockModal.classList.add('hidden');
    
    // Reset form
    if (blockForm) {
        blockForm.reset();
    }
    
    // Clear selected units list
    document.getElementById('selectedUnitsList').innerHTML = '';
    document.getElementById('selectedUnitsField').innerHTML = '';
}

/**
 * Validates block dates form before submission
 */
function validateBlockForm(e) {
    const blockDatesSection = document.getElementById('blockDatesSection');
    
    // Only validate dates if in block mode
    if (!blockDatesSection.classList.contains('hidden')) {
        const startDate = document.querySelector('#blockModal input[name="blockStartDate"]').value;
        const endDate = document.querySelector('#blockModal input[name="blockEndDate"]').value;
        const reason = document.querySelector('#blockModal textarea[name="blockReason"]').value.trim();
        
        if (!startDate || !endDate) {
            alert('Please select both start and end dates.');
            e.preventDefault();
            return false;
        }
        
        if (new Date(startDate) > new Date(endDate)) {
            alert('End date must be after or equal to start date.');
            e.preventDefault();
            return false;
        }
        
        if (!reason) {
            alert('Please provide a reason for blocking.');
            e.preventDefault();
            return false;
        }
    }
    
    if (selectedUnits.size === 0) {
        alert('No units selected.');
        e.preventDefault();
        return false;
    }
    
    return true;
}

/**
 * Handles block form submission
 */
function handleBlockFormSubmit(e) {
    if (!validateBlockForm(e)) {
        return;
    }
    
    // Show loading state
    const submitBtn = e.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    
    const isUnblocking = !document.getElementById('blockDatesSection').classList.contains('hidden') === false;
    submitBtn.innerHTML = isUnblocking 
        ? '<i class="fas fa-spinner fa-spin mr-2"></i>Unblocking...'
        : '<i class="fas fa-spinner fa-spin mr-2"></i>Blocking...';
    
    console.log('Processing units:', Array.from(selectedUnits));
}

/**
 * Sets minimum date for block date inputs to today
 */
function initializeBlockDateInputs() {
    const today = new Date().toISOString().split('T')[0];
    const startDateInput = document.querySelector('#blockModal input[name="blockStartDate"]');
    const endDateInput = document.querySelector('#blockModal input[name="blockEndDate"]');
    
    if (startDateInput) {
        startDateInput.min = today;
    }
    
    if (endDateInput) {
        endDateInput.min = today;
    }
    
    // Update end date minimum when start date changes
    if (startDateInput && endDateInput) {
        startDateInput.addEventListener('change', function() {
            endDateInput.min = this.value || today;
            
            // Clear end date if it's before the new start date
            if (endDateInput.value && endDateInput.value < this.value) {
                endDateInput.value = '';
            }
        });
    }
}

// Initialize block modal when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    initializeBlockDateInputs();
    
    // Close modal when clicking outside
    const blockModal = document.getElementById('blockModal');
    if (blockModal) {
        blockModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeBlockModal();
            }
        });
    }
    
    // Add form validation on submit
    const blockForm = document.getElementById('blockForm');
    if (blockForm) {
        blockForm.addEventListener('submit', handleBlockFormSubmit);
    }
    
    // Handle ESC key to close modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const blockModal = document.getElementById('blockModal');
            if (blockModal && !blockModal.classList.contains('hidden')) {
                closeBlockModal();
            }
        }
    });
});
</script>
@endif