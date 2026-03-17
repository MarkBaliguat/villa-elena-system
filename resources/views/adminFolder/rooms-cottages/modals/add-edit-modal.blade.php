{{-- Add/Edit Modal --}}
@if(auth()->user()->role === 'manager')
<div id="unitModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto relative">
        {{-- Close Button --}}
        <button type="button" onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition">
            <i class="fas fa-times text-xl"></i>
        </button>
        
        <h3 id="modalTitle" class="text-xl font-bold mb-4 pr-8">Add Unit</h3>
        <form id="unitForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div id="methodField"></div>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="unitName" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select name="unitType" id="unitTypeSelect" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="toggleUnitTypeFields(this.value)">
                        <option value="room">Room</option>
                        <option value="cottage">Cottage</option>
                        <option value="special">Special Unit</option>
                    </select>
                </div>
                
                {{-- Special Event Field — visible only when unitType = 'special' --}}
                <div id="specialEventField" class="hidden">
                    <div class="flex items-center">
                        <input type="checkbox" name="for_special_events" id="for_special_events" value="1" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="for_special_events" class="ml-2 block text-sm text-gray-700">
                            For Special Events Only
                        </label>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Check this if this unit is reserved for special events and occasions</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" required rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Capacity</label>
                    <input type="number" name="capacity" required min="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit Price</label>
                    <input type="number" name="unitRatePrice" required step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Virtual Tour Panorama Field — visible only for room at cottage, hidden para sa special --}}
                <div id="virtualTourField">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-street-view mr-1"></i>Virtual Tour Panorama
                    </label>
                    <select name="virtualTourPanorama" id="virtualTourPanorama" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">No Virtual Tour</option>
                        <optgroup label="Accommodations - Cottages">
                            <option value="2joypavillionandevelyncottage.jpg">Evelyn Cottage</option>
                            <option value="4centro.jpg">Elsa, Edna, Emma, Pavillion 1 Cottage</option>
                            <option value="3tonicottageandihawan.jpg">Toni Cottage</option>
                            <option value="6arcelicottageandhardin.jpg">Arceli Cottage</option>
                            <option value="9estercottage.jpg">Ester Cottage</option>
                            <option value="10sunflowerhouse.jpg">Mini Cottage</option>
                            <option value="16backminicottages.jpg">Back Cottages</option>
                            <option value="villaelenafront.jpg">Villa Elena Front</option> 
                            <option value="31campsite4.jpg">Pavillion 2</option>
                        </optgroup>
                        {{-- <optgroup label="Special Units">
                            <option value="36sunflowerhousefunctionhall.jpg">Sunflower House Function Hall</option>
                            <option value="4centro.jpg">Resort FrontSide</option>
                            <option value="13backside.jpg">Resort Backside</option>
                            <option value="5frontpool.jpg">Front Pool</option>
                            <option value="14backpool.jpg">Back Pool</option>
                        </optgroup> --}}
                        <optgroup label="Sunflower House - Ground Floor Rooms">
                            <option value="37sunflowerhouseroom1ulit.jpg">Sunflower House Room 1</option>
                            <option value="39sunflowerhouseroom2.jpg">Sunflower House Room 2</option>
                            <option value="40sunflowerhouseroom3.jpg">Sunflower House Room 3</option>
                            <option value="41sunflowerhouseroom4ulit.jpg">Sunflower House Room 4</option>
                            <option value="42sunflowerhouseroom5.jpg">Sunflower House Room 5</option>
                        </optgroup>
                        <optgroup label="Sunflower House - 2nd Floor">
                            <option value="44sunflowerhouseroom6.jpg">Sunflower House Room 6</option>
                            <option value="45sunflowerhouseroom7.jpg">Sunflower House Room 7</option>
                            <option value="46sunflowerhouseroom8siguro.jpg">Sunflower House Room 8</option>
                            <option value="47sunflowerhouseroom9siguro.jpg">Sunflower House Room 9</option>
                            <option value="52room10.jpg">Sunflower House Room 10</option>
                            <option value="49sunflowerhouseroom11ulit.jpg">Sunflower House Room 11</option>
                        </optgroup>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Select which panorama to show for this unit's virtual tour</p>
                </div>

                {{-- Image Upload Section --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Images</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4">
                        <input type="file" name="images[]" multiple accept="image/*" class="w-full" id="imageInput">
                        <p class="text-sm text-gray-500 mt-2">Select multiple images (JPEG, PNG, JPG, GIF) - Max 5MB each</p>
                    </div>
                    
                    {{-- Image Preview --}}
                    <div id="imagePreview" class="mt-4 grid grid-cols-3 gap-4 hidden">
                        <h4 class="col-span-3 text-sm font-medium text-gray-700">Image Preview:</h4>
                    </div>
                    
                    {{-- Existing Images (for edit) --}}
                    <div id="existingImages" class="mt-4 hidden">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Current Images:</h4>
                        <div class="grid grid-cols-3 gap-4" id="existingImagesGrid">
                        </div>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="unitStatus" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="toggleBlockDates(this.value)">
                        <option value="available">Available</option>
                        <option value="blocked">Blocked</option>
                    </select>
                </div>
                
                <div id="blockDatesSection" class="hidden space-y-4 border-t pt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Block Start Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="blockStartDate" id="addEditBlockStartDate" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Block End Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="blockEndDate" id="addEditBlockEndDate" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Block Reason <span class="text-red-500">*</span>
                        </label>
                        <select name="blockReason" id="addEditBlockReason" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">Select a reason...</option>
                            <option value="Maintenance">Maintenance</option>
                            <option value="Repairs">Repairs</option>
                            <option value="Renovation">Renovation</option>
                            <option value="Deep Cleaning">Deep Cleaning</option>
                            <option value="Pest Control">Pest Control</option>
                            <option value="Equipment Issues">Equipment Issues</option>
                            <option value="Safety Inspection">Safety Inspection</option>
                            <option value="Owner Use">Owner Use</option>
                            <option value="Seasonal Closure">Seasonal Closure</option>
                            <option value="Special Event">Special Event</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closeModal()" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white py-2 rounded-lg font-medium transition">
                    Cancel
                </button>
                <button type="submit" id="submitUnitBtn" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-medium transition disabled:bg-gray-400 disabled:cursor-not-allowed">
                    <span id="submitUnitText">Save</span>
                    <span id="submitUnitSpinner" class="hidden">
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        Saving...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// ============================================
// ADD/EDIT MODAL FUNCTIONS WITH LOADING STATES
// ============================================

let currentEditingUnit = null;

/**
 * ✅ MAIN TOGGLE FUNCTION — controls both specialEventField at virtualTourField
 * room     → virtual tour: SHOW  | special event checkbox: HIDE
 * cottage  → virtual tour: SHOW  | special event checkbox: HIDE
 * special  → virtual tour: HIDE  | special event checkbox: SHOW
 */
function toggleUnitTypeFields(unitType) {
    const specialEventField = document.getElementById('specialEventField');
    const virtualTourField  = document.getElementById('virtualTourField');
    const virtualTourSelect = document.getElementById('virtualTourPanorama');

    if (unitType === 'special') {
        // SHOW special event checkbox
        specialEventField.classList.remove('hidden');

        // HIDE virtual tour at i-clear ang value
        virtualTourField.classList.add('hidden');
        virtualTourSelect.value = '';
    } else {
        // HIDE special event checkbox at i-uncheck
        specialEventField.classList.add('hidden');
        document.getElementById('for_special_events').checked = false;

        // SHOW virtual tour para sa room at cottage
        virtualTourField.classList.remove('hidden');
    }
}

/**
 * @deprecated — kept para sa backward compatibility, redirects to toggleUnitTypeFields
 */
function toggleSpecialEventField(unitType) {
    toggleUnitTypeFields(unitType);
}

/**
 * Opens the modal for adding a new unit
 */
function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Add Unit';
    document.getElementById('unitForm').action = "{{ route('admin.units.store') }}";
    document.getElementById('unitForm').method = 'POST';
    document.getElementById('methodField').innerHTML = '';
    document.getElementById('unitForm').reset();
    document.getElementById('blockDatesSection').classList.add('hidden');

    // ✅ Default state: room ang default value ng select, kaya show virtual tour, hide special event
    toggleUnitTypeFields('room');

    resetImageSections();
    resetSubmitButton();
    initializeBlockDateInputs();
    document.getElementById('unitModal').classList.remove('hidden');
}

/**
 * Opens the modal for editing an existing unit
 */
function openEditModal(unitId) {
    console.log('Opening edit modal for unit ID:', unitId);
    currentEditingUnit = unitId;
    
    const editUrl = "{{ route('admin.units.edit', ':id') }}".replace(':id', unitId);
    
    fetch(editUrl, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.status);
        }
        return response.json();
    })
    .then(unit => {
        console.log('Unit data received:', unit);
        
        document.getElementById('modalTitle').textContent = 'Edit Unit';
        
        const updateUrl = "{{ route('admin.units.update', ':id') }}".replace(':id', unitId);
        document.getElementById('unitForm').action = updateUrl;
        document.getElementById('unitForm').method = 'POST';
        document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        
        populateFormFields(unit);
        displayExistingImages(unit.images || []);

        // ✅ I-apply ang toggle base sa unitType ng na-load na unit
        toggleUnitTypeFields(unit.unitType || 'room');
        toggleBlockDates(unit.unitStatus || 'available');
        resetSubmitButton();
        initializeBlockDateInputs();
        
        document.getElementById('unitModal').classList.remove('hidden');
    })
    .catch(error => {
        console.error('Error fetching unit data:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error loading unit data. Please try again.',
            confirmButtonColor: '#3b82f6'
        });
    });
}

/**
 * Populates form fields with unit data
 */
function populateFormFields(unit) {
    document.querySelector('input[name="unitName"]').value                  = unit.unitName || '';
    document.querySelector('select[name="unitType"]').value                 = unit.unitType || 'room';
    document.querySelector('textarea[name="description"]').value            = unit.description || '';
    document.querySelector('input[name="capacity"]').value                  = unit.capacity || 1;
    document.querySelector('input[name="unitRatePrice"]').value             = unit.unitRatePrice || 0;
    document.querySelector('select[name="unitStatus"]').value               = unit.unitStatus || 'available';
    document.querySelector('input[name="for_special_events"]').checked      = unit.for_special_events || false;
    document.querySelector('input[name="blockStartDate"]').value            = unit.blockStartDate || '';
    document.querySelector('input[name="blockEndDate"]').value              = unit.blockEndDate || '';
    document.querySelector('select[name="blockReason"]').value              = unit.blockReason || '';
    document.querySelector('select[name="virtualTourPanorama"]').value      = unit.virtualTourPanorama || '';
}

/**
 * Displays existing images in the edit modal
 */
function displayExistingImages(images) {
    const existingImages     = document.getElementById('existingImages');
    const existingImagesGrid = document.getElementById('existingImagesGrid');
    
    if (images && images.length > 0) {
        existingImages.classList.remove('hidden');
        existingImagesGrid.innerHTML = '';
        images.forEach((image, index) => {
            const imgContainer = createExistingImageContainer(image, index);
            existingImagesGrid.appendChild(imgContainer);
        });
    } else {
        existingImages.classList.add('hidden');
    }
}

/**
 * Creates an image container for existing images
 */
function createExistingImageContainer(image, index) {
    const imgContainer = document.createElement('div');
    imgContainer.className = 'relative';
    
    const img       = document.createElement('img');
    img.src         = '/storage/' + image;
    img.className   = 'w-full h-24 object-cover rounded-lg';
    img.alt         = 'Unit image ' + (index + 1);
    img.onerror     = function() {
        this.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgZmlsbD0iI2YzZjRmNiIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBkeT0iMC4zNWVtIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmb250LXNpemU9IjEyIiBmaWxsPSIjOTk5Ij5ObyBJbWFnZTwvdGV4dD48L3N2Zz4=';
    };
    
    const removeBtn = createImageDeleteButton(image, imgContainer);
    imgContainer.appendChild(img);
    imgContainer.appendChild(removeBtn);
    return imgContainer;
}

/**
 * Creates delete button for existing images
 */
function createImageDeleteButton(image, imgContainer) {
    const removeBtn       = document.createElement('button');
    removeBtn.type        = 'button';
    removeBtn.className   = 'absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600 transition';
    removeBtn.innerHTML   = '×';
    removeBtn.title       = 'Delete image';
    removeBtn.onclick     = function() {
        handleImageDelete(image, imgContainer);
    };
    return removeBtn;
}

/**
 * Handles image deletion
 */
function handleImageDelete(image, imgContainer) {
    Swal.fire({
        icon: 'warning',
        title: 'Delete Image?',
        text: 'Are you sure you want to delete this image?',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const deleteUrl = `/admin/units/${currentEditingUnit}/delete-image`;
            
            fetch(deleteUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ image_path: image })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    imgContainer.remove();
                    
                    const existingImagesGrid = document.getElementById('existingImagesGrid');
                    if (existingImagesGrid.children.length === 0) {
                        document.getElementById('existingImages').classList.add('hidden');
                    }
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'Image has been deleted.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Error deleting image', confirmButtonColor: '#3b82f6' });
                }
            })
            .catch(error => {
                console.error('Error deleting image:', error);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Error deleting image', confirmButtonColor: '#3b82f6' });
            });
        }
    });
}

/**
 * Sets up image preview for new uploads
 */
function setupImagePreview() {
    const imageInput   = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');
    
    if (!imageInput) return;
    
    imageInput.addEventListener('change', function(e) {
        imagePreview.innerHTML = '<h4 class="col-span-3 text-sm font-medium text-gray-700">Image Preview:</h4>';
        imagePreview.classList.remove('hidden');
        
        const files = e.target.files;
        if (files.length === 0) {
            imagePreview.classList.add('hidden');
            return;
        }
        
        Array.from(files).forEach((file, index) => {
            if (!validateImageFile(file)) return;
            
            const reader    = new FileReader();
            reader.onload   = function(e) {
                const previewContainer = createPreviewContainer(e.target.result, index, file.name);
                imagePreview.appendChild(previewContainer);
            };
            reader.readAsDataURL(file);
        });
    });
}

/**
 * Validates image file
 */
function validateImageFile(file) {
    if (file.size > 5 * 1024 * 1024) {
        Swal.fire({ icon: 'warning', title: 'File Too Large', text: `File ${file.name} is too large. Maximum size is 5MB.`, confirmButtonColor: '#3b82f6' });
        return false;
    }
    if (!file.type.match('image.*')) {
        Swal.fire({ icon: 'warning', title: 'Invalid File Type', text: `File ${file.name} is not an image.`, confirmButtonColor: '#3b82f6' });
        return false;
    }
    return true;
}

/**
 * Creates preview container for new image
 */
function createPreviewContainer(src, index, fileName) {
    const imgContainer    = document.createElement('div');
    imgContainer.className = 'relative';
    imgContainer.dataset.index = index;
    
    const img     = document.createElement('img');
    img.src       = src;
    img.className = 'w-full h-24 object-cover rounded-lg';
    img.alt       = 'Preview ' + fileName;
    
    const removeBtn     = document.createElement('button');
    removeBtn.type      = 'button';
    removeBtn.className = 'absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600 transition';
    removeBtn.innerHTML = '×';
    removeBtn.title     = 'Remove image';
    removeBtn.onclick   = function() {
        imgContainer.remove();
        const imagePreview = document.getElementById('imagePreview');
        if (imagePreview.children.length <= 1) {
            imagePreview.classList.add('hidden');
        }
    };
    
    imgContainer.appendChild(img);
    imgContainer.appendChild(removeBtn);
    return imgContainer;
}

/**
 * Toggles block dates section visibility
 */
function toggleBlockDates(status) {
    const blockDatesSection = document.getElementById('blockDatesSection');
    const blockStartDate    = document.getElementById('addEditBlockStartDate');
    const blockEndDate      = document.getElementById('addEditBlockEndDate');
    const blockReason       = document.getElementById('addEditBlockReason');
    
    if (status === 'blocked') {
        blockDatesSection.classList.remove('hidden');
        blockStartDate.required = true;
        blockEndDate.required   = true;
        blockReason.required    = true;
    } else {
        blockDatesSection.classList.add('hidden');
        blockStartDate.required = false;
        blockEndDate.required   = false;
        blockReason.required    = false;
        blockStartDate.value    = '';
        blockEndDate.value      = '';
        blockReason.value       = '';
    }
}

/**
 * Resets all image sections
 */
function resetImageSections() {
    document.getElementById('imagePreview').classList.add('hidden');
    document.getElementById('imagePreview').innerHTML = '<h4 class="col-span-3 text-sm font-medium text-gray-700">Image Preview:</h4>';
    document.getElementById('existingImages').classList.add('hidden');
    document.getElementById('existingImagesGrid').innerHTML = '';
    document.getElementById('imageInput').value = '';
}

/**
 * Shows loading state on submit button
 */
function showSubmitLoading() {
    const submitBtn    = document.getElementById('submitUnitBtn');
    const submitText   = document.getElementById('submitUnitText');
    const submitSpinner = document.getElementById('submitUnitSpinner');
    
    if (submitBtn && submitText && submitSpinner) {
        submitBtn.disabled = true;
        submitText.classList.add('hidden');
        submitSpinner.classList.remove('hidden');
    }
}

/**
 * Hides loading state on submit button
 */
function hideSubmitLoading() {
    const submitBtn    = document.getElementById('submitUnitBtn');
    const submitText   = document.getElementById('submitUnitText');
    const submitSpinner = document.getElementById('submitUnitSpinner');
    
    if (submitBtn && submitText && submitSpinner) {
        submitBtn.disabled = false;
        submitText.classList.remove('hidden');
        submitSpinner.classList.add('hidden');
    }
}

/**
 * Resets the submit button to its original state
 */
function resetSubmitButton() {
    hideSubmitLoading();
}

/**
 * Closes the unit modal
 */
function closeModal() {
    document.getElementById('unitModal').classList.add('hidden');
    resetImageSections();
    resetSubmitButton();
    currentEditingUnit = null;
}

/**
 * Sets minimum date for block date inputs to today
 */
function initializeBlockDateInputs() {
    const today        = new Date().toISOString().split('T')[0];
    const startDateInput = document.getElementById('addEditBlockStartDate');
    const endDateInput   = document.getElementById('addEditBlockEndDate');
    
    if (startDateInput) startDateInput.min = today;
    if (endDateInput)   endDateInput.min   = today;
    
    if (startDateInput && endDateInput) {
        startDateInput.addEventListener('change', function() {
            endDateInput.min = this.value || today;
            if (endDateInput.value && endDateInput.value < this.value) {
                endDateInput.value = '';
            }
        });
    }
}

/**
 * Validates the form before submission
 */
function validateUnitForm(e) {
    const status = document.querySelector('select[name="unitStatus"]').value;
    
    if (status === 'blocked') {
        const startDate = document.getElementById('addEditBlockStartDate').value;
        const endDate   = document.getElementById('addEditBlockEndDate').value;
        const reason    = document.getElementById('addEditBlockReason').value;
        
        if (!startDate || !endDate || !reason) {
            return true; // Let HTML5 validation handle this
        }
        
        if (new Date(endDate) < new Date(startDate)) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Invalid Date Range',
                text: 'End date must be after or equal to start date.',
                confirmButtonColor: '#3b82f6'
            });
            return false;
        }
    }
    
    showSubmitLoading();
    return true;
}

/**
 * Handles form submission with loading state
 */
function handleFormSubmit(event) {
    return validateUnitForm(event);
}

// ============================================
// INITIALIZE ON DOM READY
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    setupImagePreview();
    initializeBlockDateInputs();
    
    const unitForm = document.getElementById('unitForm');
    if (unitForm) {
        unitForm.addEventListener('submit', handleFormSubmit);
    }
    
    // Close modal on backdrop click
    document.getElementById('unitModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });
    
    // Status change listener
    const statusSelect = document.querySelector('#unitModal select[name="unitStatus"]');
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            toggleBlockDates(this.value);
        });
    }
    
    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const unitModal = document.getElementById('unitModal');
            if (unitModal && !unitModal.classList.contains('hidden')) closeModal();
        }
    });

    // ✅ Set default state on page load (room ang default, so show virtual tour)
    toggleUnitTypeFields(document.getElementById('unitTypeSelect')?.value || 'room');
});
</script>
@endif