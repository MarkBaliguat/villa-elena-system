<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/sunflower1.png') }}">
    <title>Rooms & Cottages - Villa Elena</title>
    <style>
        .selected-unit {
            border: 2px solid #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        .checkbox-container {
            position: absolute;
            top: 12px;
            left: 12px;
            z-index: 10;
        }
        .loading-spinner {
            display: none;
        }
        .search-loading .loading-spinner {
            display: inline-block;
        }
        .search-loading .search-icon {
            display: none;
        }
        .pagination .page-item {
            display: inline-block;
            margin: 0 2px;
        }
        .pagination .page-link {
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            color: #374151;
            text-decoration: none;
        }
        .pagination .page-item.active .page-link {
            background-color: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }
        .pagination .page-item.disabled .page-link {
            color: #9ca3af;
            cursor: not-allowed;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex">
        {{-- Sidebar --}}
        @include('adminFolder.partials.sidebar')
        @include('adminFolder.rooms-cottages.modals.add-edit-modal')
        @include('adminFolder.rooms-cottages.modals.block-modal')
        
        {{-- Main Content --}}
        <div class="flex-1 ml-64 p-8">
            {{-- Header Section --}}
            <div class="mb-6">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Rooms & Cottages</h1>
                        <p class="text-gray-500 text-sm mt-1">Manage your rooms, cottages, and special units</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-600">{{ now()->format('l, F j, Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- Success Message --}}
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Action Buttons and Filters --}}
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">Rooms & Cottages</h2>
                    <div class="flex gap-3">
                        <button id="blockDatesBtn" onclick="openBlockModal()" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-lg font-medium flex items-center gap-2 transition disabled:bg-red-400 disabled:cursor-not-allowed" disabled>
                            <i class="far fa-calendar-times"></i>
                            Block Units
                        </button>
                        @if(auth()->user()->role === 'manager')
                        <button onclick="openAddModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium flex items-center gap-2 transition">
                            <i class="fas fa-plus"></i>
                            Add Unit
                        </button>
                        @endif
                    </div>
                </div>

                {{-- Search and Filter --}}
                <form method="GET" action="{{ route('admin.rooms-cottages') }}" id="searchForm">
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex gap-4">
                            <div class="relative">
                                <i class="fas fa-search search-icon absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input 
                                    type="text" 
                                    name="search"
                                    id="searchInput"
                                    placeholder="Search rooms, cottages, or special units..." 
                                    value="{{ request('search') }}"
                                    class="pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-64"
                                >
                            </div>
                            <select name="status" id="statusFilter" class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                                <option value="">All Status</option>
                                <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>

                                <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>Blocked</option>
                            </select>
                            <select name="type" id="typeFilter" class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                                <option value="">All Types</option>
                                <option value="room" {{ request('type') == 'room' ? 'selected' : '' }}>Rooms</option>
                                <option value="cottage" {{ request('type') == 'cottage' ? 'selected' : '' }}>Cottages</option>
                                <option value="special" {{ request('type') == 'special' ? 'selected' : '' }}>Special Units</option>
                            </select>
                            <button type="button" onclick="clearFilters()" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2.5 rounded-lg font-medium">
                                Clear
                            </button>
                        </div>
                    </div>
                </form>

                {{-- Selection Info --}}
                <div id="selectionInfo" class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4 hidden">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="font-medium text-blue-800" id="selectedCount">0</span>
                            <span class="text-blue-700"> units selected</span>
                        </div>
                        <button onclick="clearSelection()" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Clear selection
                        </button>
                    </div>
                </div>

                {{-- Units Container --}}
                <div id="unitsContainer">
                    {{-- Units Grid --}}
                    <div id="unitsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                        @foreach($units as $unit)
                        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition relative unit-card" data-unit-id="{{ $unit->unitID }}">
                            @if(auth()->user()->role === 'manager')
                            <div class="checkbox-container">
                                <input type="checkbox" class="unit-checkbox hidden h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500" value="{{ $unit->unitID }}">
                            </div>
                            @endif
                            <div class="relative">
                                <div class="h-48 bg-gray-200">
                                    @if($unit->first_image_url)
                                        <img src="{{ $unit->first_image_url }}" alt="{{ $unit->unitName }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                                            <i class="fas fa-camera text-4xl mb-2"></i>
                                            <span class="text-sm">No Image Available</span>
                                        </div>
                                    @endif
                                </div>
                                @if($unit->unitType == 'special')
                                <div class="absolute top-2 right-2">
                                    <span class="bg-purple-500 text-white px-2 py-1 rounded-full text-xs font-semibold">
                                        Special
                                        @if($unit->for_special_events)
                                        <i class="fas fa-star ml-1"></i>
                                        @endif
                                    </span>
                                </div>
                                @endif
                            </div>
                            <div class="p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="text-lg font-bold text-gray-800">{{ $unit->unitName }}</h3>
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                        {{ $unit->unitStatus == 'available' ? 'bg-green-100 text-green-700' : '' }}

                                        {{ $unit->unitStatus == 'blocked' ? 'bg-red-100 text-red-700' : '' }}">
                                        {{ ucfirst($unit->unitStatus) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mb-3">{{ Str::limit($unit->description, 80) }}</p>
                                <div class="space-y-1 mb-4">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-users w-5"></i>
                                        <span>Up to {{ $unit->capacity }} Guest{{ $unit->capacity > 1 ? 's' : '' }}</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-800 font-semibold">
                                        <i class="fas fa-peso-sign w-5"></i>
                                        <span>₱{{ number_format($unit->unitRatePrice, 2) }}</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-home w-5"></i>
                                        <span class="capitalize">
                                            @if($unit->unitType == 'special')
                                                Special Unit
                                                @if($unit->for_special_events)
                                                    <span class="text-purple-600 ml-1">(For Events)</span>
                                                @endif
                                            @else
                                                {{ $unit->unitType }}
                                            @endif
                                        </span>
                                    </div>
                                    @if($unit->blockStartDate && $unit->blockEndDate)
                                    <div class="flex items-center text-sm text-red-600">
                                        <i class="fas fa-calendar-times w-5"></i>
                                        <span>Blocked: {{ \Carbon\Carbon::parse($unit->blockStartDate)->format('M j') }} - {{ \Carbon\Carbon::parse($unit->blockEndDate)->format('M j, Y') }}</span>
                                    </div>
                                    @endif
                                </div>
                                @if(auth()->user()->role === 'manager')
                                <div class="flex gap-2">
                                    <button class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-medium transition edit-btn" data-unit-id="{{ $unit->unitID }}">
                                        Edit
                                    </button>
                                    <form action="{{ route('admin.units.destroy', $unit->unitID) }}" method="POST" class="flex-1" onsubmit="return confirm('Are you sure you want to delete this unit?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg font-medium transition">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                                @else
                                <div class="text-center text-gray-500 text-sm py-2">
                                    Read-only access
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Loading Indicator --}}
                    <div id="loadingIndicator" class="hidden text-center py-8">
                        <div class="inline-flex items-center">
                            <i class="fas fa-spinner fa-spin text-blue-500 text-xl mr-3"></i>
                            <span class="text-gray-600">Loading units...</span>
                        </div>
                    </div>

                    {{-- No Results Message --}}
                    <div id="noResults" class="hidden text-center py-12">
                        <div class="text-gray-500">
                            <i class="fas fa-search text-4xl mb-4"></i>
                            <p class="text-lg">No units found matching your criteria</p>
                            <p class="text-sm mt-2">Try adjusting your search or filters</p>
                        </div>
                    </div>

                    {{-- Pagination --}}
                    <div id="paginationSection" class="flex justify-between items-center">
                        <div class="text-sm text-gray-600">
                            Showing {{ $units->firstItem() }} to {{ $units->lastItem() }} of {{ $units->total() }} results
                        </div>
                        <div class="flex gap-2 pagination">
                            {{ $units->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ============================================
        // UNIT SELECTION FUNCTIONS
        // ============================================

        let selectedUnits = new Set();

        /**
         * Initializes unit selection functionality
         */
        function initializeUnitSelection() {
            selectedUnits.clear();
            updateSelection();
            
            document.querySelectorAll('.unit-card').forEach(card => {
                setupUnitCardSelection(card);
            });
        }

        /**
         * Sets up selection for a unit card
         * @param {HTMLElement} card - Unit card element
         */
        function setupUnitCardSelection(card) {
            const checkbox = card.querySelector('.unit-checkbox');
            
            // Clone to remove old listeners
            const newCard = card.cloneNode(true);
            card.parentNode.replaceChild(newCard, card);
            
            const updatedCheckbox = newCard.querySelector('.unit-checkbox');
            
            // Card click handler
            newCard.addEventListener('click', function(e) {
                // Don't trigger if clicking buttons, forms, or links
                if (e.target.closest('button') || e.target.closest('form') || e.target.closest('a')) {
                    return;
                }
                
                const unitId = this.getAttribute('data-unit-id');
                
                if (selectedUnits.has(unitId)) {
                    deselectUnit(unitId, this, updatedCheckbox);
                } else {
                    selectUnit(unitId, this, updatedCheckbox);
                }
                
                updateSelection();
            });
            
            // Checkbox click handler
            if (updatedCheckbox) {
                updatedCheckbox.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const unitId = newCard.getAttribute('data-unit-id');
                    
                    if (this.checked) {
                        selectUnit(unitId, newCard, this);
                    } else {
                        deselectUnit(unitId, newCard, this);
                    }
                    
                    updateSelection();
                });
            }
        }

        /**
         * Selects a unit
         */
        function selectUnit(unitId, card, checkbox) {
            selectedUnits.add(unitId);
            card.classList.add('selected-unit');
            
            if (checkbox) {
                checkbox.checked = true;
                checkbox.classList.remove('hidden');
            }
        }

        /**
         * Deselects a unit
         */
        function deselectUnit(unitId, card, checkbox) {
            selectedUnits.delete(unitId);
            card.classList.remove('selected-unit');
            
            if (checkbox) {
                checkbox.checked = false;
                checkbox.classList.add('hidden');
            }
        }

        /**
         * Updates selection info and button states
         */
        function updateSelection() {
            const selectedCount = selectedUnits.size;
            const selectionInfo = document.getElementById('selectionInfo');
            const blockDatesBtn = document.getElementById('blockDatesBtn');
            const selectedCountElement = document.getElementById('selectedCount');
            
            if (selectedCountElement) {
                selectedCountElement.textContent = selectedCount;
            }
            
            if (selectionInfo) {
                if (selectedCount > 0) {
                    selectionInfo.classList.remove('hidden');
                } else {
                    selectionInfo.classList.add('hidden');
                }
            }
            
            if (blockDatesBtn) {
                blockDatesBtn.disabled = selectedCount === 0;
                
                // Check if all selected units are blocked
                if (selectedCount > 0) {
                    const allBlocked = checkIfAllSelectedUnitsAreBlocked();
                    
                    if (allBlocked) {
                        // All selected units are blocked - show "Unblock Units"
                        blockDatesBtn.innerHTML = '<i class="far fa-calendar-check"></i> Unblock Units';
                        blockDatesBtn.classList.remove('bg-red-600', 'hover:bg-red-700', 'disabled:bg-red-400');
                        blockDatesBtn.classList.add('bg-green-600', 'hover:bg-green-700', 'disabled:bg-green-400');
                    } else {
                        // Some or all units are not blocked - show "Block Units"
                        blockDatesBtn.innerHTML = '<i class="far fa-calendar-times"></i> Block Units';
                        blockDatesBtn.classList.remove('bg-green-600', 'hover:bg-green-700', 'disabled:bg-green-400');
                        blockDatesBtn.classList.add('bg-red-600', 'hover:bg-red-700', 'disabled:bg-red-400');
                    }
                }
            }
        }
        
        /**
         * Checks if all selected units are currently blocked
         * @returns {boolean}
         */
        function checkIfAllSelectedUnitsAreBlocked() {
            if (selectedUnits.size === 0) return false;
            
            let allBlocked = true;
            
            selectedUnits.forEach(unitId => {
                const card = document.querySelector(`.unit-card[data-unit-id="${unitId}"]`);
                if (card) {
                    const statusBadge = card.querySelector('.px-3.py-1.text-xs.font-semibold.rounded-full');
                    if (statusBadge) {
                        const statusText = statusBadge.textContent.trim().toLowerCase();
                        if (statusText !== 'blocked') {
                            allBlocked = false;
                        }
                    }
                }
            });
            
            return allBlocked;
        }

        /**
         * Clears all selections
         */
        function clearSelection() {
            selectedUnits.clear();
            
            document.querySelectorAll('.unit-checkbox').forEach(checkbox => {
                checkbox.checked = false;
                checkbox.classList.add('hidden');
            });
            
            document.querySelectorAll('.unit-card').forEach(card => {
                card.classList.remove('selected-unit');
            });
            
            updateSelection();
        }

        // ============================================
        // SEARCH AND FILTER FUNCTIONS
        // ============================================

        let searchTimeout;

        /**
         * Performs search with filters
         */
        function performSearch() {
            const searchForm = document.getElementById('searchForm');
            const loadingIndicator = document.getElementById('loadingIndicator');
            const unitsGrid = document.getElementById('unitsGrid');
            const paginationSection = document.getElementById('paginationSection');
            const noResults = document.getElementById('noResults');
            
            // Show loading
            if (loadingIndicator) loadingIndicator.classList.remove('hidden');
            if (unitsGrid) unitsGrid.classList.add('hidden');
            if (paginationSection) paginationSection.classList.add('hidden');
            if (noResults) noResults.classList.add('hidden');
            
            const searchContainer = document.querySelector('.relative');
            if (searchContainer) {
                searchContainer.classList.add('search-loading');
            }
            
            // Get form data
            const formData = new FormData(searchForm);
            const params = new URLSearchParams();
            
            for (let [key, value] of formData) {
                if (value) {
                    params.append(key, value);
                }
            }
            
            // AJAX search
            fetch(searchForm.action + '?' + params.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network error');
                }
                return response.text();
            })
            .then(html => {
                updateUnitsDisplay(html);
            })
            .catch(error => {
                console.error('Search error:', error);
                searchForm.submit();
            })
            .finally(() => {
                if (loadingIndicator) loadingIndicator.classList.add('hidden');
                if (searchContainer) searchContainer.classList.remove('search-loading');
            });
        }

        /**
         * Updates units display with new HTML
         */
        function updateUnitsDisplay(html) {
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = html;
            
            const newUnitsContainer = tempDiv.querySelector('#unitsContainer');
            
            if (newUnitsContainer) {
                const currentContainer = document.getElementById('unitsContainer');
                if (currentContainer) {
                    currentContainer.innerHTML = newUnitsContainer.innerHTML;
                }
                
                // Reinitialize
                initializeUnitSelection();
                
                // Check results
                const unitsGrid = document.getElementById('unitsGrid');
                const noResults = document.getElementById('noResults');
                
                if (unitsGrid && noResults) {
                    const hasResults = unitsGrid.children.length > 0;
                    
                    if (hasResults) {
                        noResults.classList.add('hidden');
                    } else {
                        noResults.classList.remove('hidden');
                    }
                }
            }
        }

        /**
         * Clears all filters
         */
        function clearFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('typeFilter').value = '';
            performSearch();
        }

        // ============================================
        // EDIT BUTTON HANDLER
        // ============================================

        /**
         * Handles edit button clicks using event delegation
         */
        function handleEditButtonClick(e) {
            const editBtn = e.target.closest('.edit-btn');
            if (editBtn) {
                e.preventDefault();
                e.stopPropagation();
                
                const unitId = editBtn.getAttribute('data-unit-id');
                console.log('Edit button clicked for unit:', unitId);
                
                if (unitId) {
                    openEditModal(unitId);
                } else {
                    console.error('No unit ID found');
                }
            }
        }

        // ============================================
        // INITIALIZATION
        // ============================================

        document.addEventListener('DOMContentLoaded', function() {
            // Use event delegation for edit buttons
            document.addEventListener('click', handleEditButtonClick);
            
            // Initialize selection
            initializeUnitSelection();
            
            // Search with debounce
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(performSearch, 500);
                });
            }
            
            // Filter changes
            const statusFilter = document.getElementById('statusFilter');
            if (statusFilter) {
                statusFilter.addEventListener('change', performSearch);
            }
            
            const typeFilter = document.getElementById('typeFilter');
            if (typeFilter) {
                typeFilter.addEventListener('change', performSearch);
            }
        });
    </script>

</body>
</html>