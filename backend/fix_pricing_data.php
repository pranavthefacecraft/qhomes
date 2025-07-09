<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Property Pricing Diagnosis ===\n\n";

// Check all properties and their pricing data
$properties = \App\Models\Property::all();

foreach ($properties as $property) {
    echo "Property ID: {$property->id}\n";
    echo "Title: {$property->title}\n";
    echo "Status: {$property->status}\n";
    echo "Old Price field: " . ($property->price ?? 'NULL') . "\n";
    echo "Sale Price: " . ($property->sale_price ?? 'NULL') . "\n";
    echo "Price per Month: " . ($property->price_per_month ?? 'NULL') . "\n";
    echo "Price per Day: " . ($property->price_per_day ?? 'NULL') . "\n";
    echo "Currency: " . ($property->currency ?? 'NULL') . "\n";
    echo "Formatted Price: {$property->formatted_price}\n";
    echo "Display Price: {$property->display_price}\n";
    echo "---\n";
}

echo "\n=== Fixing Pricing Data ===\n\n";

// Fix pricing data for existing properties
foreach ($properties as $property) {
    $updated = false;
    
    // If it's a sale property and has old price but no sale_price
    if (in_array($property->status, ['for_sale', 'sold']) && $property->price > 0 && !$property->sale_price) {
        $property->sale_price = $property->price;
        $updated = true;
        echo "Fixed sale price for: {$property->title} - Set sale_price to {$property->price}\n";
    }
    
    // If it's a rental property and has old price but no monthly price
    if (in_array($property->status, ['for_rent', 'rented']) && $property->price > 0 && !$property->price_per_month) {
        $property->price_per_month = $property->price;
        $updated = true;
        echo "Fixed rental price for: {$property->title} - Set price_per_month to {$property->price}\n";
    }
    
    // Set default currency if missing
    if (!$property->currency) {
        $property->currency = 'USD';
        $updated = true;
        echo "Set currency for: {$property->title}\n";
    }
    
    if ($updated) {
        $property->save();
    }
}

echo "\n=== Updated Property Pricing ===\n\n";

// Check properties again after update
$properties = \App\Models\Property::all();
foreach ($properties as $property) {
    echo "Property: {$property->title}\n";
    echo "Status: {$property->status}\n";
    echo "Formatted Price: {$property->formatted_price}\n";
    echo "Display Price: {$property->display_price}\n";
    echo "---\n";
}

echo "\nPricing fix completed!\n";
