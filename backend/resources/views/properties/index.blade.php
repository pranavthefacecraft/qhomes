<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Properties') }}
            </h2>
            <a href="{{ route('properties.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                + Add New Property
            </a>
        </div>
    </x-slot>



    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if($properties->count() > 0)
        <!-- Properties Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            @foreach($properties as $property)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                    <div class="p-6">
                        <!-- Property Header -->
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $property->title }}</h3>
                                <div class="flex items-center space-x-2 text-sm text-gray-600">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $property->type_display }}
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $property->status == 'for_sale' ? 'bg-green-100 text-green-800' : 
                                           ($property->status == 'for_rent' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($property->status == 'sold' ? 'bg-gray-100 text-gray-800' : 'bg-purple-100 text-purple-800')) }}">
                                        {{ $property->status_display }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Property Actions -->
                            <div class="flex items-center space-x-2" x-data="{ open: false }">
                                <button @click="open = !open" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                    </svg>
                                </button>
                                <div x-show="open" @click.away="open = false" x-cloak 
                                     class="absolute right-0 mt-6 w-48 bg-white rounded-md shadow-lg z-10 border border-gray-200">
                                    <div class="py-1">
                                        <a href="{{ route('properties.show', $property) }}" 
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">View Details</a>
                                        <a href="{{ route('properties.edit', $property) }}" 
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit</a>
                                        <form method="POST" action="{{ route('properties.destroy', $property) }}" 
                                              onsubmit="return confirm('Are you sure you want to delete this property?')" class="block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Property Details -->
                        <div class="space-y-2 mb-4">
                            <div class="text-2xl font-bold text-blue-600">
                                {{ $property->display_price }}
                            </div>
                            <div class="text-sm text-gray-600">
                                <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                {{ $property->city }}, {{ $property->state }}
                            </div>
                        </div>

                        <!-- Property Stats -->
                        @if($property->bedrooms || $property->bathrooms || $property->square_feet)
                            <div class="flex items-center space-x-4 text-sm text-gray-600 mb-4">
                                @if($property->bedrooms)
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                        </svg>
                                        {{ $property->bedrooms }} bed{{ $property->bedrooms != 1 ? 's' : '' }}
                                    </div>
                                @endif
                                @if($property->bathrooms)
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        {{ $property->bathrooms }} bath{{ $property->bathrooms != 1 ? 's' : '' }}
                                    </div>
                                @endif
                                @if($property->square_feet)
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                                        </svg>
                                        {{ number_format($property->square_feet) }} sqft
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Property Footer -->
                        <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                            <div class="text-xs text-gray-500">
                                {{ $property->views }} view{{ $property->views != 1 ? 's' : '' }}
                            </div>
                            <div class="text-xs text-gray-500">
                                Added {{ $property->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $properties->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No properties</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by adding your first property.</p>
                <div class="mt-6">
                    <a href="{{ route('properties.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Add New Property
                    </a>
                </div>
            </div>
        </div>
    @endif
</x-admin-layout>
