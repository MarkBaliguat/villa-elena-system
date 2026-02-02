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
                    <select name="unitType" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="toggleSpecialEventField(this.value)">
                        <option value="room">Room</option>
                        <option value="cottage">Cottage</option>
                        <option value="special">Special Unit</option>
                    </select>
                </div>
                
                {{-- Special Event Field --}}
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

                {{-- Virtual Tour Panorama Field --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-street-view mr-1"></i>Virtual Tour Panorama
                    </label>
                    <select name="virtualTourPanorama" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">No Virtual Tour</option>
                        <optgroup label="Main Resort Areas">
                            <option value="4centro.jpg">Centro - Main Plaza</option>
                            <option value="1lobbyandfountain.jpg">Lobby & Fountain Entrance</option>
                            <option value="2joypavillionandevelyncottage.jpg">Joy Pavilion Area</option>
                        </optgroup>
                        <optgroup label="Swimming Pools">
                            <option value="5frontpool.jpg">Main Front Pool</option>
                            <option value="8poolviewsadaan~2.jpg">Pool View Sadaan</option>
                            <option value="14backpool.jpg">Back Pool Relaxation</option>
                            <option value="15backpoolview.jpg">Back Pool View</option>
                            <option value="17poolviewsadaanan.jpg">Pool View Sadaanan</option>
                        </optgroup>
                        <optgroup label="Accommodations - Cottages">
                            <option value="3tonicottageandihawan.jpg">Toni Cottage</option>
                            <option value="6arcelicottageandhardin.jpg">Arceli Cottage</option>
                            <option value="9estercottage.jpg">Ester Cottage</option>
                            <option value="16backminicottages.jpg">Back Mini Cottages</option>
                            <option value="villaelenafront.jpg">Villa Elena Front</option>
                            <option value="villaelenaparking.jpg">Villa Elena Parking</option>
                        </optgroup>
                        <optgroup label="Nature & Gardens">
                            <option value="7hardin.jpg">Hardin - Garden Area</option>
                            <option value="11backresortandcr.jpg">Back Resort Nature Trail</option>
                            <option value="13backside.jpg">Backside Scenic View</option>
                            <option value="22papaya.jpg">Papaya Garden</option>
                        </optgroup>
                        <optgroup label="Sunflower House - Ground Floor">
                            <option value="10sunflowerhouse.jpg">Sunflower House</option>
                            <option value="12sunflowerhouseandwellnesscenter.jpg">Sunflower Wellness Center</option>
                            <option value="33sunflowerhousesala.jpg">Sunflower House Sala</option>
                            <option value="34sunflowerhousecenter.jpg">Sunflower House Center</option>
                            <option value="35sunflowerhousekusina.jpg">Sunflower House Kitchen</option>
                            <option value="36sunflowerhousefunctionhall.jpg">Sunflower House Function Hall</option>
                            <option value="51wellnesscenter.jpg">Wellness Center</option>
                        </optgroup>
                        <optgroup label="Sunflower House - Ground Floor Rooms">
                            <option value="37sunflowerhouseroom1ulit.jpg">Sunflower House Room 1</option>
                            <option value="38sunflowerhouselobbypapuntangroom2.jpg">Sunflower House Lobby</option>
                            <option value="39sunflowerhouseroom2.jpg">Sunflower House Room 2</option>
                            <option value="40sunflowerhouseroom3.jpg">Sunflower House Room 3</option>
                            <option value="41sunflowerhouseroom4ulit.jpg">Sunflower House Room 4</option>
                            <option value="42sunflowerhouseroom5.jpg">Sunflower House Room 5</option>
                        </optgroup>
                        <optgroup label="Sunflower House - 2nd Floor">
                            <option value="43sunflowerhouselobby2ndfloor.jpg">Sunflower House 2nd Floor Lobby</option>
                            <option value="44sunflowerhouseroom6.jpg">Sunflower House Room 6</option>
                            <option value="45sunflowerhouseroom7.jpg">Sunflower House Room 7</option>
                            <option value="46sunflowerhouseroom8siguro.jpg">Sunflower House Room 8</option>
                            <option value="47sunflowerhouseroom9siguro.jpg">Sunflower House Room 9</option>
                            <option value="48sunflowerhouselobby2ndfloorpart2retake.jpg">Sunflower House 2nd Floor Part 2</option>
                            <option value="49sunflowerhouseroom11ulit.jpg">Sunflower House Room 11</option>
                            <option value="50sunflowerhousebalconyview.jpg">Sunflower House Balcony View</option>
                            <option value="52room10.jpg">Room 10</option>
                        </optgroup>
                        <optgroup label="Agri-Tourism Farm">
                            <option value="18farmentrance.jpg">Farm Entrance</option>
                            <option value="19farmentranceotherside.jpg">Farm Entrance Other Side</option>
                            <option value="20entranceandjoybridge.jpg">Joy Bridge Entrance</option>
                            <option value="20othersidepajoybridge.jpg">Joy Bridge Other Side</option>
                            <option value="21bridge.jpg">Bridge</option>
                            <option value="21bridgeatsunflowerfarmentrance.jpg">Bridge at Sunflower Farm</option>
                            <option value="23entrancesasunflower.jpg">Sunflower Farm Entrance</option>
                            <option value="24sunflowerfarmcenter.jpg">Farm Center</option>
                            <option value="25sunflowertocampsite.jpg">Sunflower to Campsite</option>
                            <option value="26papuntangduloatcampsite.jpg">To Duloat Campsite</option>
                        </optgroup>
                        <optgroup label="Campsite">
                            <option value="27duloatcampsiteentrance.jpg">Campsite Area</option>
                            <option value="28campsite1.jpg">Campsite 1</option>
                            <option value="29campsite2.jpg">Campsite 2</option>
                            <option value="30campsite3.jpg">Campsite 3</option>
                            <option value="31campsite4.jpg">Campsite 4</option>
                            <option value="32pavillion2.jpg">Pavilion 2</option>
                        </optgroup>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Select which panorama to show for this unit's virtual tour</p>
                </div>

                {{-- Image Upload Section --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Images</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4">
                        <input type="file" name="images[]" multiple accept="image/*" class="w-full" id="imageInput">
                        <p class="text-sm text-gray-500 mt-2">Select multiple images (JPEG, PNG, JPG, GIF) - Max 2MB each</p>
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Block Start Date</label>
                        <input type="date" name="blockStartDate" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Block End Date</label>
                        <input type="date" name="blockEndDate" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Block Reason</label>
                        <textarea name="blockReason" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Reason for blocking..."></textarea>
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
 * Toggles special event field based on unit type
 */
function toggleSpecialEventField(unitType) {
    const specialEventField = document.getElementById('specialEventField');
    if (unitType === 'special') {
        specialEventField.classList.remove('hidden');
    } else {
        specialEventField.classList.add('hidden');
        document.getElementById('for_special_events').checked = false;
    }
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
    document.getElementById('specialEventField').classList.add('hidden');
    resetImageSections();
    resetSubmitButton();
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
        toggleSpecialEventField(unit.unitType || 'room');
        toggleBlockDates(unit.unitStatus || 'available');
        resetSubmitButton();
        
        document.getElementById('unitModal').classList.remove('hidden');
    })
    .catch(error => {
        console.error('Error fetching unit data:', error);
        alert('Error loading unit data. Please try again.');
    });
}

/**
 * Populates form fields with unit data
 */
function populateFormFields(unit) {
    document.querySelector('input[name="unitName"]').value = unit.unitName || '';
    document.querySelector('select[name="unitType"]').value = unit.unitType || 'room';
    document.querySelector('textarea[name="description"]').value = unit.description || '';
    document.querySelector('input[name="capacity"]').value = unit.capacity || 1;
    document.querySelector('input[name="unitRatePrice"]').value = unit.unitRatePrice || 0;
    document.querySelector('select[name="unitStatus"]').value = unit.unitStatus || 'available';
    document.querySelector('input[name="for_special_events"]').checked = unit.for_special_events || false;
    document.querySelector('input[name="blockStartDate"]').value = unit.blockStartDate || '';
    document.querySelector('input[name="blockEndDate"]').value = unit.blockEndDate || '';
    document.querySelector('textarea[name="blockReason"]').value = unit.blockReason || '';
    document.querySelector('select[name="virtualTourPanorama"]').value = unit.virtualTourPanorama || '';
}

/**
 * Displays existing images in the edit modal
 */
function displayExistingImages(images) {
    const existingImages = document.getElementById('existingImages');
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
    
    const img = document.createElement('img');
    img.src = '/storage/' + image;
    img.className = 'w-full h-24 object-cover rounded-lg';
    img.alt = 'Unit image ' + (index + 1);
    img.onerror = function() {
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
    const removeBtn = document.createElement('button');
    removeBtn.type = 'button';
    removeBtn.className = 'absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600 transition';
    removeBtn.innerHTML = '×';
    removeBtn.title = 'Delete image';
    removeBtn.onclick = function() {
        handleImageDelete(image, imgContainer);
    };
    
    return removeBtn;
}

/**
 * Handles image deletion
 */
function handleImageDelete(image, imgContainer) {
    if (!confirm('Are you sure you want to delete this image?')) {
        return;
    }
    
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
        } else {
            alert('Error deleting image');
        }
    })
    .catch(error => {
        console.error('Error deleting image:', error);
        alert('Error deleting image');
    });
}

/**
 * Sets up image preview for new uploads
 */
function setupImagePreview() {
    const imageInput = document.getElementById('imageInput');
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
            if (!validateImageFile(file)) {
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
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
    if (file.size > 2 * 1024 * 1024) {
        alert('File ' + file.name + ' is too large. Maximum size is 2MB.');
        return false;
    }
    
    if (!file.type.match('image.*')) {
        alert('File ' + file.name + ' is not an image.');
        return false;
    }
    
    return true;
}

/**
 * Creates preview container for new image
 */
function createPreviewContainer(src, index, fileName) {
    const imgContainer = document.createElement('div');
    imgContainer.className = 'relative';
    imgContainer.dataset.index = index;
    
    const img = document.createElement('img');
    img.src = src;
    img.className = 'w-full h-24 object-cover rounded-lg';
    img.alt = 'Preview ' + fileName;
    
    const removeBtn = document.createElement('button');
    removeBtn.type = 'button';
    removeBtn.className = 'absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600 transition';
    removeBtn.innerHTML = '×';
    removeBtn.title = 'Remove image';
    removeBtn.onclick = function() {
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
    if (status === 'blocked') {
        blockDatesSection.classList.remove('hidden');
    } else {
        blockDatesSection.classList.add('hidden');
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
    const submitBtn = document.getElementById('submitUnitBtn');
    const submitText = document.getElementById('submitUnitText');
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
    const submitBtn = document.getElementById('submitUnitBtn');
    const submitText = document.getElementById('submitUnitText');
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
 * Handles form submission with loading state
 */
function handleFormSubmit(event) {
    showSubmitLoading();
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    setupImagePreview();
    
    const unitForm = document.getElementById('unitForm');
    if (unitForm) {
        unitForm.addEventListener('submit', handleFormSubmit);
    }
    
    document.getElementById('unitModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
    
    const statusSelect = document.querySelector('#unitModal select[name="unitStatus"]');
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            toggleBlockDates(this.value);
        });
    }
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const unitModal = document.getElementById('unitModal');
            if (unitModal && !unitModal.classList.contains('hidden')) {
                closeModal();
            }
        }
    });
});
</script>
@endif