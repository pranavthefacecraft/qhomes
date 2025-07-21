import React, { useState, useEffect } from 'react';
import axios from 'axios';

const APITest = () => {
  const [testResults, setTestResults] = useState([]);
  const [loading, setLoading] = useState(false);

  const addResult = (message, type = 'info') => {
    const timestamp = new Date().toLocaleTimeString();
    setTestResults(prev => [...prev, { message, type, timestamp }]);
  };

  const clearResults = () => {
    setTestResults([]);
  };

  const testBasicConnection = async () => {
    setLoading(true);
    addResult('🔄 Testing basic connection to backend...', 'loading');
    
    try {
      const response = await axios.get('https://qhomesbackend.tfcmockup.com/api/properties');
      
      if (response.status === 200 && response.data) {
        addResult(`✅ Connection successful! Found ${Array.isArray(response.data) ? response.data.length : '?'} properties`, 'success');
        addResult(`Response headers: ${JSON.stringify(response.headers, null, 2)}`, 'info');
      } else {
        addResult(`⚠️ Unexpected response: ${response.status}`, 'warning');
      }
    } catch (error) {
      addResult(`❌ Connection failed: ${error.message}`, 'error');
      if (error.response) {
        addResult(`Response status: ${error.response.status}`, 'error');
        addResult(`Response data: ${JSON.stringify(error.response.data)}`, 'error');
      }
    }
    setLoading(false);
  };

  const testNetworkConnectivity = async () => {
    setLoading(true);
    addResult('🔄 Testing network connectivity...', 'loading');
    
    try {
      // Test if the domain is reachable
      const response = await fetch('https://qhomesbackend.tfcmockup.com', {
        method: 'HEAD',
        mode: 'no-cors'
      });
      addResult('✅ Backend domain is reachable', 'success');
    } catch (error) {
      addResult(`❌ Backend domain unreachable: ${error.message}`, 'error');
    }
    
    try {
      // Test API endpoint directly
      const response = await fetch('https://qhomesbackend.tfcmockup.com/api/properties');
      addResult(`API endpoint response: ${response.status} ${response.statusText}`, 
        response.ok ? 'success' : 'error');
    } catch (error) {
      addResult(`❌ API endpoint failed: ${error.message}`, 'error');
    }
    
    setLoading(false);
  };

  const testCORSHeaders = async () => {
    setLoading(true);
    addResult('🔄 Testing CORS configuration...', 'loading');
    
    try {
      const response = await fetch('https://qhomesbackend.tfcmockup.com/api/properties', {
        method: 'OPTIONS'
      });
      
      const corsHeaders = {
        'Access-Control-Allow-Origin': response.headers.get('Access-Control-Allow-Origin'),
        'Access-Control-Allow-Methods': response.headers.get('Access-Control-Allow-Methods'),
        'Access-Control-Allow-Headers': response.headers.get('Access-Control-Allow-Headers')
      };
      
      addResult('CORS Headers:', 'info');
      addResult(JSON.stringify(corsHeaders, null, 2), 'info');
      
    } catch (error) {
      addResult(`❌ CORS test failed: ${error.message}`, 'error');
    }
    
    setLoading(false);
  };

  const runAllTests = async () => {
    clearResults();
    await testNetworkConnectivity();
    await testCORSHeaders();
    await testBasicConnection();
  };

  useEffect(() => {
    // Auto-run tests on component mount
    runAllTests();
  }, []);

  return (
    <div className="p-6 max-w-4xl mx-auto">
      <h1 className="text-3xl font-bold mb-6">🏠 QHomes API Testing Dashboard</h1>
      
      <div className="mb-6">
        <h2 className="text-xl font-semibold mb-4">Backend URL: <code className="bg-gray-100 px-2 py-1 rounded">https://qhomesbackend.tfcmockup.com</code></h2>
      </div>

      <div className="flex space-x-4 mb-6">
        <button 
          onClick={testBasicConnection}
          disabled={loading}
          className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 disabled:opacity-50"
        >
          Test Connection
        </button>
        <button 
          onClick={testNetworkConnectivity}
          disabled={loading}
          className="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 disabled:opacity-50"
        >
          Test Network
        </button>
        <button 
          onClick={testCORSHeaders}
          disabled={loading}
          className="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 disabled:opacity-50"
        >
          Test CORS
        </button>
        <button 
          onClick={runAllTests}
          disabled={loading}
          className="bg-orange-600 text-white px-4 py-2 rounded hover:bg-orange-700 disabled:opacity-50"
        >
          Run All Tests
        </button>
        <button 
          onClick={clearResults}
          className="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700"
        >
          Clear
        </button>
      </div>

      <div className="border rounded-lg p-4 bg-gray-50 max-h-96 overflow-y-auto">
        <h3 className="text-lg font-semibold mb-4">Test Results:</h3>
        {testResults.length === 0 ? (
          <p className="text-gray-500">No test results yet...</p>
        ) : (
          testResults.map((result, index) => (
            <div
              key={index}
              className={`mb-2 p-2 rounded ${
                result.type === 'success' ? 'bg-green-100 text-green-800' :
                result.type === 'error' ? 'bg-red-100 text-red-800' :
                result.type === 'warning' ? 'bg-yellow-100 text-yellow-800' :
                result.type === 'loading' ? 'bg-blue-100 text-blue-800' :
                'bg-gray-100 text-gray-800'
              }`}
            >
              <div className="flex justify-between items-start">
                <span className="flex-1">{result.message}</span>
                <span className="text-xs opacity-75 ml-2">{result.timestamp}</span>
              </div>
            </div>
          ))
        )}
      </div>

      <div className="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded">
        <h4 className="font-semibold text-yellow-800 mb-2">🔧 Common Issues & Solutions:</h4>
        <ul className="text-sm text-yellow-700 space-y-1">
          <li>• <strong>CORS Error:</strong> Backend needs to allow your frontend domain</li>
          <li>• <strong>Network Error:</strong> Check if backend server is running</li>
          <li>• <strong>404 Error:</strong> API endpoint might have changed</li>
          <li>• <strong>500 Error:</strong> Backend server error - check Laravel logs</li>
        </ul>
      </div>
    </div>
  );
};

export default APITest;
