  {{-- Block Dates Modal --}}
    @if(auth()->user()->role === 'manager' || auth()->user()->role === 'staff')
    <div id="blockModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md relative">
            {{-- Close Button --}}
            <button type="button" onclick="closeBlockModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition">
                <i class="fas fa-times text-xl"></i>
            </button>
            
            <h3 class="text-xl font-bold mb-4 pr-8">Block Dates</h3>
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
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Block Start Date</label>
                        <input type="date" name="blockStartDate" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Block End Date</label>
                        <input type="date" name="blockEndDate" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Block Reason</label>
                        <textarea name="blockReason" required rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Reason for blocking..."></textarea>
                    </div>
                </div>
                
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeBlockModal()" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white py-2 rounded-lg font-medium transition">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg font-medium transition">
                        Block Dates
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
{{-- Block Dates Modal --}}
@if(auth()->user()->role === 'manager' || auth()->user()->role === 'staff')
<div id="blockModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md relative">
        {{-- Close Button --}}
        <button type="button" onclick="closeBlockModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition">
            <i class="fas fa-times text-xl"></i>
        </button>
        
        <h3 class="text-xl font-bold mb-4 pr-8">Block Dates</h3>
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
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Block Start Date</label>
                    <input type="date" name="blockStartDate" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Block End Date</label>
                    <input type="date" name="blockEndDate" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Block Reason</label>
                    <textarea name="blockReason" required rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Reason for blocking..."></textarea>
                </div>
            </div>
            
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closeBlockModal()" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white py-2 rounded-lg font-medium transition">
                    Cancel
                </button>
                <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg font-medium transition">
                    Block Dates
                </button>
            </div>
        </form>
    </div>
</div>

    <script>
    // ============================================
    // BLOCK DATES MODAL FUNCTIONS
    // ============================================

    /**
     * Opens the block dates modal with selected units
     */
    function openBlockModal() {
        if (selectedUnits.size === 0) {
            alert('Please select at least one unit to block.');
            return;
        }
        
        updateSelectedUnitsList();
        document.getElementById('blockModal').classList.remove('hidden');
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
     * @param {string} unitId - Unit ID
     * @param {HTMLElement} listContainer - Container for visual list
     * @param {HTMLElement} fieldContainer - Container for hidden inputs
     */
    function addUnitToBlockList(unitId, listContainer, fieldContainer) {
        const unitCard = document.querySelector(`.unit-card[data-unit-id="${unitId}"]`);
        
        if (!unitCard) {
            console.warn(`Unit card not found for ID: ${unitId}`);
            return;
        }
        
        const unitName = unitCard.querySelector('h3')?.textContent || 'Unknown Unit';
        
        // Create visual list item
        const listItem = document.createElement('div');
        listItem.className = 'text-sm text-gray-700 py-1 px-2 hover:bg-gray-100 rounded';
        listItem.innerHTML = `
            <i class="fas fa-home text-blue-500 mr-2"></i>
            <span>${unitName}</span>
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
     * @param {Event} e - Form submit event
     * @returns {boolean} True if valid, false otherwise
     */
    function validateBlockForm(e) {
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
        
        if (selectedUnits.size === 0) {
            alert('No units selected for blocking.');
            e.preventDefault();
            return false;
        }
        
        return true;
    }

    /**
     * Handles block form submission
     * @param {Event} e - Form submit event
     */
    function handleBlockFormSubmit(e) {
        if (!validateBlockForm(e)) {
            return;
        }
        
        // Show loading state
        const submitBtn = e.target.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Blocking...';
        
        console.log('Blocking units:', Array.from(selectedUnits));
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