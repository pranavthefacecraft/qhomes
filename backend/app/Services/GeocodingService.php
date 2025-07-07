<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeocodingService 
{
    /**
     * Get latitude and longitude for a given address using OpenStreetMap Nominatim API
     * 
     * @param string $address
     * @param string $city
     * @param string $state
     * @param string $postalCode
     * @param string $country
     * @return array|null
     */
    public function getCoordinates($address, $city = null, $state = null, $postalCode = null, $country = 'US')
    {
        try {
            // Build multiple address variations for better geocoding
            $addressVariations = $this->buildAddressVariations($address, $city, $state, $postalCode, $country);
            
            foreach ($addressVariations as $fullAddress) {
                Log::info('Trying geocoding with address: ' . $fullAddress);
                
                // Make request to Nominatim API with better parameters
                $response = Http::timeout(15)
                    ->withHeaders([
                        'User-Agent' => 'QHomes-PropertyApp/1.0 (contact@qhomes.com)'
                    ])
                    ->get('https://nominatim.openstreetmap.org/search', [
                        'q' => $fullAddress,
                        'format' => 'json',
                        'limit' => 3,
                        'addressdetails' => 1,
                        'extratags' => 1,
                        'namedetails' => 1,
                        'countrycodes' => strtolower($country)
                    ]);

                if ($response->successful() && $response->json()) {
                    $data = $response->json();
                    
                    Log::info('Geocoding response: ', $data);
                    
                    if (!empty($data) && isset($data[0]['lat']) && isset($data[0]['lon'])) {
                        return [
                            'latitude' => (float) $data[0]['lat'],
                            'longitude' => (float) $data[0]['lon'],
                            'formatted_address' => $data[0]['display_name'] ?? $fullAddress,
                            'confidence' => $this->calculateConfidence($data[0])
                        ];
                    }
                }
                
                // Add delay between requests to be respectful to the API
                sleep(1);
            }

            Log::warning('Geocoding failed for all address variations');
            return null;

        } catch (\Exception $e) {
            Log::error('Geocoding error: ' . $e->getMessage(), [
                'address' => $address,
                'city' => $city,
                'state' => $state,
                'postal_code' => $postalCode,
                'country' => $country
            ]);
            return null;
        }
    }

    /**
     * Build multiple address variations for better geocoding success
     * 
     * @param string $address
     * @param string $city
     * @param string $state
     * @param string $postalCode
     * @param string $country
     * @return array
     */
    private function buildAddressVariations($address, $city, $state, $postalCode, $country)
    {
        $variations = [];
        
        // Clean up address components
        $address = trim($address);
        $city = trim($city);
        $state = trim($state);
        $postalCode = trim($postalCode);
        $country = trim($country);
        
        // Variation 1: Full address as provided
        $parts = array_filter([$address, $city, $state, $postalCode, $country]);
        $variations[] = implode(', ', $parts);
        
        // Variation 2: Without postal code (sometimes helps with international addresses)
        $parts = array_filter([$address, $city, $state, $country]);
        $variations[] = implode(', ', $parts);
        
        // Variation 3: Just city, state, country (for general area)
        $parts = array_filter([$city, $state, $country]);
        $variations[] = implode(', ', $parts);
        
        // Variation 4: Simplified address (remove complex parts like "PT 29", "Seksyen")
        $simplifiedAddress = $this->simplifyAddress($address);
        if ($simplifiedAddress !== $address) {
            $parts = array_filter([$simplifiedAddress, $city, $state, $postalCode, $country]);
            $variations[] = implode(', ', $parts);
        }
        
        // Variation 5: For Malaysia specifically - try different state formats
        if (strtoupper($country) === 'MY' || strtolower($country) === 'malaysia') {
            $malayState = $this->normalizeStateForMalaysia($state);
            if ($malayState !== $state) {
                $parts = array_filter([$address, $city, $malayState, $postalCode, 'Malaysia']);
                $variations[] = implode(', ', $parts);
            }
        }
        
        return array_unique($variations);
    }

    /**
     * Simplify address by removing complex building/unit identifiers
     */
    private function simplifyAddress($address)
    {
        // Remove common prefixes that might confuse geocoding
        $patterns = [
            '/^(PT|LOT|UNIT|SUITE|APT)\s*\d+[A-Z]?,?\s*/i',
            '/,?\s*(Seksyen|Section)\s*\d+,?\s*/i',
            '/,?\s*(Jalan|Jln|Road|Rd)\s*/i'
        ];
        
        $simplified = $address;
        foreach ($patterns as $pattern) {
            $simplified = preg_replace($pattern, '', $simplified);
        }
        
        return trim($simplified, ', ');
    }

    /**
     * Normalize state names for Malaysia
     */
    private function normalizeStateForMalaysia($state)
    {
        $malayStates = [
            'Selangor Darul Ehsan' => 'Selangor',
            'Kuala Lumpur' => 'KL',
            'Wilayah Persekutuan Kuala Lumpur' => 'Kuala Lumpur',
            'Pulau Pinang' => 'Penang',
            'Johor Darul Takzim' => 'Johor',
            'Negeri Sembilan Darul Khusus' => 'Negeri Sembilan'
        ];
        
        return $malayStates[$state] ?? $state;
    }

    /**
     * Calculate confidence score based on result quality
     */
    private function calculateConfidence($result)
    {
        $confidence = 0.5; // Base confidence
        
        // Increase confidence if we have detailed address components
        if (isset($result['address'])) {
            $address = $result['address'];
            if (isset($address['house_number'])) $confidence += 0.1;
            if (isset($address['road'])) $confidence += 0.1;
            if (isset($address['city']) || isset($address['town'])) $confidence += 0.1;
            if (isset($address['state'])) $confidence += 0.1;
            if (isset($address['postcode'])) $confidence += 0.1;
        }
        
        return min($confidence, 1.0);
    }

    /**
     * Build full address string for geocoding
     * 
     * @param string $address
     * @param string $city
     * @param string $state
     * @param string $postalCode
     * @param string $country
     * @return string
     */
    private function buildFullAddress($address, $city, $state, $postalCode, $country)
    {
        $parts = array_filter([
            $address,
            $city,
            $state,
            $postalCode,
            $country
        ]);

        return implode(', ', $parts);
    }

    /**
     * Alternative geocoding using Google Maps API (requires API key)
     * Uncomment and configure if you prefer Google Maps over OpenStreetMap
     * 
     * @param string $address
     * @param string $city
     * @param string $state
     * @param string $postalCode
     * @param string $country
     * @return array|null
     */
    /*
    public function getCoordinatesGoogle($address, $city = null, $state = null, $postalCode = null, $country = 'US')
    {
        $apiKey = config('services.google_maps.api_key');
        
        if (!$apiKey) {
            Log::error('Google Maps API key not configured');
            return null;
        }

        try {
            $fullAddress = $this->buildFullAddress($address, $city, $state, $postalCode, $country);
            
            $response = Http::timeout(10)->get('https://maps.googleapis.com/maps/api/geocode/json', [
                'address' => $fullAddress,
                'key' => $apiKey
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['status'] === 'OK' && !empty($data['results'])) {
                    $result = $data['results'][0];
                    
                    return [
                        'latitude' => $result['geometry']['location']['lat'],
                        'longitude' => $result['geometry']['location']['lng'],
                        'formatted_address' => $result['formatted_address']
                    ];
                }
            }

            Log::warning('Google geocoding failed for address: ' . $fullAddress);
            return null;

        } catch (\Exception $e) {
            Log::error('Google geocoding error: ' . $e->getMessage());
            return null;
        }
    }
    */
}
