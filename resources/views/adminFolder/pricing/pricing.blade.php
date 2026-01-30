<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/sunflower1.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Pricing Management - Villa Elena</title>
</head>
<body class="bg-gray-50">
    <div class="flex">
        {{-- Sidebar --}}
        @include('adminFolder.partials.sidebar')
        
        {{-- Main Content --}}
        <div class="ml-64 flex-1 p-8">
            {{-- Header Section --}}
            <div class="mb-6">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Pricing Management</h1>
                        <p class="text-gray-500 text-sm mt-1">Manage entrance fee pricing for cottage bookings</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-600" id="currentDate"></span>
                    </div>
                </div>
            </div>
            
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 notification-message">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 notification-message">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Current Price Card -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Current Entrance Fee</h2>
                    @if($entranceFee && auth()->user()->role === 'manager')
                    <form action="{{ route('admin.pricing.entrance-fee.deactivate') }}" method="POST" onsubmit="return confirmDeactivation()">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100 border border-red-200 rounded-lg transition-all duration-200 ease-in-out hover:shadow-sm">
                            <i class="fas fa-power-off mr-2 text-red-500"></i>
                            Deactivate Fee
                        </button>
                    </form>
                    @endif
                </div>
                
                @if($entranceFee)
                    <div class="p-6 bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 rounded-xl border border-blue-100 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-3">
                                    <h3 class="text-xl font-semibold text-gray-900">{{ $entranceFee->feeName }}</h3>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                                        Active
                                    </span>
                                </div>
                                <p class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent mb-2">
                                    ₱{{ number_format($entranceFee->amount, 2) }}
                                </p>
                                <p class="text-gray-600 text-sm flex items-center">
                                    <i class="fas fa-calendar-alt mr-2 text-gray-400"></i>
                                    Active since {{ $entranceFee->created_at->format('M d, Y') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                                    <i class="fas fa-tag text-white text-2xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-12 border-2 border-dashed border-gray-300 rounded-xl bg-gray-50">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-tag text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Entrance Fee Set</h3>
                        <p class="text-gray-500 mb-4">Please set the entrance fee price to start managing pricing.</p>
                        @if(auth()->user()->role === 'manager')
                        <button onclick="openUpdateModal()" class="inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                            <i class="fas fa-plus mr-2"></i>
                            Set Entrance Fee
                        </button>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Staff View -->
            @if(auth()->user()->role !== 'manager')
                <div class="bg-white rounded-lg shadow-sm p-8 text-center">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-lock text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Access Restricted</h3>
                    <p class="text-gray-500">Only managers can update pricing information.</p>
                </div>
            @endif

            <!-- Floating Action Button (Manager Only) -->
            @if(auth()->user()->role === 'manager' && $entranceFee)
            <div class="fixed bottom-8 right-8 group">
                <!-- Main FAB Button -->
                <button onclick="openUpdateModal()" 
                        class="w-16 h-16 bg-gradient-to-br from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-full shadow-2xl flex items-center justify-center transition-all duration-300 hover:scale-110 hover:rotate-90 group">
                    <i class="fas fa-edit text-xl"></i>
                </button>
                
                <!-- Tooltip -->
                <div class="absolute bottom-full right-0 mb-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none">
                    <div class="bg-gray-900 text-white text-sm px-4 py-2 rounded-lg whitespace-nowrap shadow-lg">
                        Update Entrance Fee
                        <div class="absolute top-full right-6 w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-gray-900"></div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Modal Overlay -->
            <div id="updateModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 animate-fadeIn">
                <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto animate-slideUp">
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6 rounded-t-2xl">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-edit text-white text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-white">
                                        {{ $entranceFee ? 'Update Entrance Fee' : 'Set Entrance Fee' }}
                                    </h2>
                                    <p class="text-blue-100 text-sm">Modify pricing information</p>
                                </div>
                            </div>
                            <button onclick="closeUpdateModal()" class="text-white hover:bg-white hover:bg-opacity-20 w-10 h-10 rounded-lg transition-all duration-200 flex items-center justify-center">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-6">
                        <form action="{{ route('admin.pricing.entrance-fee.update') }}" method="POST">
                            @csrf
                            
                            <div class="space-y-6">
                                <!-- Fee Name -->
                                <div>
                                    <label for="feeName" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Fee Name <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                            <i class="fas fa-tag"></i>
                                        </span>
                                        <input type="text" 
                                               id="feeName" 
                                               name="feeName" 
                                               value="{{ old('feeName', $entranceFee->feeName ?? 'Standard Entrance Fee') }}"
                                               class="w-full pl-12 pr-4 py-3.5 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                               placeholder="e.g., Standard Entrance Fee"
                                               required>
                                    </div>
                                    @error('feeName')
                                        <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Amount -->
                                <div>
                                    <label for="amount" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Amount (PHP) <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-semibold text-lg">₱</span>
                                        <input type="number" 
                                               id="amount" 
                                               name="amount" 
                                               value="{{ old('amount', $entranceFee->amount ?? '') }}"
                                               step="0.01"
                                               min="0"
                                               class="w-full pl-12 pr-4 py-3.5 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-lg font-semibold"
                                               placeholder="0.00"
                                               required>
                                    </div>
                                    @error('amount')
                                        <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Important Note -->
                                <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border-2 border-yellow-200 rounded-xl p-4">
                                    <div class="flex items-start gap-3">
                                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-semibold text-yellow-900 mb-1">Important Note</h4>
                                            <p class="text-sm text-yellow-800 leading-relaxed">
                                                @if($entranceFee)
                                                    Updating the entrance fee will deactivate the current price and create a new active price. 
                                                    Existing bookings will keep their original entrance fee amount.
                                                @else
                                                    Setting an entrance fee will apply to all new cottage bookings.
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Footer -->
                            <div class="flex gap-3 mt-8 pt-6 border-t-2 border-gray-100">
                                <button type="button" 
                                        onclick="closeUpdateModal()"
                                        class="flex-1 px-6 py-3.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-semibold transition-all duration-200">
                                    <i class="fas fa-times mr-2"></i>
                                    Cancel
                                </button>
                                <button type="submit" 
                                        class="flex-1 px-6 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl">
                                    <i class="fas fa-save mr-2"></i>
                                    {{ $entranceFee ? 'Update Fee' : 'Set Fee' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out;
        }

        .animate-slideUp {
            animation: slideUp 0.3s ease-out;
        }

        /* Smooth scrollbar for modal */
        #updateModal > div {
            scrollbar-width: thin;
            scrollbar-color: rgba(59, 130, 246, 0.5) transparent;
        }

        #updateModal > div::-webkit-scrollbar {
            width: 6px;
        }

        #updateModal > div::-webkit-scrollbar-track {
            background: transparent;
        }

        #updateModal > div::-webkit-scrollbar-thumb {
            background-color: rgba(59, 130, 246, 0.5);
            border-radius: 20px;
        }

        /* Notification fade out */
        .notification-message {
            transition: opacity 0.3s ease-out;
        }
    </style>

    <script>
        // Set current date
        document.getElementById('currentDate').textContent = new Date().toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        // Modal functions
        function openUpdateModal() {
            document.getElementById('updateModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeUpdateModal() {
            document.getElementById('updateModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal on outside click
        document.getElementById('updateModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeUpdateModal();
            }
        });

        // Close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeUpdateModal();
            }
        });

        // Auto-hide success/error messages
        setTimeout(() => {
            const messages = document.querySelectorAll('.notification-message');
            messages.forEach(message => {
                message.style.opacity = '0';
                setTimeout(() => message.remove(), 300);
            });
        }, 3000);

        // Confirmation for deactivation
        function confirmDeactivation() {
            return confirm('Are you sure you want to deactivate the current entrance fee? This will remove the fee from all new bookings.');
        }

        // Format number input on blur
        document.getElementById('amount')?.addEventListener('blur', function() {
            if (this.value) {
                this.value = parseFloat(this.value).toFixed(2);
            }
        });
    </script>
</body>
</html>