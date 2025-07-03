<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Property') }} - {{ $property->title }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('properties.show', $property) }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                    View Property
                </a>
                <a href="{{ route('properties.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                    ← Back to Properties
                </a>
            </div>
        </div>
    </x-slot>

    <x-slot name="sidebar">
        <x-admin-sidebar />
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <form method="POST" action="{{ route('properties.update', $property) }}" class="space-y-6" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Basic Information Section -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Property Title -->
                        <div class="md:col-span-2">
                            <label for="title" class="block text-sm font-medium text-gray-700">Property Title *</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $property->title) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                   placeholder="e.g., Beautiful 3BR House in Downtown" required>
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Property Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700">Property Type *</label>
                            <select name="type" id="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                <option value="">Select Type</option>
                                <option value="house" {{ old('type', $property->type) == 'house' ? 'selected' : '' }}>House</option>
                                <option value="apartment" {{ old('type', $property->type) == 'apartment' ? 'selected' : '' }}>Apartment</option>
                                <option value="condo" {{ old('type', $property->type) == 'condo' ? 'selected' : '' }}>Condo</option>
                                <option value="townhouse" {{ old('type', $property->type) == 'townhouse' ? 'selected' : '' }}>Townhouse</option>
                                <option value="commercial" {{ old('type', $property->type) == 'commercial' ? 'selected' : '' }}>Commercial</option>
                                <option value="land" {{ old('type', $property->type) == 'land' ? 'selected' : '' }}>Land</option>
                                <option value="other" {{ old('type', $property->type) == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Property Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
                            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                <option value="">Select Status</option>
                                <option value="for_sale" {{ old('status', $property->status) == 'for_sale' ? 'selected' : '' }}>For Sale</option>
                                <option value="for_rent" {{ old('status', $property->status) == 'for_rent' ? 'selected' : '' }}>For Rent</option>
                                <option value="sold" {{ old('status', $property->status) == 'sold' ? 'selected' : '' }}>Sold</option>
                                <option value="rented" {{ old('status', $property->status) == 'rented' ? 'selected' : '' }}>Rented</option>
                                <option value="draft" {{ old('status', $property->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700">Price *</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" name="price" id="price" value="{{ old('price', $property->price) }}" 
                                       class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                       placeholder="0.00" step="0.01" min="0" required>
                            </div>
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Currency -->
                        <div>
                            <label for="currency" class="block text-sm font-medium text-gray-700">Currency</label>
                            <select name="currency" id="currency" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="USD" {{ old('currency', $property->currency) == 'USD' ? 'selected' : '' }}>USD ($)</option>
                                <option value="EUR" {{ old('currency', $property->currency) == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                                <option value="GBP" {{ old('currency', $property->currency) == 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                                <option value="CAD" {{ old('currency', $property->currency) == 'CAD' ? 'selected' : '' }}>CAD ($)</option>
                                <option value="MYR" {{ old('currency', $property->currency) == 'MYR' ? 'selected' : '' }}>MYR (RM)</option>
                                <option value="CHF" {{ old('currency', $property->currency) == 'CHF' ? 'selected' : '' }}>CHF (₣)</option>
                                <option value="INR" {{ old('currency', $property->currency) == 'INR' ? 'selected' : '' }}>INR (₹)</option>
                            </select>
                            @error('currency')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Location Section -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Location</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Address -->
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700">Full Address *</label>
                            <textarea name="address" id="address" rows="3" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                      placeholder="e.g., 123 Main Street, Suite 100" required>{{ old('address', $property->address) }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- City -->
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700">City *</label>
                            <input type="text" name="city" id="city" value="{{ old('city', $property->city) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                   placeholder="e.g., New York" required>
                            @error('city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- State -->
                        <div>
                            <label for="state" class="block text-sm font-medium text-gray-700">State/Province *</label>
                            <input type="text" name="state" id="state" value="{{ old('state', $property->state) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                   placeholder="e.g., NY" required>
                            @error('state')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Postal Code -->
                        <div>
                            <label for="postal_code" class="block text-sm font-medium text-gray-700">Zip Code / Postal Code *</label>
                            <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $property->postal_code) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                   placeholder="e.g., 10001" required>
                            @error('postal_code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Country -->
                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                            <select name="country" id="country" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="US" {{ old('country', $property->country) == 'US' ? 'selected' : '' }}>United States</option>
                                <option value="MY" {{ old('country', $property->country) == 'MY' ? 'selected' : '' }}>Malaysia</option>
                                <option value="GB" {{ old('country', $property->country) == 'GB' ? 'selected' : '' }}>United Kingdom</option>
                                <option value="CH" {{ old('country', $property->country) == 'CH' ? 'selected' : '' }}>Switzerland</option>
                                <option value="IN" {{ old('country', $property->country) == 'IN' ? 'selected' : '' }}>India</option>
                                <option value="CA" {{ old('country', $property->country) == 'CA' ? 'selected' : '' }}>Canada</option>
                                <option value="AU" {{ old('country', $property->country) == 'AU' ? 'selected' : '' }}>Australia</option>
                                <option value="SG" {{ old('country', $property->country) == 'SG' ? 'selected' : '' }}>Singapore</option>
                            </select>
                            @error('country')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Property Details Section -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Property Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Bedrooms -->
                        <div>
                            <label for="bedrooms" class="block text-sm font-medium text-gray-700">Bedrooms</label>
                            <input type="number" name="bedrooms" id="bedrooms" value="{{ old('bedrooms', $property->bedrooms) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                   placeholder="0" min="0">
                            @error('bedrooms')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bathrooms -->
                        <div>
                            <label for="bathrooms" class="block text-sm font-medium text-gray-700">Bathrooms</label>
                            <input type="number" name="bathrooms" id="bathrooms" value="{{ old('bathrooms', $property->bathrooms) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                   placeholder="0.0" step="0.5" min="0">
                            @error('bathrooms')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Square Feet -->
                        <div>
                            <label for="square_feet" class="block text-sm font-medium text-gray-700">Square Feet</label>
                            <input type="number" name="square_feet" id="square_feet" value="{{ old('square_feet', $property->square_feet) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                   placeholder="0" min="0">
                            @error('square_feet')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Lot Size -->
                        <div>
                            <label for="lot_size" class="block text-sm font-medium text-gray-700">Lot Size (sq ft)</label>
                            <input type="number" name="lot_size" id="lot_size" value="{{ old('lot_size', $property->lot_size) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                   placeholder="0" min="0">
                            @error('lot_size')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Year Built -->
                        <div>
                            <label for="year_built" class="block text-sm font-medium text-gray-700">Year Built</label>
                            <input type="number" name="year_built" id="year_built" value="{{ old('year_built', $property->year_built) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                   placeholder="{{ date('Y') }}" min="1800" max="{{ date('Y') }}">
                            @error('year_built')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Features -->
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Property Features</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @php
                            $features = ['Pool', 'Garage', 'Garden', 'Balcony', 'Fireplace', 'Air Conditioning', 'Heating', 'Parking', 'Security System', 'Gym', 'Laundry', 'Dishwasher'];
                            $selectedFeatures = old('features', $property->features ?? []);
                            @endphp
                            @foreach($features as $feature)
                            <div class="flex items-center">
                                <input type="checkbox" name="features[]" value="{{ $feature }}" id="feature_{{ strtolower(str_replace(' ', '_', $feature)) }}" 
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       {{ in_array($feature, $selectedFeatures) ? 'checked' : '' }}>
                                <label for="feature_{{ strtolower(str_replace(' ', '_', $feature)) }}" class="ml-2 text-sm text-gray-700">{{ $feature }}</label>
                            </div>
                            @endforeach
                        </div>
                        @error('features')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description Section -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Description & Features</h3>
                    <div class="space-y-6">
                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Property Description</label>
                            <textarea name="description" id="description" rows="6" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                      placeholder="Provide a detailed description of the property...">{{ old('description', $property->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Key Features -->
                        <div>
                            <label for="key_features" class="block text-sm font-medium text-gray-700">Key Features</label>
                            <textarea name="key_features" id="key_features" rows="3" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                      placeholder="List key selling points, one per line...">{{ old('key_features', $property->key_features) }}</textarea>
                            @error('key_features')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Virtual Tour Link -->
                        <div>
                            <label for="virtual_tour_link" class="block text-sm font-medium text-gray-700">Virtual Tour Link</label>
                            <input type="url" name="virtual_tour_link" id="virtual_tour_link" value="{{ old('virtual_tour_link', $property->virtual_tour_link) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                   placeholder="https://example.com/virtual-tour">
                            @error('virtual_tour_link')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Current Images Display -->
                @if($property->images && (isset($property->images['main']) || isset($property->images['additional'])))
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Current Images</h3>
                        
                        @if(isset($property->images['main']))
                            <div class="mb-6">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Main Image</h4>
                                <img src="{{ asset('storage/' . $property->images['main']) }}" 
                                     alt="{{ $property->title }}" 
                                     class="object-cover rounded-lg border border-gray-300">
                            </div>
                        @endif

                        @if(isset($property->images['additional']) && count($property->images['additional']) > 0)
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Additional Images ({{ count($property->images['additional']) }})</h4>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4" >
                                    @foreach($property->images['additional'] as $image)
                                    <div class="relative group">
                                        <img src="{{ asset('storage/' . $image) }}" 
                                             alt="{{ $property->title }}" 
                                             class="h-auto max-w-full rounded-lg border border-gray-300">
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        <p class="text-sm text-gray-600 mt-3">Note: Uploading new images will replace the existing ones.</p>
                    </div>
                @endif

                <!-- Property Images Section -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Property Images</h3>
                    <div class="space-y-6">
                        <!-- Single Main Image -->
                        <div>
                            <label for="main_image" class="block text-sm font-medium text-gray-700">Main Property Image</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-blue-400 transition-colors">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="main_image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Upload new main image</span>
                                            <input id="main_image" name="main_image" type="file" class="sr-only" accept="image/*">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, JPEG up to 10MB</p>
                                </div>
                            </div>
                            @error('main_image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Multiple Additional Images -->
                        <div>
                            <label for="additional_images" class="block text-sm font-medium text-gray-700">Additional Images (Gallery)</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-blue-400 transition-colors">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h36v-4a2 2 0 00-2-2h-8.93a2 2 0 01-1.664-.89l-1.406-2.109a2 2 0 00-1.664-.891H16a2 2 0 00-2 2v4zm30 0v12a2 2 0 01-2 2H8a2 2 0 01-2-2V20h32z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="additional_images" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Upload new additional images</span>
                                            <input id="additional_images" name="additional_images[]" type="file" class="sr-only" accept="image/*" multiple>
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, JPEG up to 10MB each. Select multiple files at once.</p>
                                </div>
                            </div>
                            @error('additional_images')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('additional_images.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Image Preview Area -->
                        <div id="image-preview" class="hidden">
                            <h4 class="text-sm font-medium text-gray-900 mb-3">New Images Preview</h4>
                            <div id="preview-container" class="grid grid-cols-2 md:grid-cols-4 gap-4"></div>
                        </div>
                    </div>
                </div>

                <!-- Agent/Contact Information Section -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Agent/Contact Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Agent Name -->
                        <div>
                            <label for="agent_name" class="block text-sm font-medium text-gray-700">Agent Name</label>
                            <input type="text" name="agent_name" id="agent_name" value="{{ old('agent_name', $property->agent_name) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                   placeholder="Agent or Owner Name">
                            @error('agent_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Agent Phone -->
                        <div>
                            <label for="agent_phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                            @php
                                $phoneData = explode(' ', $property->agent_phone, 2);
                                $countryCode = $phoneData[0] ?? '+1';
                                $phoneNumber = $phoneData[1] ?? '';
                            @endphp
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <select name="agent_phone_country" id="agent_phone_country" 
                                        class="rounded-l-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                    <option value="+1" {{ old('agent_phone_country', $countryCode) == '+1' ? 'selected' : '' }}>🇺🇸 +1</option>
                                    <option value="+60" {{ old('agent_phone_country', $countryCode) == '+60' ? 'selected' : '' }}>🇲🇾 +60</option>
                                    <option value="+44" {{ old('agent_phone_country', $countryCode) == '+44' ? 'selected' : '' }}>🇬🇧 +44</option>
                                    <option value="+41" {{ old('agent_phone_country', $countryCode) == '+41' ? 'selected' : '' }}>🇨🇭 +41</option>
                                    <option value="+91" {{ old('agent_phone_country', $countryCode) == '+91' ? 'selected' : '' }}>🇮🇳 +91</option>
                                    <option value="+65" {{ old('agent_phone_country', $countryCode) == '+65' ? 'selected' : '' }}>🇸🇬 +65</option>
                                    <option value="+61" {{ old('agent_phone_country', $countryCode) == '+61' ? 'selected' : '' }}>🇦🇺 +61</option>
                                </select>
                                <input type="tel" name="agent_phone" id="agent_phone" value="{{ old('agent_phone', $phoneNumber) }}" 
                                       class="flex-1 rounded-r-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                       placeholder="123-4567890">
                            </div>
                            @error('agent_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('agent_phone_country')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Agent Email -->
                        <div>
                            <label for="agent_email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="agent_email" id="agent_email" value="{{ old('agent_email', $property->agent_email) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                   placeholder="agent@example.com">
                            @error('agent_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Additional Options Section -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Options</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Availability Date -->
                        <div>
                            <label for="availability_date" class="block text-sm font-medium text-gray-700">Availability Date</label>
                            <input type="date" name="availability_date" id="availability_date" 
                                   value="{{ old('availability_date', $property->availability_date ? $property->availability_date->format('Y-m-d') : '') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('availability_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Checkboxes -->
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="is_featured" value="1" id="is_featured" 
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       {{ old('is_featured', $property->is_featured) ? 'checked' : '' }}>
                                <label for="is_featured" class="ml-2 text-sm text-gray-700">Featured Property</label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" id="is_active" 
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       {{ old('is_active', $property->is_active) ? 'checked' : '' }}>
                                <label for="is_active" class="ml-2 text-sm text-gray-700">Active (Visible to public)</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6">
                    <a href="{{ route('properties.show', $property) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded transition duration-200">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded transition duration-200" onclick="debugForm(event)">
                        Update Property
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript for Image Preview -->
    <script>
        // Debug function to check form data
        function debugForm(event) {
            const mainImage = document.getElementById('main_image').files[0];
            const additionalImages = document.getElementById('additional_images').files;
            
            console.log('Form submission debug:');
            console.log('Main image:', mainImage);
            console.log('Additional images:', additionalImages);
            console.log('Form enctype:', document.querySelector('form').getAttribute('enctype'));
            
            if (!mainImage && additionalImages.length === 0) {
                console.warn('No images selected for upload');
            }
            
            // Let the form submit continue
            return true;
        }

        // Main image preview
        document.getElementById('main_image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                console.log('Main image selected:', file.name, file.size);
                const reader = new FileReader();
                reader.onload = function(e) {
                    showImagePreview([{file: file, src: e.target.result}], 'main');
                };
                reader.readAsDataURL(file);
            }
        });

        // Additional images preview
        document.getElementById('additional_images').addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            console.log('Additional images selected:', files.length, 'files');
            if (files.length > 0) {
                const previews = [];
                let loadedCount = 0;

                files.forEach((file, index) => {
                    console.log(`Additional image ${index + 1}:`, file.name, file.size);
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previews.push({file: file, src: e.target.result});
                        loadedCount++;
                        
                        if (loadedCount === files.length) {
                            showImagePreview(previews, 'additional');
                        }
                    };
                    reader.readAsDataURL(file);
                });
            }
        });

        function showImagePreview(images, type) {
            const previewContainer = document.getElementById('preview-container');
            const previewSection = document.getElementById('image-preview');
            
            // Clear existing previews for this type
            const existingPreviews = previewContainer.querySelectorAll(`[data-type="${type}"]`);
            existingPreviews.forEach(preview => preview.remove());

            images.forEach((imageData, index) => {
                const imageDiv = document.createElement('div');
                imageDiv.className = 'relative group';
                imageDiv.setAttribute('data-type', type);
                
                const img = document.createElement('img');
                img.src = imageData.src;
                img.className = 'w-full h-24 object-cover rounded-lg border border-gray-300';
                img.alt = `Preview ${type} image`;
                
                const overlay = document.createElement('div');
                overlay.className = 'absolute inset-0 bg-black bg-opacity-50 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center';
                
                const fileName = document.createElement('span');
                fileName.className = 'text-white text-xs text-center px-2';
                fileName.textContent = imageData.file.name;
                
                overlay.appendChild(fileName);
                imageDiv.appendChild(img);
                imageDiv.appendChild(overlay);
                previewContainer.appendChild(imageDiv);
            });

            // Show preview section if there are images
            if (previewContainer.children.length > 0) {
                previewSection.classList.remove('hidden');
            }
        }
    </script>
</x-admin-layout>
