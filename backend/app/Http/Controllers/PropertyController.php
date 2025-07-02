<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $properties = Property::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('properties.index', compact('properties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('properties.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:house,apartment,condo,townhouse,commercial,land,other',
            'status' => 'required|in:for_sale,for_rent,sold,rented,draft',
            'price' => 'required|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'nullable|string|max:100',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|numeric|min:0',
            'square_feet' => 'nullable|integer|min:0',
            'lot_size' => 'nullable|integer|min:0',
            'year_built' => 'nullable|integer|min:1800|max:' . date('Y'),
            'features' => 'nullable|array',
            'description' => 'nullable|string',
            'key_features' => 'nullable|string',
            'virtual_tour_link' => 'nullable|url',
            'agent_name' => 'nullable|string|max:100',
            'agent_phone' => 'nullable|string|max:20',
            'agent_phone_country' => 'nullable|string|max:5',
            'agent_email' => 'nullable|email',
            'availability_date' => 'nullable|date',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
            'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:10240'
        ]);

        // Handle image uploads
        $images = [];
        
        // Handle main image
        if ($request->hasFile('main_image')) {
            $mainImagePath = $request->file('main_image')->store('property-images', 'public');
            $images['main'] = $mainImagePath;
        }
        
        // Handle additional images
        if ($request->hasFile('additional_images')) {
            $additionalImages = [];
            foreach ($request->file('additional_images') as $image) {
                $imagePath = $image->store('property-images', 'public');
                $additionalImages[] = $imagePath;
            }
            $images['additional'] = $additionalImages;
        }

        // Combine phone country code and number
        if ($validated['agent_phone']) {
            $validated['agent_phone'] = ($validated['agent_phone_country'] ?? '+1') . ' ' . $validated['agent_phone'];
        }
        
        // Remove the separate country code field
        unset($validated['agent_phone_country']);

        $validated['user_id'] = Auth::id();
        $validated['property_id'] = 'PROP-' . strtoupper(uniqid());
        
        // Set images field (empty array if no images, or the images array if there are images)
        $validated['images'] = !empty($images) ? $images : null;

        Property::create($validated);

        return redirect()->route('properties.index')
            ->with('success', 'Property created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Property $property): View
    {
        // Increment view count
        $property->increment('views');
        
        return view('properties.show', compact('property'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Property $property): View
    {
        return view('properties.edit', compact('property'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Property $property): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:house,apartment,condo,townhouse,commercial,land,other',
            'status' => 'required|in:for_sale,for_rent,sold,rented,draft',
            'price' => 'required|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'nullable|string|max:100',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|numeric|min:0',
            'square_feet' => 'nullable|integer|min:0',
            'lot_size' => 'nullable|integer|min:0',
            'year_built' => 'nullable|integer|min:1800|max:' . date('Y'),
            'features' => 'nullable|array',
            'description' => 'nullable|string',
            'key_features' => 'nullable|string',
            'virtual_tour_link' => 'nullable|url',
            'agent_name' => 'nullable|string|max:100',
            'agent_phone' => 'nullable|string|max:20',
            'agent_phone_country' => 'nullable|string|max:5',
            'agent_email' => 'nullable|email',
            'availability_date' => 'nullable|date',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
            'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:10240'
        ]);

        // Handle image uploads if provided
        if ($request->hasFile('main_image') || $request->hasFile('additional_images')) {
            // Start with existing images
            $images = $property->images ?? [];
            
            // Handle main image
            if ($request->hasFile('main_image')) {
                $mainImagePath = $request->file('main_image')->store('property-images', 'public');
                $images['main'] = $mainImagePath;
            }
            
            // Handle additional images
            if ($request->hasFile('additional_images')) {
                $additionalImages = [];
                foreach ($request->file('additional_images') as $image) {
                    $imagePath = $image->store('property-images', 'public');
                    $additionalImages[] = $imagePath;
                }
                $images['additional'] = $additionalImages;
            }
            
            $validated['images'] = $images;
        }

        // Combine phone country code and number
        if ($validated['agent_phone']) {
            $validated['agent_phone'] = ($validated['agent_phone_country'] ?? '+1') . ' ' . $validated['agent_phone'];
        }
        
        // Remove the separate country code field
        unset($validated['agent_phone_country']);

        $property->update($validated);

        $message = 'Property updated successfully!';
        if (isset($validated['images'])) {
            $message .= ' Images have been uploaded.';
        }

        return redirect()->route('properties.show', $property)
            ->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Property $property): RedirectResponse
    {
        $property->delete();

        return redirect()->route('properties.index')
            ->with('success', 'Property deleted successfully!');
    }
}
