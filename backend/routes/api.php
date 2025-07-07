<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PropertyController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
    ]);

    $user = \App\Models\User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
    ]);

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'access_token' => $token,
        'token_type' => 'Bearer',
        'user' => $user,
    ]);
});

Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|string|email',
        'password' => 'required|string',
    ]);

    if (!\Auth::attempt($request->only('email', 'password'))) {
        return response()->json([
            'message' => 'Invalid login credentials'
        ], 401);
    }

    $user = \App\Models\User::where('email', $request->email)->firstOrFail();
    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'access_token' => $token,
        'token_type' => 'Bearer',
        'user' => $user,
    ]);
});

Route::post('/logout', function (Request $request) {
    $request->user()->currentAccessToken()->delete();
    
    return response()->json([
        'message' => 'Successfully logged out'
    ]);
})->middleware('auth:sanctum');

// Properties API routes for frontend
Route::get('/properties', [PropertyController::class, 'apiIndex']);
Route::get('/properties/{id}', [PropertyController::class, 'apiShow']);

// Geocoding test endpoint
Route::post('/geocode', function (Request $request) {
    $request->validate([
        'address' => 'required|string',
        'city' => 'nullable|string',
        'state' => 'nullable|string',
        'postal_code' => 'nullable|string',
        'country' => 'nullable|string'
    ]);

    $geocodingService = new \App\Services\GeocodingService();
    $coordinates = $geocodingService->getCoordinates(
        $request->address,
        $request->city,
        $request->state,
        $request->postal_code,
        $request->country ?? 'US'
    );

    if ($coordinates) {
        return response()->json([
            'success' => true,
            'data' => $coordinates
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Could not geocode the provided address'
        ], 422);
    }
});
 