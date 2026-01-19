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
            {{-- Header Section - SAME DESIGN AS RESERVATION --}}
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
                    <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-100">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $entranceFee->feeName }}</h3>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i>
                                        Active
                                    </span>
                                </div>
                                <p class="text-3xl font-bold text-blue-600 mb-1">₱{{ number_format($entranceFee->amount, 2) }}</p>
                                <p class="text-gray-600 text-sm flex items-center">
                                    <i class="fas fa-calendar-alt mr-2 text-gray-400"></i>
                                    Active since {{ $entranceFee->created_at->format('M d, Y') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-tag text-blue-600 text-xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8 border-2 border-dashed border-gray-300 rounded-lg bg-gray-50">
                        <i class="fas fa-tag text-gray-400 text-4xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Entrance Fee Set</h3>
                        <p class="text-gray-500">Please set the entrance fee price below.</p>
                    </div>
                @endif
            </div>

            <!-- Update Price Form - Only for Manager -->
            @if(auth()->user()->role === 'manager')
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">
                        {{ $entranceFee ? 'Update Entrance Fee' : 'Set Entrance Fee' }}
                    </h2>
                    
                    <form action="{{ route('admin.pricing.entrance-fee.update') }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Fee Name -->
                            <div>
                                <label for="feeName" class="block text-sm font-medium text-gray-700 mb-2">
                                    Fee Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="feeName" 
                                       name="feeName" 
                                       value="{{ old('feeName', $entranceFee->feeName ?? 'Standard Entrance Fee') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       placeholder="e.g., Standard Entrance Fee"
                                       required>
                                @error('feeName')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Amount -->
                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                                    Amount (PHP) <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₱</span>
                                    <input type="number" 
                                           id="amount" 
                                           name="amount" 
                                           value="{{ old('amount', $entranceFee->amount ?? '') }}"
                                           step="0.01"
                                           min="0"
                                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                           placeholder="0.00"
                                           required>
                                </div>
                                @error('amount')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Important Note -->
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-3"></i>
                                <div>
                                    <h4 class="text-sm font-medium text-yellow-800">Important Note</h4>
                                    <p class="text-sm text-yellow-700 mt-1">
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

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium flex items-center gap-2 transition">
                                <i class="fas fa-save"></i>
                                {{ $entranceFee ? 'Update Entrance Fee' : 'Set Entrance Fee' }}
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <!-- Staff View -->
                <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                    <i class="fas fa-lock text-gray-400 text-4xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Access Restricted</h3>
                    <p class="text-gray-500">Only managers can update pricing information.</p>
                </div>
            @endif

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center animate-slide-in">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center animate-slide-in">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                </div>
            @endif
        </div>
    </div>

    <style>
        .animate-slide-in {
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>

    <script>
        // Set current date - SAME AS RESERVATION
        document.getElementById('currentDate').textContent = new Date().toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        // Auto-hide success/error messages
        setTimeout(() => {
            const messages = document.querySelectorAll('.fixed');
            messages.forEach(message => {
                message.style.transition = 'all 0.3s ease-out';
                message.style.transform = 'translateX(100%)';
                message.style.opacity = '0';
                setTimeout(() => message.remove(), 300);
            });
        }, 5000);

        // Confirmation for deactivation
        function confirmDeactivation() {
            return confirm('Are you sure you want to deactivate the current entrance fee? This will remove the fee from all new bookings.');
        }
    </script>
</body>
</html>