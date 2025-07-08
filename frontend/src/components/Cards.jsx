import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import axios from 'axios';

import useHoverStore from './store';



const Cards = () => {

  const [properties, setProperties] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  const { setHoveredProperty, clearHoveredProperty } = useHoverStore();


  useEffect(() => {
    fetchProperties();
  }, []);

  const fetchProperties = async () => {
    try {
      setLoading(true);
      setError(null);
      console.log('Fetching properties from API...');
      
      // Update this URL to match your Laravel backend URL
      const response = await axios.get('http://localhost:8000/api/properties');
      console.log('API Response:', response.data);
      console.log('API Response type:', typeof response.data);
      console.log('Is array?', Array.isArray(response.data));
      
      // The API should return an array of properties
      if (Array.isArray(response.data)) {
        setProperties(response.data);
        console.log('Properties set:', response.data.length, 'properties');
      } else {
        console.error('API did not return an array:', response.data);
        setError('Invalid data format received from server');
      }
    } catch (err) {
      console.error('Full error object:', err);
      if (err.response) {
        console.error('Error response data:', err.response.data);
        console.error('Error response status:', err.response.status);
        setError(`Server error: ${err.response.status} - ${err.response.statusText}`);
      } else if (err.request) {
        console.error('No response received:', err.request);
        setError('No response from server. Please check if the backend is running.');
      } else {
        console.error('Error setting up request:', err.message);
        setError(`Request error: ${err.message}`);
      }
    } finally {
      setLoading(false);
    }
  };

  const formatPrice = (price, currency = 'USD') => {
    return new Intl.NumberFormat('en-US', {
      style: 'currency',
      currency: currency,
    }).format(price);
  };


  if (loading) {
    return (
      <div className="flex justify-center items-center py-12 h-screen">
        <div className="text-center">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600 mx-auto mb-4"></div>
          <p className="text-gray-600">Loading properties...</p>
        </div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="text-center py-12">
        <p className="text-red-600 mb-4">{error}</p>
        <button 
          onClick={fetchProperties}
          className="bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-700 transition-colors"
        >
          Try Again
        </button>
      </div>
    )
  }

  if (properties.length === 0) {
    return (
      <div className="text-center py-12">
        <p className="text-gray-600 mb-4">No properties found.</p>
        <button 
          onClick={fetchProperties}
          className="bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-700 transition-colors"
        >
          Refresh
        </button>
      </div>
    );
  }

  console.log('Rendering properties:', properties.length);

  
  return (
    <>
    

    {properties.map((property) => (
      <div className="details"
       key={property.id}
       onMouseEnter={() => setHoveredProperty(property.id)}
       onMouseLeave={clearHoveredProperty}
       >
        {/* Image */}
        <Link to={`/property/${property.id}`}>
        
        <div className="image">
          {property.images && property.images.length > 0 ? (
              <img
                src={`http://localhost:8000/storage/${property.images[0]}`}
                alt={property.title}
                className="w-full h-full object-cover rounded-lg"
              />
            ) : (
              <div className="w-full h-48 flex items-center justify-center text-gray-500">
                <svg className="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>
            )}
        </div>

        </Link>

        {/* Details */}
        <div className="description">
          
            <div className="title"><Link to={`/property/${property.id}`}>{property.title}</Link></div>
          
          
          <div className="location">{property.location}</div>
          <div className="price-type">
            <div className="pricing">{formatPrice(property.price, property.currency)}</div>
            <div className="property-type">{property.property_type}</div>
          </div>
          <div className="features">
            <div className="feature flex items-center">
                <svg className="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                {property.bedrooms} 
              </div>
              <div className="feature flex items-center">
                <svg className="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                </svg>
                {property.bathrooms} 
              </div>
              <div className="feature flex items-center">
                <svg className="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 8V4m0 0h4M4 4l5 5m11-5h-4m4 0v4m0-4l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                </svg>
                {property.area_size} sqft
              </div>
          </div>
        </div>
        {/* Button */}
        <div className="view-button">
        <Link
        to={`/property/${property.id}`}
        className="button block border-primary-600 text-white hover:bg-primary-700 hover:text-white text-center text-sm font-medium"
        >
          View Property
        </Link>
        </div>
      </div>
    ))}
    </>
  );
};

export default Cards;