<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add New Property') }}
            </h2>
            <a href="{{ route('properties.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                ← Back to Properties
            </a>
        </div>
    </x-slot>



    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <form method="POST" action="{{ route('properties.store') }}" class="space-y-6" enctype="multipart/form-data">
                @csrf

                <!-- Basic Information Section -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Property Title -->
                        <div class="md:col-span-2">
                            <label for="title" class="block text-sm font-medium text-gray-700">Property Title *</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" 
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
                                <option value="house" {{ old('type') == 'house' ? 'selected' : '' }}>House</option>
                                <option value="apartment" {{ old('type') == 'apartment' ? 'selected' : '' }}>Apartment</option>
                                <option value="condo" {{ old('type') == 'condo' ? 'selected' : '' }}>Condo</option>
                                <option value="townhouse" {{ old('type') == 'townhouse' ? 'selected' : '' }}>Townhouse</option>
                                <option value="commercial" {{ old('type') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                                <option value="land" {{ old('type') == 'land' ? 'selected' : '' }}>Land</option>
                                <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Property Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
                            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required onchange="togglePriceFields()">
                                <option value="">Select Status</option>
                                <option value="for_sale" {{ old('status') == 'for_sale' ? 'selected' : '' }}>For Sale</option>
                                <option value="for_rent" {{ old('status') == 'for_rent' ? 'selected' : '' }}>For Rent</option>
                                <option value="sold" {{ old('status') == 'sold' ? 'selected' : '' }}>Sold</option>
                                <option value="rented" {{ old('status') == 'rented' ? 'selected' : '' }}>Rented</option>
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sale Price (For Sale properties) -->
                        <div id="sale-price-field" style="display: none;">
                            <label for="sale_price" class="block text-sm font-medium text-gray-700">Sale Price *</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" name="sale_price" id="sale_price" value="{{ old('sale_price') }}" 
                                       class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                       placeholder="0.00" step="0.01" min="0">
                            </div>
                            @error('sale_price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Monthly Rent Price (For Rent properties) -->
                        <div id="monthly-price-field" style="display: none;">
                            <label for="price_per_month" class="block text-sm font-medium text-gray-700">Monthly Rent *</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" name="price_per_month" id="price_per_month" value="{{ old('price_per_month') }}" 
                                       class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                       placeholder="0.00" step="0.01" min="0">
                            </div>
                            @error('price_per_month')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Daily Rent Price (For Rent properties) -->
                        <div id="daily-price-field" style="display: none;">
                            <label for="price_per_day" class="block text-sm font-medium text-gray-700">Daily Rent (Optional)</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" name="price_per_day" id="price_per_day" value="{{ old('price_per_day') }}" 
                                       class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                       placeholder="0.00" step="0.01" min="0">
                            </div>
                            @error('price_per_day')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Currency -->
                        <div>
                            <label for="currency" class="block text-sm font-medium text-gray-700">Currency</label>
                            <select name="currency" id="currency" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="USD" {{ old('currency', 'USD') == 'USD' ? 'selected' : '' }}>USD ($)</option>
                                <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                                <option value="GBP" {{ old('currency') == 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                                <option value="CAD" {{ old('currency') == 'CAD' ? 'selected' : '' }}>CAD ($)</option>
                                <option value="MYR" {{ old('currency') == 'MYR' ? 'selected' : '' }}>MYR (RM)</option>
                                <option value="CHF" {{ old('currency') == 'CHF' ? 'selected' : '' }}>CHF (₣)</option>
                                <option value="INR" {{ old('currency') == 'INR' ? 'selected' : '' }}>INR (₹)</option>
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
                                      placeholder="e.g., 123 Main Street, Suite 100" required>{{ old('address') }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- City -->
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700">City *</label>
                            <input type="text" name="city" id="city" value="{{ old('city') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                   placeholder="e.g., New York" required>
                            @error('city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- State -->
                        <div>
                            <label for="state" class="block text-sm font-medium text-gray-700">State/Province *</label>
                            <input type="text" name="state" id="state" value="{{ old('state') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                   placeholder="e.g., NY" required>
                            @error('state')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Postal Code -->
                        <div>
                            <label for="postal_code" class="block text-sm font-medium text-gray-700">Zip Code / Postal Code *</label>
                            <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code') }}" 
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
                                <option value="US" {{ old('country', 'US') == 'US' ? 'selected' : '' }}>United States</option>
                                <option value="MY" {{ old('country') == 'MY' ? 'selected' : '' }}>Malaysia</option>
                                <option value="GB" {{ old('country') == 'GB' ? 'selected' : '' }}>United Kingdom</option>
                                <option value="CH" {{ old('country') == 'CH' ? 'selected' : '' }}>Switzerland</option>
                                <option value="IN" {{ old('country') == 'IN' ? 'selected' : '' }}>India</option>
                                <option value="CA" {{ old('country') == 'CA' ? 'selected' : '' }}>Canada</option>
                                <option value="AU" {{ old('country') == 'AU' ? 'selected' : '' }}>Australia</option>
                                <option value="SG" {{ old('country') == 'SG' ? 'selected' : '' }}>Singapore</option>
                            </select>
                            @error('country')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Get Coordinates Button -->
                        <div class="md:col-span-2">
                            <button type="button" id="getCoordinatesBtn" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                                🗺️ Get Coordinates from Address
                            </button>
                            <p class="mt-2 text-sm text-gray-600">
                                Click to automatically fetch latitude and longitude based on the address above.
                            </p>
                        </div>

                        <!-- Latitude -->
                        <div>
                            <label for="latitude" class="block text-sm font-medium text-gray-700">Latitude</label>
                            <input type="number" name="latitude" id="latitude" value="{{ old('latitude') }}" step="0.00000001" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                   placeholder="e.g., 40.7128" readonly>
                            <p class="mt-1 text-xs text-gray-500">Will be populated automatically when you get coordinates</p>
                            @error('latitude')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Longitude -->
                        <div>
                            <label for="longitude" class="block text-sm font-medium text-gray-700">Longitude</label>
                            <input type="number" name="longitude" id="longitude" value="{{ old('longitude') }}" step="0.00000001" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                   placeholder="e.g., -74.0060" readonly>
                            <p class="mt-1 text-xs text-gray-500">Will be populated automatically when you get coordinates</p>
                            @error('longitude')
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
                            <input type="number" name="bedrooms" id="bedrooms" value="{{ old('bedrooms') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                   placeholder="0" min="0">
                            @error('bedrooms')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bathrooms -->
                        <div>
                            <label for="bathrooms" class="block text-sm font-medium text-gray-700">Bathrooms</label>
                            <input type="number" name="bathrooms" id="bathrooms" value="{{ old('bathrooms') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                   placeholder="0.0" step="0.5" min="0">
                            @error('bathrooms')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Square Feet -->
                        <div>
                            <label for="square_feet" class="block text-sm font-medium text-gray-700">Square Feet</label>
                            <input type="number" name="square_feet" id="square_feet" value="{{ old('square_feet') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                   placeholder="0" min="0">
                            @error('square_feet')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Lot Size -->
                        <div>
                            <label for="lot_size" class="block text-sm font-medium text-gray-700">Lot Size (sq ft)</label>
                            <input type="number" name="lot_size" id="lot_size" value="{{ old('lot_size') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                   placeholder="0" min="0">
                            @error('lot_size')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Year Built -->
                        <div>
                            <label for="year_built" class="block text-sm font-medium text-gray-700">Year Built</label>
                            <input type="number" name="year_built" id="year_built" value="{{ old('year_built') }}" 
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
                            @endphp
                            @foreach($features as $feature)
                            <div class="flex items-center">
                                <input type="checkbox" name="features[]" value="{{ $feature }}" id="feature_{{ strtolower(str_replace(' ', '_', $feature)) }}" 
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       {{ in_array($feature, old('features', [])) ? 'checked' : '' }}>
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
                            <textarea name="description" id="description" rows="10" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                      placeholder="Provide a detailed description of the property...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Key Features -->
                        <div>
                            <label for="key_features" class="block text-sm font-medium text-gray-700">Key Features</label>
                            <textarea name="key_features" id="key_features" rows="3" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                      placeholder="List key selling points, one per line...">{{ old('key_features') }}</textarea>
                            @error('key_features')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Virtual Tour Link -->
                        <div>
                            <label for="virtual_tour_link" class="block text-sm font-medium text-gray-700">Virtual Tour Link</label>
                            <input type="url" name="virtual_tour_link" id="virtual_tour_link" value="{{ old('virtual_tour_link') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                   placeholder="https://example.com/virtual-tour">
                            @error('virtual_tour_link')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

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
                                            <span>Upload main image</span>
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
                                            <span>Upload multiple images</span>
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
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Selected Images Preview</h4>
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
                            <input type="text" name="agent_name" id="agent_name" value="{{ old('agent_name', Auth::user()->name) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                   placeholder="Agent or Owner Name">
                            @error('agent_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Agent Phone -->
                        <div>
                            <label for="agent_phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <select name="agent_phone_country" id="agent_phone_country" 
                                        class="rounded-l-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                    <option value="+1" {{ old('agent_phone_country', '+1') == '+1' ? 'selected' : '' }}>🇺🇸 +1</option>
                                    <option value="+60" {{ old('agent_phone_country') == '+60' ? 'selected' : '' }}>🇲🇾 +60</option>
                                    <option value="+44" {{ old('agent_phone_country') == '+44' ? 'selected' : '' }}>🇬🇧 +44</option>
                                    <option value="+41" {{ old('agent_phone_country') == '+41' ? 'selected' : '' }}>🇨🇭 +41</option>
                                    <option value="+91" {{ old('agent_phone_country') == '+91' ? 'selected' : '' }}>🇮🇳 +91</option>
                                    <option value="+65" {{ old('agent_phone_country') == '+65' ? 'selected' : '' }}>🇸🇬 +65</option>
                                    <option value="+61" {{ old('agent_phone_country') == '+61' ? 'selected' : '' }}>🇦🇺 +61</option>
                                </select>
                                <input type="tel" name="agent_phone" id="agent_phone" value="{{ old('agent_phone') }}" 
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
                            <input type="email" name="agent_email" id="agent_email" value="{{ old('agent_email', Auth::user()->email) }}" 
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
                            <input type="date" name="availability_date" id="availability_date" value="{{ old('availability_date') }}" 
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
                                       {{ old('is_featured') ? 'checked' : '' }}>
                                <label for="is_featured" class="ml-2 text-sm text-gray-700">Featured Property</label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" id="is_active" 
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label for="is_active" class="ml-2 text-sm text-gray-700">Active (Visible to public)</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6">
                    <a href="{{ route('properties.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded transition duration-200">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded transition duration-200">
                        Create Property
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript for Image Preview -->
    <script>
        // Main image preview
        document.getElementById('main_image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
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
            if (files.length > 0) {
                const previews = [];
                let loadedCount = 0;

                files.forEach((file, index) => {
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

        // Geocoding functionality
        document.getElementById('getCoordinatesBtn').addEventListener('click', async function() {
            const button = this;
            const originalText = button.textContent;
            
            // Get address fields
            const address = document.getElementById('address').value.trim();
            const city = document.getElementById('city').value.trim();
            const state = document.getElementById('state').value.trim();
            const postalCode = document.getElementById('postal_code').value.trim();
            const country = document.getElementById('country').value || 'US';
            
            // Validate required fields
            if (!address || !city || !state || !postalCode) {
                alert('Please fill in all required address fields (Address, City, State, Postal Code) before getting coordinates.');
                return;
            }
            
            // Update button state
            button.disabled = true;
            button.textContent = '🔄 Getting coordinates...';
            button.className = 'bg-gray-400 text-white font-bold py-2 px-4 rounded cursor-not-allowed';
            
            try {
                // Try multiple address variations for better success rate
                const addressVariations = [
                    // Full address
                    [address, city, state, postalCode, country].filter(Boolean).join(', '),
                    // Without postal code
                    [address, city, state, country].filter(Boolean).join(', '),
                    // Just city and state/country
                    [city, state, country].filter(Boolean).join(', '),
                    // Simplified address (remove complex parts)
                    [address.replace(/^(PT|LOT|UNIT|SUITE|APT)\s*\d+[A-Z]?,?\s*/i, '').replace(/,?\s*(Seksyen|Section)\s*\d+,?\s*/i, ''), city, state, postalCode, country].filter(Boolean).join(', ')
                ];
                
                let coordinates = null;
                let lastError = null;
                
                for (let i = 0; i < addressVariations.length; i++) {
                    const fullAddress = addressVariations[i];
                    console.log(`Trying address variation ${i + 1}: ${fullAddress}`);
                    
                    try {
                        // Call OpenStreetMap Nominatim API with better parameters
                        const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(fullAddress)}&limit=3&addressdetails=1&countrycodes=${country.toLowerCase()}`, {
                            headers: {
                                'User-Agent': 'QHomes-PropertyApp/1.0'
                            }
                        });
                        
                        if (!response.ok) {
                            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                        }
                        
                        const data = await response.json();
                        console.log(`Response for variation ${i + 1}:`, data);
                        
                        if (data.length > 0 && data[0].lat && data[0].lon) {
                            coordinates = {
                                latitude: parseFloat(data[0].lat).toFixed(8),
                                longitude: parseFloat(data[0].lon).toFixed(8),
                                formatted_address: data[0].display_name || fullAddress,
                                variation_used: i + 1
                            };
                            break;
                        }
                        
                    } catch (error) {
                        lastError = error;
                        console.error(`Error with variation ${i + 1}:`, error);
                    }
                    
                    // Add delay between requests to be respectful to the API
                    if (i < addressVariations.length - 1) {
                        await new Promise(resolve => setTimeout(resolve, 1000));
                    }
                }
                
                if (coordinates) {
                    // Populate latitude and longitude fields
                    document.getElementById('latitude').value = coordinates.latitude;
                    document.getElementById('longitude').value = coordinates.longitude;
                    
                    // Show success message
                    button.textContent = `✅ Found (variation ${coordinates.variation_used})`;
                    button.className = 'bg-green-600 text-white font-bold py-2 px-4 rounded';
                    
                    console.log('Coordinates found:', coordinates);
                    
                    // Reset button after 3 seconds
                    setTimeout(() => {
                        button.textContent = originalText;
                        button.className = 'bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-200';
                        button.disabled = false;
                    }, 3000);
                    
                } else {
                    throw new Error(lastError ? lastError.message : 'No coordinates found for any address variation');
                }
                
            } catch (error) {
                console.error('Geocoding error:', error);
                alert('Error getting coordinates: ' + error.message + '. Please check the address and try again.\n\nTips:\n- Try using a simpler address format\n- Ensure the city and state/country are correct\n- For Malaysian addresses, try using just the main road name');
                
                // Reset button
                button.textContent = originalText;
                button.className = 'bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-200';
                button.disabled = false;
            }
        });

        // Toggle price fields based on property status
        function togglePriceFields() {
            const status = document.getElementById('status').value;
            const saleField = document.getElementById('sale-price-field');
            const monthlyField = document.getElementById('monthly-price-field');
            const dailyField = document.getElementById('daily-price-field');
            
            // Hide all fields first
            saleField.style.display = 'none';
            monthlyField.style.display = 'none';
            dailyField.style.display = 'none';
            
            // Clear required attributes
            document.getElementById('sale_price').required = false;
            document.getElementById('price_per_month').required = false;
            
            if (status === 'for_sale' || status === 'sold') {
                saleField.style.display = 'block';
                document.getElementById('sale_price').required = true;
            } else if (status === 'for_rent' || status === 'rented') {
                monthlyField.style.display = 'block';
                dailyField.style.display = 'block';
                document.getElementById('price_per_month').required = true;
            }
        }

        // Initialize price fields on page load
        document.addEventListener('DOMContentLoaded', function() {
            togglePriceFields();
        });

        // Initialize TinyMCE for description field
        tinymce.init({
            selector: '#description',
            height: 400,
            menubar: false,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'table', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | ' +
                'bold italic forecolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | table | link | preview code',
            content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }',
            branding: false,
            resize: true,
            statusbar: false,
            setup: function (editor) {
                editor.on('change', function () {
                    editor.save();
                });
            }
        });
    </script>

    <!-- TinyMCE CDN -->
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
</x-admin-layout>
