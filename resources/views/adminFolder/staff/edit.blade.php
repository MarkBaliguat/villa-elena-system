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
        /* Main Content Responsive Layout */
        #mainContent {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-left: 16rem;
            width: calc(100% - 16rem);
        }

        #mainContent.ml-24 {
            margin-left: 5.5rem;
            width: calc(100% - 5.5rem);
        }

        #mainContent.ml-64 {
            margin-left: 16rem;
            width: calc(100% - 16rem);
        }

        @media (max-width: 768px) {
            #mainContent {
                margin-left: 5.5rem !important;
                width: calc(100% - 5.5rem) !important;
                padding: 1rem !important;
            }
        }

        @media (max-width: 640px) {
            #mainContent {
                padding: 0.75rem !important;
            }
        }

        .sidebar {
            width: 16rem;
            position: fixed;
            left: 0;
            top: 0;
            height: 100%;
            background-color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-right: 1px solid #e5e7eb;
            display: flex;
            flex-direction: column;
            z-index: 50;
        }
        
        .icon-container {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
        }
        
        .active-item {
            background-color: #eff6ff;
            color: #1d4ed8;
            border-right: 2px solid #2563eb;
            font-weight: 600;
        }
        
        .hover-item:hover {
            background-color: #f9fafb;
            color: #2563eb;
        }
    </style>
</head>
<body class="bg-gray-50">
    @include('adminFolder.partials.sidebar')

    <!-- Main Content -->
    <div class="ml-64 p-8" id="mainContent">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 md:mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Edit Staff</h1>
                <p class="text-sm md:text-base text-gray-600 mt-1 md:mt-2">Update staff account information</p>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 md:p-6">
            <form action="{{ route('admin.staff.update', $staff->userID) }}" method="POST" id="editStaffForm">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                    <!-- Personal Information -->
                    <div class="space-y-4">
                        <h3 class="text-base md:text-lg font-semibold text-gray-900 border-b pb-2">Personal Information</h3>
                        
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $staff->name) }}"
                                   class="w-full px-4 py-2.5 md:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base"
                                   required
                                   pattern="[a-zA-Z\s]+"
                                   title="Name should only contain letters and spaces">
                            @error('name')
                                <p class="text-red-500 text-xs md:text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username *</label>
                            <input type="text" 
                                   id="username" 
                                   name="username" 
                                   value="{{ old('username', $staff->username) }}"
                                   class="w-full px-4 py-2.5 md:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base"
                                   required
                                   pattern="[a-zA-Z0-9_]+"
                                   title="Username may only contain letters, numbers, and underscores">
                            @error('username')
                                <p class="text-red-500 text-xs md:text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Contact & Role -->
                    <div class="space-y-4">
                        <h3 class="text-base md:text-lg font-semibold text-gray-900 border-b pb-2">Contact & Role</h3>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $staff->email) }}"
                                   class="w-full px-4 py-2.5 md:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base"
                                   required>
                            @error('email')
                                <p class="text-red-500 text-xs md:text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phoneNumber" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input type="tel" 
                                   id="phoneNumber" 
                                   name="phoneNumber" 
                                   value="{{ old('phoneNumber', $staff->phoneNumber) }}"
                                   class="w-full px-4 py-2.5 md:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base"
                                   maxlength="11"
                                   pattern="09[0-9]{9}"
                                   title="Please enter a valid 11-digit Philippine number starting with 09">
                            @error('phoneNumber')
                                <p class="text-red-500 text-xs md:text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                            <select id="role" 
                                    name="role" 
                                    class="w-full px-4 py-2.5 md:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base"
                                    required>
                                <option value="">Select Role</option>
                                <option value="staff" {{ old('role', $staff->role) == 'staff' ? 'selected' : '' }}>Staff</option>
                                <option value="manager" {{ old('role', $staff->role) == 'manager' ? 'selected' : '' }}>Manager</option>
                            </select>
                            @error('role')
                                <p class="text-red-500 text-xs md:text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="md:col-span-2 space-y-4">
                        <h3 class="text-base md:text-lg font-semibold text-gray-900 border-b pb-2">Security (Leave blank to keep current password)</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                                <input type="password" 
                                       id="password" 
                                       name="password" 
                                       class="w-full px-4 py-2.5 md:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base"
                                       minlength="8">
                                @error('password')
                                    <p class="text-red-500 text-xs md:text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                                <input type="password" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
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
        // ===== SIDEBAR RESPONSIVE SCRIPT =====
        window.addEventListener('sidebarToggled', (event) => {
            const mainContent = document.getElementById('mainContent');
            if (event.detail.collapsed) {
                mainContent.classList.remove('ml-64');
                mainContent.classList.add('ml-24');
            } else {
                mainContent.classList.remove('ml-24');
                mainContent.classList.add('ml-64');
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            const savedState = localStorage.getItem('sidebarState');
            const mainContent = document.getElementById('mainContent');
            
            if (window.innerWidth > 768) {
                if (savedState === 'collapsed') {
                    mainContent.classList.remove('ml-64');
                    mainContent.classList.add('ml-24');
                }
            } else {
                mainContent.classList.remove('ml-64');
                mainContent.classList.add('ml-24');
            }
        });

        window.addEventListener('resize', () => {
            const mainContent = document.getElementById('mainContent');
            const savedState = localStorage.getItem('sidebarState');
            
            if (window.innerWidth > 768) {
                if (savedState === 'collapsed') {
                    mainContent.classList.remove('ml-64');
                    mainContent.classList.add('ml-24');
                } else {
                    mainContent.classList.remove('ml-24');
                    mainContent.classList.add('ml-64');
                }
            } else {
                mainContent.classList.remove('ml-64');
                mainContent.classList.add('ml-24');
            }
        });
        // ===== END SIDEBAR RESPONSIVE SCRIPT =====

        // Phone number validation
        document.getElementById('phoneNumber').addEventListener('input', function(e) {
            this.value = this.value.replace(/\D/g, '');
            
            if (this.value.length > 11) {
                this.value = this.value.slice(0, 11);
            }
            
            if (this.value.length > 0 && !this.value.startsWith('09')) {
                this.value = '09' + this.value.slice(2);
            }
        });

        // Password confirmation validation
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password_confirmation');

        function validatePasswords() {
            if (passwordInput.value && confirmPasswordInput.value) {
                if (passwordInput.value !== confirmPasswordInput.value) {
                    confirmPasswordInput.setCustomValidity('Passwords do not match');
                } else {
                    confirmPasswordInput.setCustomValidity('');
                }
            }
        }

        passwordInput.addEventListener('input', validatePasswords);
        confirmPasswordInput.addEventListener('input', validatePasswords);
    </script>
</body>
</html>