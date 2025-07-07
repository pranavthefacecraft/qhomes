<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Malaysian Address Geocoding Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto max-w-4xl py-8">
        <div class="bg-white rounded-lg shadow-md p-8">
            <h1 class="text-3xl font-bold text-center mb-8 text-blue-600">Malaysian Address Geocoding Debug</h1>
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Testing Address:</label>
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <p><strong>Address:</strong> PT 29, Jalan Prof Diraja Ungku Aziz, Seksyen 13</p>
                        <p><strong>City:</strong> Petaling Jaya</p>
                        <p><strong>State:</strong> Selangor Darul Ehsan</p>
                        <p><strong>Postal Code:</strong> 46200</p>
                        <p><strong>Country:</strong> Malaysia (MY)</p>
                    </div>
                </div>

                <button id="testBtn" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200">
                    🧪 Test All Address Variations
                </button>

                <div id="results" class="space-y-4"></div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('testBtn').addEventListener('click', async function() {
            const button = this;
            const resultsDiv = document.getElementById('results');
            
            button.disabled = true;
            button.textContent = '🔄 Testing...';
            resultsDiv.innerHTML = '';
            
            const address = 'PT 29, Jalan Prof Diraja Ungku Aziz, Seksyen 13';
            const city = 'Petaling Jaya';
            const state = 'Selangor Darul Ehsan';
            const postalCode = '46200';
            const country = 'MY';
            
            // Test different address variations
            const variations = [
                // Original full address
                `${address}, ${city}, ${state}, ${postalCode}, Malaysia`,
                
                // Without postal code
                `${address}, ${city}, ${state}, Malaysia`,
                
                // Simplified address (remove PT 29, Seksyen 13)
                `Jalan Prof Diraja Ungku Aziz, ${city}, ${state}, Malaysia`,
                
                // Just city and state
                `${city}, ${state}, Malaysia`,
                
                // With normalized state
                `${address}, ${city}, Selangor, Malaysia`,
                
                // Very simple
                `Petaling Jaya, Selangor, Malaysia`,
                
                // Alternative formatting
                `Prof Diraja Ungku Aziz Road, Petaling Jaya, Selangor, Malaysia`,
                
                // Section 13 specifically
                `Section 13, Petaling Jaya, Selangor, Malaysia`
            ];
            
            for (let i = 0; i < variations.length; i++) {
                const variation = variations[i];
                
                // Create result container
                const resultDiv = document.createElement('div');
                resultDiv.className = 'border border-gray-300 rounded-lg p-4';
                resultDiv.innerHTML = `
                    <h3 class="font-semibold text-lg mb-2">Variation ${i + 1}</h3>
                    <p class="text-gray-600 mb-2">${variation}</p>
                    <div class="status">Testing...</div>
                `;
                resultsDiv.appendChild(resultDiv);
                
                try {
                    const apiUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(variation)}&limit=3&addressdetails=1&countrycodes=my`;
                    
                    console.log(`Testing variation ${i + 1}: ${variation}`);
                    console.log(`API URL: ${apiUrl}`);
                    
                    const response = await fetch(apiUrl, {
                        headers: {
                            'User-Agent': 'QHomes-PropertyApp/1.0'
                        }
                    });
                    
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }
                    
                    const data = await response.json();
                    console.log(`Response for variation ${i + 1}:`, data);
                    
                    const statusDiv = resultDiv.querySelector('.status');
                    
                    if (data.length > 0) {
                        const result = data[0];
                        if (result.lat && result.lon) {
                            statusDiv.innerHTML = `
                                <div class="text-green-600 font-semibold">✅ SUCCESS</div>
                                <div class="mt-2 space-y-1">
                                    <p><strong>Latitude:</strong> ${result.lat}</p>
                                    <p><strong>Longitude:</strong> ${result.lon}</p>
                                    <p><strong>Display Name:</strong> ${result.display_name}</p>
                                    <p><strong>Importance:</strong> ${result.importance || 'N/A'}</p>
                                </div>
                            `;
                            resultDiv.className = 'border border-green-300 bg-green-50 rounded-lg p-4';
                        } else {
                            statusDiv.innerHTML = `<div class="text-yellow-600">⚠️ No coordinates in response</div>`;
                            statusDiv.innerHTML += `<pre class="mt-2 text-xs bg-gray-100 p-2 rounded overflow-auto">${JSON.stringify(data, null, 2)}</pre>`;
                        }
                    } else {
                        statusDiv.innerHTML = `<div class="text-red-600">❌ No results found</div>`;
                        resultDiv.className = 'border border-red-300 bg-red-50 rounded-lg p-4';
                    }
                    
                } catch (error) {
                    console.error(`Error with variation ${i + 1}:`, error);
                    
                    const statusDiv = resultDiv.querySelector('.status');
                    statusDiv.innerHTML = `<div class="text-red-600">❌ Error: ${error.message}</div>`;
                    resultDiv.className = 'border border-red-300 bg-red-50 rounded-lg p-4';
                }
                
                // Add delay between requests
                if (i < variations.length - 1) {
                    await new Promise(resolve => setTimeout(resolve, 1500));
                }
            }
            
            button.textContent = '✅ Testing Complete';
            button.className = 'w-full bg-green-600 text-white font-bold py-3 px-4 rounded-lg';
            
            setTimeout(() => {
                button.textContent = '🧪 Test All Address Variations';
                button.className = 'w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200';
                button.disabled = false;
            }, 3000);
        });
    </script>
</body>
</html>
