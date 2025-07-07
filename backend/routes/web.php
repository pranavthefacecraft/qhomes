<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Test geocoding functionality
Route::get('/test-geocoding', function () {
    return view('test-geocoding');
});

// Debug geocoding for Malaysian address
Route::get('/debug-geocoding', function () {
    $service = new \App\Services\GeocodingService();
    $result = $service->getCoordinates(
        'PT 29, Jalan Prof Diraja Ungku Aziz, Seksyen 13',
        'Petaling Jaya',
        'Selangor Darul Ehsan',
        '46200',
        'MY'
    );
    
    return response()->json([
        'input' => [
            'address' => 'PT 29, Jalan Prof Diraja Ungku Aziz, Seksyen 13',
            'city' => 'Petaling Jaya',
            'state' => 'Selangor Darul Ehsan',
            'postal_code' => '46200',
            'country' => 'MY'
        ],
        'result' => $result,
        'logs' => 'Check Laravel logs for detailed information'
    ]);
});

// Malaysian address debug page
Route::get('/debug-malaysian', function () {
    return view('debug-malaysian-geocoding');
});

// Test API response structure
Route::get('/test-api', function () {
    $properties = \App\Models\Property::where('is_active', true)->take(3)->get();
    $controller = new \App\Http\Controllers\PropertyController();
    
    return response()->json([
        'message' => 'API Test - Property Response Structure',
        'sample_properties' => $properties->map(function ($property) {
            // Simulate the same formatting as the API
            $images = [];
            if ($property->images) {
                if (isset($property->images['main'])) {
                    $images[] = $property->images['main'];
                }
                if (isset($property->images['additional'])) {
                    $images = array_merge($images, $property->images['additional']);
                }
            }

            return [
                'id' => $property->id,
                'title' => $property->title,
                'property_type' => ucfirst($property->type),
                'listing_type' => ucwords(str_replace('_', ' ', $property->status)),
                'price' => $property->price,
                'currency' => $property->currency ?? 'USD',
                
                // Complete address information
                'address' => $property->address,
                'city' => $property->city,
                'state' => $property->state,
                'postal_code' => $property->postal_code,
                'country' => $property->country ?? 'US',
                'location' => $property->city . ', ' . $property->state,
                'full_address' => trim($property->address . ', ' . $property->city . ', ' . $property->state . ' ' . $property->postal_code . ', ' . ($property->country ?? 'US')),
                
                // Coordinates
                'latitude' => $property->latitude,
                'longitude' => $property->longitude,
                
                // Property details
                'bedrooms' => $property->bedrooms ?? 0,
                'bathrooms' => $property->bathrooms ?? 0,
                'area_size' => $property->square_feet ?? 0,
                'sqft' => $property->square_feet ?? 0,
                'type' => $property->type,
                'status' => $property->status,
                
                // Media and content
                'images' => $images,
                'description' => $property->description,
                'features' => $property->features,
                
                // Agent information
                'agent' => [
                    'name' => $property->agent_name,
                    'phone' => $property->agent_phone,
                    'email' => $property->agent_email,
                ],
                
                // Additional info
                'year_built' => $property->year_built,
                'lot_size' => $property->lot_size,
                'property_id' => $property->property_id,
                'created_at' => $property->created_at,
                'updated_at' => $property->updated_at,
            ];
        })
    ]);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Property routes
    Route::resource('properties', PropertyController::class);
    
    // Agent routes
    Route::resource('agents', AgentController::class);
});

require __DIR__.'/auth.php';
 