<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $property->title }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('properties.edit', $property) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                    Edit Property
                </a>
                <a href="{{ route('properties.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                    ← Back to Properties
                </a>
            </div>
        </div>
    </x-slot>

    <x-slot name="sidebar">
        <x-admin-sidebar />

        <x-admin-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
            <svg class="text-gray-400 mr-3 flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Profile
        </x-admin-nav-link>
    </x-slot>

    <div class="space-y-6">
        <!-- Property Header -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <div class="flex items-center space-x-2 mb-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $property->type_display }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $property->status == 'for_sale' ? 'bg-green-100 text-green-800' : 
                                   ($property->status == 'for_rent' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($property->status == 'sold' ? 'bg-gray-100 text-gray-800' : 'bg-purple-100 text-purple-800')) }}">
                                {{ $property->status_display }}
                            </span>
                            @if($property->is_featured)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Featured
                                </span>
                            @endif
                        </div>
                        <div class="text-3xl font-bold text-blue-600 mb-2">
                            {{ $property->formatted_price }}
                        </div>
                        <div class="text-gray-600">
                            <svg class="inline w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            {{ $property->address }}, {{ $property->city }}, {{ $property->state }} {{ $property->postal_code }}
                        </div>
                    </div>
                    <div class="text-right text-sm text-gray-500">
                        <div>Property ID: {{ $property->property_id }}</div>
                        <div>{{ $property->views }} view{{ $property->views != 1 ? 's' : '' }}</div>
                        <div>Added {{ $property->created_at->diffForHumans() }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Property Images -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Property Images</h3>
                
                @if($property->images && (isset($property->images['main']) || (isset($property->images['additional']) && count($property->images['additional']) > 0)))
                    @php
                        $allImages = [];
                        
                        // Add main image first if it exists
                        if(isset($property->images['main'])) {
                            $allImages[] = [
                                'path' => $property->images['main'],
                                'type' => 'main'
                            ];
                        }
                        
                        // Add additional images
                        if(isset($property->images['additional']) && count($property->images['additional']) > 0) {
                            foreach($property->images['additional'] as $image) {
                                $allImages[] = [
                                    'path' => $image,
                                    'type' => 'additional'
                                ];
                            }
                        }
                        
                        $totalImages = count($allImages);
                    @endphp

                    <div class="mb-4">
                        <p class="text-sm text-gray-600">{{ $totalImages }} image{{ $totalImages != 1 ? 's' : '' }} • Click to view full size</p>
                    </div>

                    <!-- Image Thumbnails Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($allImages as $index => $imageData)
                            <div class="relative group">
                                <img src="{{ asset('storage/' . $imageData['path']) }}" 
                                     alt="{{ $property->title }}" 
                                     class="max-w-full h-auto object-cover rounded-lg border border-gray-300 cursor-pointer hover:opacity-80 transition-opacity shadow-sm"
                                     onclick="openImageModal('{{ asset('storage/' . $imageData['path']) }}', {{ $index }})">
                                
                                <!-- Image overlay with type indicator -->
                                @if($imageData['type'] === 'main')
                                    <div class="absolute top-2 left-2 bg-blue-600 text-white text-xs px-2 py-1 rounded-md font-medium">
                                        Main
                                    </div>
                                @endif
                                
                                <!-- Image number overlay -->
                                <div class="absolute bottom-2 right-2 bg-black bg-opacity-60 text-white text-xs px-2 py-1 rounded-md">
                                    {{ $index + 1 }}/{{ $totalImages }}
                                </div>

                                <!-- Hover overlay -->
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-200 rounded-lg flex items-center justify-center">
                                    <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                    </svg>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- No Images Placeholder -->
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No images available</h3>
                        <p class="mt-1 text-sm text-gray-500">No images have been uploaded for this property.</p>
                        @if(Auth::id() === $property->user_id)
                            <div class="mt-6">
                                <a href="{{ route('properties.edit', $property) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Add Images
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Property Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Details -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Property Details</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                            @if($property->bedrooms)
                                <div class="text-center p-3 bg-gray-50 rounded-lg">
                                    <div class="text-2xl font-bold text-blue-600">{{ $property->bedrooms }}</div>
                                    <div class="text-sm text-gray-600">Bedroom{{ $property->bedrooms != 1 ? 's' : '' }}</div>
                                </div>
                            @endif
                            @if($property->bathrooms)
                                <div class="text-center p-3 bg-gray-50 rounded-lg">
                                    <div class="text-2xl font-bold text-blue-600">{{ $property->bathrooms }}</div>
                                    <div class="text-sm text-gray-600">Bathroom{{ $property->bathrooms != 1 ? 's' : '' }}</div>
                                </div>
                            @endif
                            @if($property->square_feet)
                                <div class="text-center p-3 bg-gray-50 rounded-lg">
                                    <div class="text-2xl font-bold text-blue-600">{{ number_format($property->square_feet) }}</div>
                                    <div class="text-sm text-gray-600">Sq Ft</div>
                                </div>
                            @endif
                            @if($property->year_built)
                                <div class="text-center p-3 bg-gray-50 rounded-lg">
                                    <div class="text-2xl font-bold text-blue-600">{{ $property->year_built }}</div>
                                    <div class="text-sm text-gray-600">Year Built</div>
                                </div>
                            @endif
                        </div>

                        @if($property->lot_size)
                            <div class="mb-4">
                                <span class="font-medium text-gray-700">Lot Size:</span> {{ number_format($property->lot_size) }} sq ft
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Description -->
                @if($property->description)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Description</h3>
                            <p class="text-gray-700 leading-relaxed">{{ $property->description }}</p>
                        </div>
                    </div>
                @endif

                <!-- Features -->
                @if($property->features && count($property->features) > 0)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Property Features</h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($property->features as $feature)
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-gray-700">{{ $feature }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Key Features -->
                @if($property->key_features)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Key Features</h3>
                            <div class="text-gray-700 whitespace-pre-line">{{ $property->key_features }}</div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Contact Information -->
                @if($property->agent_name || $property->agent_phone || $property->agent_email)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Contact Information</h3>
                            
                            @if($property->agent_name)
                                <div class="mb-3">
                                    <span class="font-medium text-gray-700">Agent:</span>
                                    <div class="text-gray-900">{{ $property->agent_name }}</div>
                                </div>
                            @endif

                            @if($property->agent_phone)
                                <div class="mb-3">
                                    <span class="font-medium text-gray-700">Phone:</span>
                                    <div class="text-gray-900">
                                        <a href="tel:{{ $property->agent_phone }}" class="text-blue-600 hover:text-blue-700">
                                            {{ $property->agent_phone }}
                                        </a>
                                    </div>
                                </div>
                            @endif

                            @if($property->agent_email)
                                <div class="mb-3">
                                    <span class="font-medium text-gray-700">Email:</span>
                                    <div class="text-gray-900">
                                        <a href="mailto:{{ $property->agent_email }}" class="text-blue-600 hover:text-blue-700">
                                            {{ $property->agent_email }}
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Property Info -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Property Information</h3>
                        
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Type:</span>
                                <span class="font-medium">{{ $property->type_display }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status:</span>
                                <span class="font-medium">{{ $property->status_display }}</span>
                            </div>
                            @if($property->availability_date)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Available:</span>
                                    <span class="font-medium">{{ $property->availability_date->format('M d, Y') }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between">
                                <span class="text-gray-600">Listed:</span>
                                <span class="font-medium">{{ $property->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Views:</span>
                                <span class="font-medium">{{ $property->views }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Virtual Tour -->
                @if($property->virtual_tour_link)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Virtual Tour</h3>
                            <a href="{{ $property->virtual_tour_link }}" target="_blank" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition duration-150 ease-in-out w-full justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                View Virtual Tour
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 items-center justify-center" style="display: none;" onclick="closeImageModal()">
        <div class="max-w-4xl max-h-screen p-4">
            <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain">
        </div>
        <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white text-4xl hover:text-gray-300">
            &times;
        </button>
    </div>

    <script>
        function openImageModal(imageSrc, imageIndex = null) {
            document.getElementById('modalImage').src = imageSrc;
            const modal = document.getElementById('imageModal');
            modal.style.display = 'flex';
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.style.display = 'none';
        }

        // Close modal with escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });
    </script>
</x-admin-layout>
