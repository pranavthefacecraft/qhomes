<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📋 Property Listings Management
            </h2>
            <a href="{{ route('properties.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                <i class="fas fa-plus mr-2"></i> Add New Property
            </a>
        </div>
    </x-slot>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 mb-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-list text-2xl text-blue-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Listings</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['total'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-2xl text-green-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Active</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['active'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-handshake text-2xl text-blue-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Sold/Rented</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['sold_rented'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-star text-2xl text-yellow-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Featured</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['featured'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-edit text-2xl text-orange-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Draft</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['draft'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-pause-circle text-2xl text-red-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Inactive</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['inactive'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Filters & Search</h3>
        </div>
        <div class="px-6 py-4">
            <form method="GET" action="{{ route('listings.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>All Statuses</option>
                            <option value="for_sale" {{ $statusFilter === 'for_sale' ? 'selected' : '' }}>For Sale</option>
                            <option value="for_rent" {{ $statusFilter === 'for_rent' ? 'selected' : '' }}>For Rent</option>
                            <option value="sold" {{ $statusFilter === 'sold' ? 'selected' : '' }}>Sold</option>
                            <option value="rented" {{ $statusFilter === 'rented' ? 'selected' : '' }}>Rented</option>
                            <option value="draft" {{ $statusFilter === 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>
                    <div>
                        <label for="featured" class="block text-sm font-medium text-gray-700">Featured</label>
                        <select name="featured" id="featured" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="all" {{ $featuredFilter === 'all' ? 'selected' : '' }}>All</option>
                            <option value="featured" {{ $featuredFilter === 'featured' ? 'selected' : '' }}>Featured Only</option>
                            <option value="regular" {{ $featuredFilter === 'regular' ? 'selected' : '' }}>Regular Only</option>
                        </select>
                    </div>
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                        <input type="text" name="search" id="search" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                               placeholder="Search by title, address, or city..." 
                               value="{{ $search }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">&nbsp;</label>
                        <div class="flex space-x-2">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                                Filter
                            </button>
                            <a href="{{ route('listings.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                                Clear
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Listings Table -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900">Property Listings</h3>
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                    Bulk Actions
                    <i class="fas fa-chevron-down ml-1"></i>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10" style="display: none;">
                    <div class="py-1">
                        <a href="#" onclick="bulkAction('activate')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Activate Selected</a>
                        <a href="#" onclick="bulkAction('deactivate')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Deactivate Selected</a>
                        <a href="#" onclick="bulkAction('feature')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Feature Selected</a>
                        <a href="#" onclick="bulkAction('unfeature')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Unfeature Selected</a>
                        <div class="border-t border-gray-100"></div>
                        <a href="#" onclick="bulkAction('delete')" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Delete Selected</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            @if($properties->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Featured</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Active</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($properties as $property)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" class="property-checkbox rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" value="{{ $property->id }}">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-16 w-16">
                                            @if($property->images && isset($property->images['main']))
                                                <img src="{{ asset('storage/' . $property->images['main']) }}" 
                                                     alt="{{ $property->title }}" 
                                                     class="h-16 w-16 rounded-lg object-cover">
                                            @else
                                                <div class="h-16 w-16 rounded-lg bg-gray-200 flex items-center justify-center">
                                                    <i class="fas fa-image text-gray-400"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $property->title }}</div>
                                            <div class="text-sm text-gray-500">
                                                📍 {{ $property->address }}, {{ $property->city }}
                                            </div>
                                            <div class="mt-1 flex space-x-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ ucfirst($property->type) }}
                                                </span>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    {{ $property->bedrooms }}BR/{{ $property->bathrooms }}BA
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-blue-600">{{ $property->display_price }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ in_array($property->status, ['for_sale', 'for_rent']) ? 'bg-green-100 text-green-800' : 
                                           (in_array($property->status, ['sold', 'rented']) ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucwords(str_replace('_', ' ', $property->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <form method="POST" action="{{ route('listings.toggle-featured', $property) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-{{ $property->is_featured ? 'yellow' : 'gray' }}-600 hover:text-{{ $property->is_featured ? 'yellow' : 'gray' }}-900" 
                                                title="{{ $property->is_featured ? 'Remove from Featured' : 'Make Featured' }}">
                                            <i class="fas fa-star text-xl"></i>
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <form method="POST" action="{{ route('listings.toggle-active', $property) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-{{ $property->is_active ? 'green' : 'red' }}-600 hover:text-{{ $property->is_active ? 'green' : 'red' }}-900" 
                                                title="{{ $property->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="fas fa-{{ $property->is_active ? 'eye' : 'eye-slash' }} text-xl"></i>
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $property->created_at->format('M j, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open" class="text-gray-600 hover:text-gray-900">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10" style="display: none;">
                                            <div class="py-1">
                                                <a href="{{ route('properties.show', $property) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">👁️ View</a>
                                                <a href="{{ route('properties.edit', $property) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">✏️ Edit</a>
                                                <div class="border-t border-gray-100"></div>
                                                <a href="#" onclick="deleteProperty({{ $property->id }})" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">🗑️ Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                    <div class="flex-1 flex justify-between sm:hidden">
                        {{ $properties->appends(request()->query())->links('pagination::simple-tailwind') }}
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Showing <span class="font-medium">{{ $properties->firstItem() }}</span> to <span class="font-medium">{{ $properties->lastItem() }}</span> of <span class="font-medium">{{ $properties->total() }}</span> results
                            </p>
                        </div>
                        <div>
                            {{ $properties->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-home text-4xl text-gray-400 mb-4"></i>
                    <h5 class="text-xl font-medium text-gray-900 mb-2">No properties found</h5>
                    <p class="text-gray-500">Try adjusting your filters or <a href="{{ route('properties.create') }}" class="text-blue-600 hover:text-blue-800">create a new property</a>.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Bulk Action Form -->
    <form id="bulkActionForm" method="POST" action="{{ route('listings.bulk-action') }}" style="display: none;">
        @csrf
        <input type="hidden" name="action" id="bulkActionType">
        <div id="bulkPropertyIds"></div>
    </form>

    <script>
        // Select all functionality
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.property-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Bulk action function
        function bulkAction(action) {
            const checkedBoxes = document.querySelectorAll('.property-checkbox:checked');
            
            if (checkedBoxes.length === 0) {
                alert('Please select at least one property.');
                return;
            }

            const confirmMessage = action === 'delete' 
                ? 'Are you sure you want to delete the selected properties? This action cannot be undone.'
                : `Are you sure you want to ${action} the selected properties?`;

            if (confirm(confirmMessage)) {
                const form = document.getElementById('bulkActionForm');
                const actionInput = document.getElementById('bulkActionType');
                const idsContainer = document.getElementById('bulkPropertyIds');
                
                // Clear previous property IDs
                idsContainer.innerHTML = '';
                
                // Add selected property IDs
                checkedBoxes.forEach(checkbox => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'properties[]';
                    input.value = checkbox.value;
                    idsContainer.appendChild(input);
                });
                
                actionInput.value = action;
                form.submit();
            }
        }

        // Delete single property
        function deleteProperty(propertyId) {
            if (confirm('Are you sure you want to delete this property? This action cannot be undone.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/properties/${propertyId}`;
                form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</x-admin-layout>
