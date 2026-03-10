<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Staff - Villa Elena Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/x-icon" href="{{ asset('images/sunflower1.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        #mainContent {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-left: 16rem;
            width: calc(100% - 16rem);
        }
        #mainContent.ml-24 { margin-left: 5.5rem; width: calc(100% - 5.5rem); }
        #mainContent.ml-64 { margin-left: 16rem; width: calc(100% - 16rem); }
        @media (max-width: 768px) {
            #mainContent { margin-left: 5.5rem !important; width: calc(100% - 5.5rem) !important; padding: 1rem !important; }
        }
        @media (max-width: 640px) { #mainContent { padding: 0.75rem !important; } }

        .image-upload-area {
            border: 2px dashed #d1d5db;
            border-radius: 0.75rem;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.2s;
            cursor: pointer;
            background: #f9fafb;
        }
        .image-upload-area:hover { border-color: #3b82f6; background: #eff6ff; }
        .image-upload-area.drag-over { border-color: #3b82f6; background: #eff6ff; }
        .avatar-preview {
            width: 96px; height: 96px;
            border-radius: 0.75rem;
            object-fit: cover;
            border: 3px solid #e5e7eb;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .avatar-placeholder {
            width: 96px; height: 96px;
            border-radius: 0.75rem;
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 1.5rem; font-weight: 700;
            border: 3px solid #e5e7eb;
        }
    </style>
</head>
<body class="bg-gray-50">
    @include('adminFolder.partials.sidebar')

    <div class="ml-64 p-8" id="mainContent">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 md:mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Edit Staff</h1>
                <p class="text-sm md:text-base text-gray-600 mt-1 md:mt-2">Update staff account information</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 md:p-6">
            <form action="{{ route('admin.staff.update', $staff->userID) }}" method="POST" id="editStaffForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="remove_image" id="removeImageFlag" value="0">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">

                    <!-- Personal Information -->
                    <div class="space-y-4">
                        <h3 class="text-base md:text-lg font-semibold text-gray-900 border-b pb-2">Personal Information</h3>
                        
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $staff->name) }}"
                                   class="w-full px-4 py-2.5 md:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base"
                                   required pattern="[a-zA-Z\s]+" title="Name should only contain letters and spaces">
                            @error('name')<p class="text-red-500 text-xs md:text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username *</label>
                            <input type="text" id="username" name="username" value="{{ old('username', $staff->username) }}"
                                   class="w-full px-4 py-2.5 md:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base"
                                   required pattern="[a-zA-Z0-9_]+" title="Username may only contain letters, numbers, and underscores">
                            @error('username')<p class="text-red-500 text-xs md:text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- Contact & Role -->
                    <div class="space-y-4">
                        <h3 class="text-base md:text-lg font-semibold text-gray-900 border-b pb-2">Contact & Role</h3>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $staff->email) }}"
                                   class="w-full px-4 py-2.5 md:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base"
                                   required>
                            @error('email')<p class="text-red-500 text-xs md:text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="phoneNumber" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input type="tel" id="phoneNumber" name="phoneNumber" value="{{ old('phoneNumber', $staff->phoneNumber) }}"
                                   class="w-full px-4 py-2.5 md:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base"
                                   maxlength="11" pattern="09[0-9]{9}"
                                   title="Please enter a valid 11-digit Philippine number starting with 09">
                            @error('phoneNumber')<p class="text-red-500 text-xs md:text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                            <select id="role" name="role"
                                    class="w-full px-4 py-2.5 md:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base"
                                    required>
                                <option value="">Select Role</option>
                                <option value="staff"   {{ old('role', $staff->role) == 'staff'   ? 'selected' : '' }}>Staff</option>
                                <option value="manager" {{ old('role', $staff->role) == 'manager' ? 'selected' : '' }}>Manager</option>
                            </select>
                            @error('role')<p class="text-red-500 text-xs md:text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- Profile Image -->
                    <div class="md:col-span-2 space-y-4">
                        <h3 class="text-base md:text-lg font-semibold text-gray-900 border-b pb-2">Profile Image</h3>
                        
                        <div class="flex flex-col sm:flex-row items-start gap-6">
                            <!-- Preview -->
                            <div class="flex-shrink-0 flex flex-col items-center gap-2">
                                @if($staff->profile_image)
                                    <img id="avatarPreview"
                                         src="{{ asset('storage/' . $staff->profile_image) }}"
                                         alt="Profile" class="avatar-preview">
                                    <div class="avatar-placeholder hidden" id="avatarPlaceholder">
                                        {{ strtoupper(substr($staff->name, 0, 2)) }}
                                    </div>
                                @else
                                    <img id="avatarPreview" src="#" alt="Preview" class="avatar-preview hidden">
                                    <div class="avatar-placeholder" id="avatarPlaceholder">
                                        {{ strtoupper(substr($staff->name, 0, 2)) }}
                                    </div>
                                @endif
                                <span class="text-xs text-gray-500">Preview</span>
                            </div>

                            <!-- Upload Controls -->
                            <div class="flex-1 w-full space-y-3">
                                <div class="image-upload-area" id="uploadArea" onclick="document.getElementById('profile_image').click()">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                    <p class="text-sm font-medium text-gray-700">Click to upload or drag & drop</p>
                                    <p class="text-xs text-gray-500 mt-1">JPG, PNG, GIF, WEBP — max 2MB</p>
                                </div>
                                <input type="file" id="profile_image" name="profile_image"
                                       accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                       class="hidden">

                                <div id="fileNameDisplay" class="hidden flex items-center gap-2 text-sm text-gray-600 bg-gray-50 px-3 py-2 rounded-lg border border-gray-200">
                                    <i class="fas fa-image text-blue-500"></i>
                                    <span id="fileName"></span>
                                    <button type="button" onclick="clearNewImage()" class="ml-auto text-red-500 hover:text-red-700">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>

                                @if($staff->profile_image)
                                <div id="removeCurrentContainer" class="flex items-center gap-3 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                                    <i class="fas fa-exclamation-triangle text-amber-500"></i>
                                    <span class="text-sm text-amber-700 flex-1">Current photo will be kept unless you upload a new one or remove it.</span>
                                    <button type="button" onclick="removeCurrentImage()"
                                            class="text-sm text-red-600 hover:text-red-800 font-medium flex items-center gap-1 whitespace-nowrap">
                                        <i class="fas fa-trash-alt"></i> Remove
                                    </button>
                                </div>
                                <div id="removedNotice" class="hidden flex items-center gap-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                                    <i class="fas fa-trash text-red-500"></i>
                                    <span class="text-sm text-red-700 flex-1">Current photo will be removed on save.</span>
                                    <button type="button" onclick="undoRemove()"
                                            class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                                        <i class="fas fa-undo"></i> Undo
                                    </button>
                                </div>
                                @endif

                                @error('profile_image')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Security -->
                    <div class="md:col-span-2 space-y-4">
                        <h3 class="text-base md:text-lg font-semibold text-gray-900 border-b pb-2">
                            Security <span class="text-gray-400 font-normal text-sm">(Leave blank to keep current password)</span>
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                                <input type="password" id="password" name="password"
                                       class="w-full px-4 py-2.5 md:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base"
                                       minlength="8">
                                @error('password')<p class="text-red-500 text-xs md:text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                       class="w-full px-4 py-2.5 md:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base"
                                       minlength="8">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row justify-end gap-3 md:gap-4 mt-6 md:mt-8 pt-4 md:pt-6 border-t">
                    <a href="{{ route('admin.staff.index') }}"
                       class="w-full sm:w-auto px-6 py-2.5 md:py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors text-center text-sm md:text-base">
                        Cancel
                    </a>
                    <button type="submit"
                            class="w-full sm:w-auto px-6 py-2.5 md:py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors flex items-center justify-center gap-2 text-sm md:text-base">
                        <i class="fas fa-save"></i>
                        Update Staff Account
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // ===== SIDEBAR =====
        window.addEventListener('sidebarToggled', (event) => {
            const mc = document.getElementById('mainContent');
            mc.classList.toggle('ml-64', !event.detail.collapsed);
            mc.classList.toggle('ml-24', event.detail.collapsed);
        });
        document.addEventListener('DOMContentLoaded', () => {
            const mc = document.getElementById('mainContent');
            if (window.innerWidth > 768 && localStorage.getItem('sidebarState') === 'collapsed') {
                mc.classList.replace('ml-64', 'ml-24');
            } else if (window.innerWidth <= 768) {
                mc.classList.replace('ml-64', 'ml-24');
            }
        });
        window.addEventListener('resize', () => {
            const mc = document.getElementById('mainContent');
            if (window.innerWidth > 768) {
                mc.classList.toggle('ml-24', localStorage.getItem('sidebarState') === 'collapsed');
                mc.classList.toggle('ml-64', localStorage.getItem('sidebarState') !== 'collapsed');
            } else {
                mc.classList.replace('ml-64', 'ml-24');
            }
        });

        // ===== PHONE =====
        document.getElementById('phoneNumber').addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '').slice(0, 11);
            if (this.value.length > 0 && !this.value.startsWith('09')) {
                this.value = '09' + this.value.slice(2);
            }
        });

        // ===== PASSWORD =====
        const passwordInput = document.getElementById('password');
        const confirmInput  = document.getElementById('password_confirmation');
        function validatePasswords() {
            if (passwordInput.value && confirmInput.value) {
                confirmInput.setCustomValidity(
                    passwordInput.value !== confirmInput.value ? 'Passwords do not match' : ''
                );
            } else {
                confirmInput.setCustomValidity('');
            }
        }
        passwordInput.addEventListener('input', validatePasswords);
        confirmInput.addEventListener('input', validatePasswords);

        // ===== IMAGE UPLOAD =====
        const profileInput      = document.getElementById('profile_image');
        const avatarPreview     = document.getElementById('avatarPreview');
        const avatarPlaceholder = document.getElementById('avatarPlaceholder');
        const fileNameDisplay   = document.getElementById('fileNameDisplay');
        const fileNameSpan      = document.getElementById('fileName');
        const uploadArea        = document.getElementById('uploadArea');
        const removeFlag        = document.getElementById('removeImageFlag');

        profileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;
            showNewPreview(file);
            // If user picks a new image, cancel any pending remove
            removeFlag.value = '0';
            const removedNotice = document.getElementById('removedNotice');
            const removeContainer = document.getElementById('removeCurrentContainer');
            if (removedNotice) removedNotice.classList.add('hidden');
            if (removeContainer) removeContainer.classList.remove('hidden');
        });

        function showNewPreview(file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                avatarPreview.src = e.target.result;
                avatarPreview.classList.remove('hidden');
                if (avatarPlaceholder) avatarPlaceholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
            fileNameSpan.textContent = file.name;
            fileNameDisplay.classList.remove('hidden');
            uploadArea.innerHTML = `<i class="fas fa-check-circle text-3xl text-green-500 mb-2"></i>
                <p class="text-sm font-medium text-green-700">New image selected</p>
                <p class="text-xs text-gray-500 mt-1">Click to change</p>`;
        }

        function clearNewImage() {
            profileInput.value = '';
            fileNameDisplay.classList.add('hidden');
            uploadArea.innerHTML = `<i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Click to upload or drag & drop</p>
                <p class="text-xs text-gray-500 mt-1">JPG, PNG, GIF, WEBP — max 2MB</p>`;
            // Restore original preview if exists
            @if($staff->profile_image)
                avatarPreview.src = "{{ asset('storage/' . $staff->profile_image) }}";
                avatarPreview.classList.remove('hidden');
                if (avatarPlaceholder) avatarPlaceholder.classList.add('hidden');
            @else
                avatarPreview.classList.add('hidden');
                if (avatarPlaceholder) avatarPlaceholder.classList.remove('hidden');
            @endif
        }

        function removeCurrentImage() {
            removeFlag.value = '1';
            avatarPreview.classList.add('hidden');
            if (avatarPlaceholder) avatarPlaceholder.classList.remove('hidden');
            const removedNotice   = document.getElementById('removedNotice');
            const removeContainer = document.getElementById('removeCurrentContainer');
            if (removeContainer) removeContainer.classList.add('hidden');
            if (removedNotice)   removedNotice.classList.remove('hidden');
        }

        function undoRemove() {
            removeFlag.value = '0';
            @if($staff->profile_image)
                avatarPreview.src = "{{ asset('storage/' . $staff->profile_image) }}";
                avatarPreview.classList.remove('hidden');
                if (avatarPlaceholder) avatarPlaceholder.classList.add('hidden');
            @endif
            const removedNotice   = document.getElementById('removedNotice');
            const removeContainer = document.getElementById('removeCurrentContainer');
            if (removedNotice)   removedNotice.classList.add('hidden');
            if (removeContainer) removeContainer.classList.remove('hidden');
        }

        // Drag & Drop
        uploadArea.addEventListener('dragover', (e) => { e.preventDefault(); uploadArea.classList.add('drag-over'); });
        uploadArea.addEventListener('dragleave', () => uploadArea.classList.remove('drag-over'));
        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('drag-over');
            const file = e.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) {
                const dt = new DataTransfer();
                dt.items.add(file);
                profileInput.files = dt.files;
                showNewPreview(file);
                removeFlag.value = '0';
            }
        });
    </script>
</body>
</html>