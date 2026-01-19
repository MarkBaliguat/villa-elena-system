<!-- {{-- Units Grid --}}
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
                    {{ $unit->unitStatus == 'maintenance' ? 'bg-yellow-100 text-yellow-700' : '' }}
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
                    <span>₱{{ number_format($unit->unitRatePrice, 2) }}/night</span>
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
</div> -->