<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Geocoding Test - QHomes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto max-w-2xl py-8">
        <div class="bg-white rounded-lg shadow-md p-8">
            <h1 class="text-3xl font-bold text-center mb-8 text-blue-600">QHomes Geocoding Test</h1>
            
            <form id="geocodingForm" class="space-y-6">
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                    <input type="text" id="address" value="PT 29, Jalan Prof Diraja Ungku Aziz, Seksyen 13"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           placeholder="e.g., 123 Main Street">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                        <input type="text" id="city" value="Petaling Jaya"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               placeholder="e.g., New York">
                    </div>
                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-700">State</label>
                        <input type="text" id="state" value="Selangor Darul Ehsan"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               placeholder="e.g., NY">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="postal_code" class="block text-sm font-medium text-gray-700">Postal Code</label>
                        <input type="text" id="postal_code" value="46200"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               placeholder="e.g., 10001">
                    </div>
                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                        <select id="country" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="US">United States</option>
                            <option value="MY" selected>Malaysia</option>
                            <option value="CA">Canada</option>
                            <option value="GB">United Kingdom</option>
                            <option value="AU">Australia</option>
                        </select>
                    </div>
                </div>

                <button type="submit" id="geocodeBtn"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200">
                    🗺️ Get Coordinates
                </button>
            </form>

            <div id="results" class="mt-8 hidden">
                <h2 class="text-xl font-semibold mb-4 text-gray-900">Results:</h2>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Latitude</label>
                            <input type="text" id="latitude" readonly
                                   class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Longitude</label>
                            <input type="text" id="longitude" readonly
                                   class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Formatted Address</label>
                        <textarea id="formatted_address" readonly rows="2"
                                  class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm"></textarea>
                    </div>
                </div>
            </div>

            <div id="error" class="mt-4 hidden">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <span id="errorMessage"></span>
                </div>
            </div>
        </div>

        <!-- Test with Backend API -->
        <div class="bg-white rounded-lg shadow-md p-8 mt-8">
            <h2 class="text-xl font-semibold mb-4 text-gray-900">Backend API Test</h2>
            <button id="testBackendBtn"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200">
                🚀 Test Backend Geocoding API
            </button>
            
            <div id="backendResults" class="mt-4 hidden">
                <h3 class="text-lg font-medium mb-2">Backend API Response:</h3>
                <pre id="backendResponse" class="bg-gray-100 p-4 rounded-lg text-sm overflow-auto"></pre>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('geocodingForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const button = document.getElementById('geocodeBtn');
            const originalText = button.textContent;
            
            // Get form values
            const address = document.getElementById('address').value.trim();
            const city = document.getElementById('city').value.trim();
            const state = document.getElementById('state').value.trim();
            const postalCode = document.getElementById('postal_code').value.trim();
            const country = document.getElementById('country').value;
            
            if (!address || !city || !state || !postalCode) {
                showError('Please fill in all required fields');
                return;
            }
            
            // Update button state
            button.disabled = true;
            button.textContent = '🔄 Getting coordinates...';
            button.className = 'w-full bg-gray-400 text-white font-bold py-3 px-4 rounded-lg cursor-not-allowed';
            
            hideError();
            hideResults();
            
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
                
                let foundCoordinates = false;
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
                            // Show results
                            document.getElementById('latitude').value = parseFloat(data[0].lat).toFixed(8);
                            document.getElementById('longitude').value = parseFloat(data[0].lon).toFixed(8);
                            document.getElementById('formatted_address').value = data[0].display_name || fullAddress;
                            showResults();
                            
                            // Update button
                            button.textContent = `✅ Found (variation ${i + 1})!`;
                            button.className = 'w-full bg-green-600 text-white font-bold py-3 px-4 rounded-lg';
                            
                            foundCoordinates = true;
                            break;
                        }
                        
                    } catch (error) {
                        lastError = error;
                        console.error(`Error with variation ${i + 1}:`, error);
                    }
                    
                    // Add delay between requests to be respectful to the API
                    if (i < addressVariations.length - 1) {
                        await new Promise(resolve => setTimeout(resolve, 1000));
                        
                        // Update button text to show progress
                        button.textContent = `🔄 Trying variation ${i + 2}...`;
                    }
                }
                
                if (!foundCoordinates) {
                    throw new Error(lastError ? lastError.message : 'No coordinates found for any address variation');
                }
                
            } catch (error) {
                console.error('Geocoding error:', error);
                showError('Error: ' + error.message + '\n\nTips:\n- Try using a simpler address format\n- Ensure the city and state/country are correct\n- For Malaysian addresses, try using just the main road name');
            } finally {
                // Reset button after 3 seconds
                setTimeout(() => {
                    button.textContent = originalText;
                    button.className = 'w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200';
                    button.disabled = false;
                }, 3000);
            }
        });

        // Test backend API
        document.getElementById('testBackendBtn').addEventListener('click', async function() {
            const button = this;
            const originalText = button.textContent;
            
            button.disabled = true;
            button.textContent = '🔄 Testing backend...';
            button.className = 'w-full bg-gray-400 text-white font-bold py-3 px-4 rounded-lg cursor-not-allowed';
            
            try {
                const response = await fetch('/api/geocode', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        address: document.getElementById('address').value,
                        city: document.getElementById('city').value,
                        state: document.getElementById('state').value,
                        postal_code: document.getElementById('postal_code').value,
                        country: document.getElementById('country').value
                    })
                });
                
                const data = await response.json();
                
                document.getElementById('backendResponse').textContent = JSON.stringify(data, null, 2);
                document.getElementById('backendResults').classList.remove('hidden');
                
                if (data.success) {
                    button.textContent = '✅ Backend test successful!';
                    button.className = 'w-full bg-green-600 text-white font-bold py-3 px-4 rounded-lg';
                } else {
                    button.textContent = '❌ Backend test failed';
                    button.className = 'w-full bg-red-600 text-white font-bold py-3 px-4 rounded-lg';
                }
                
            } catch (error) {
                console.error('Backend test error:', error);
                document.getElementById('backendResponse').textContent = 'Error: ' + error.message;
                document.getElementById('backendResults').classList.remove('hidden');
                
                button.textContent = '❌ Backend test failed';
                button.className = 'w-full bg-red-600 text-white font-bold py-3 px-4 rounded-lg';
            } finally {
                // Reset button after 5 seconds
                setTimeout(() => {
                    button.textContent = originalText;
                    button.className = 'w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200';
                    button.disabled = false;
                }, 5000);
            }
        });

        function showResults() {
            document.getElementById('results').classList.remove('hidden');
        }

        function hideResults() {
            document.getElementById('results').classList.add('hidden');
        }

        function showError(message) {
            document.getElementById('errorMessage').textContent = message;
            document.getElementById('error').classList.remove('hidden');
        }

        function hideError() {
            document.getElementById('error').classList.add('hidden');
        }
    </script>
</body>
</html>
