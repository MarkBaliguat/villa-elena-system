<nav x-data="{ open: false }" class="bg-white shadow-lg border-b border-yellow-200">
    
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            <!-- Left Side - Logo/Brand -->
            <div class="flex items-center">
                <div class="flex items-center">
                    <span class="text-yellow-500 text-2xl mr-2">🌻</span>
                    <span class="text-xl font-bold text-gray-800 cursive-font">Villa Elena</span>
                </div>
            </div>

            <!-- Center - Navigation Links -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ url('/') }}" class="text-gray-700 hover:text-yellow-600 font-medium transition duration-150 ease-in-out">
                    Home
                </a>

                <a href="{{ route('roomBooking') }}" class="text-gray-700 hover:text-yellow-600 font-medium transition duration-150 ease-in-out">
                    Book Now
                </a>
            </div>

            <!-- Right Side - User Dropdown -->
            <div class="flex items-center space-x-4">
                <!-- Cart Icon -->
                <a href="#" class="text-gray-600 hover:text-yellow-600 transition duration-150 ease-in-out">
                    <i class="fas fa-shopping-cart text-lg"></i>
                </a>

                <!-- Settings Dropdown -->
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:text-yellow-600 focus:outline-none transition ease-in-out duration-150">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-user-circle text-lg"></i>
                                    <span>{{ Auth::user()->name }}</span>
                                </div>

                                <div class="ms-2">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')" class="flex items-center space-x-2">
                                <i class="fas fa-user w-4 text-center"></i>
                                <span>{{ __('Profile') }}</span>
                            </x-dropdown-link>
                            
                            @if(Auth::user()->role === 'staff' || Auth::user()->role === 'manager')
                                <x-dropdown-link :href="route('admin.dashboard')" class="flex items-center space-x-2">
                                    <i class="fas fa-tachometer-alt w-4 text-center"></i>
                                    <span>Admin Dashboard</span>
                                </x-dropdown-link>
                            @endif
                            <x-dropdown-link :href="url('/')" class="flex items-center space-x-2">
                                <i class="fas fa-user w-4 text-center"></i>
                                <span>{{ __('Home') }}</span>
                            </x-dropdown-link>
                            
                            <div class="border-t border-gray-100 my-1"></div>
                            
                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();"
                                        class="flex items-center space-x-2 text-red-600 hover:text-red-700">
                                    <i class="fas fa-sign-out-alt w-4 text-center"></i>
                                    <span>{{ __('Log Out') }}</span>
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-600 hover:text-yellow-600 hover:bg-yellow-50 focus:outline-none focus:bg-yellow-50 focus:text-yellow-600 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-yellow-100 shadow-lg">
        
        <!-- Navigation Links -->
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="url('/')" class="flex items-center space-x-2">
                <i class="fas fa-home w-5 text-center"></i>
                <span>Home</span>
            </x-responsive-nav-link>



        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-3 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="flex items-center space-x-2">
                    <i class="fas fa-user w-5 text-center"></i>
                    <span>{{ __('Profile') }}</span>
                </x-responsive-nav-link>
                
                @if(Auth::user()->role === 'staff' || Auth::user()->role === 'manager')
                    <x-responsive-nav-link :href="route('admin.dashboard')" class="flex items-center space-x-2">
                        <i class="fas fa-tachometer-alt w-5 text-center"></i>
                        <span>Admin Dashboard</span>
                    </x-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();"
                            class="flex items-center space-x-2 text-red-600">
                        <i class="fas fa-sign-out-alt w-5 text-center"></i>
                        <span>{{ __('Log Out') }}</span>
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<style>
    .cursive-font {
        font-family: 'Dancing Script', cursive;
    }
    
    /* Custom styles for dropdown links */
    .dropdown-link {
        display: block;
        width: 100%;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        color: #374151;
        text-decoration: none;
        transition: all 0.15s ease-in-out;
    }
    
    .dropdown-link:hover {
        background-color: #fefce8;
        color: #d97706;
    }
    
    /* Responsive nav link styles */
    .responsive-nav-link {
        display: block;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        color: #374151;
        text-decoration: none;
        transition: all 0.15s ease-in-out;
    }
    
    .responsive-nav-link:hover {
        background-color: #fefce8;
        color: #d97706;
    }
</style>