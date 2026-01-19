<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Management - Villa Elena Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/sunflower1.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
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
        
        .role-badge {
            display: inline-flex;
            align-items: center;
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 500;
            border: 1px solid;
        }
        
        .role-manager {
            background-color: rgba(245, 158, 11, 0.1);
            color: #b45309;
            border-color: #fbbf24;
        }
        
        .role-admin {
            background-color: rgba(244, 63, 94, 0.1);
            color: #be123c;
            border-color: #fda4af;
        }
        
        .role-staff {
            background-color: rgba(59, 130, 246, 0.1);
            color: #1d4ed8;
            border-color: #93c5fd;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Sidebar -->
    @include('adminFolder.partials.sidebar')
    

    <!-- Main Content -->
    <div class="ml-64 p-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Staff Management</h1>
                <p class="text-gray-600 mt-2">Manage your staff accounts and permissions</p>
            </div>
            <div class="text-right">
                <div class="text-sm text-gray-500">{{ now()->format('l, F j, Y') }}</div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif

        <!-- Add New Staff Button -->
        <div class="flex justify-between items-center mb-6">
            <div class="text-sm text-gray-600">
                Showing {{ $staff->count() }} staff member(s)
            </div>
            <a href="{{ route('admin.staff.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-medium flex items-center gap-2 transition-colors">
                <i class="fas fa-plus"></i>
                Add New Staff
            </a>
        </div>

        <!-- Staff Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Staff Member</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($staff as $member)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center text-white font-semibold text-sm">
                                            {{ substr($member->name, 0, 2) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $member->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $member->username }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $member->email }}</div>
                                    <div class="text-sm text-gray-500">{{ $member->phoneNumber ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($member->role === 'manager')
                                        <span class="role-badge role-manager">Manager</span>
                                    @else
                                        <span class="role-badge role-staff">Staff</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $member->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.staff.edit', $member->userID) }}" 
                                           class="text-blue-600 hover:text-blue-900 transition-colors p-2 rounded hover:bg-blue-50">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($member->userID != auth()->id())
                                            <form action="{{ route('admin.staff.destroy', $member->userID) }}" 
                                                  method="POST" 
                                                  class="inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this staff member?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 transition-colors p-2 rounded hover:bg-red-50">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                                        <p class="text-lg font-medium">No staff members found</p>
                                        <p class="mt-1">Get started by adding your first staff member.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-blue-50 text-blue-600">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Users</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $staff->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-green-50 text-green-600">
                        <i class="fas fa-user-shield text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Managers</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $staff->where('role', 'manager')->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-purple-50 text-purple-600">
                        <i class="fas fa-user-check text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Staff Members</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $staff->where('role', 'staff')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>