import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import axios from 'axios';

import { Carousel } from './ImageSlider';

import useHoverStore from './store';



const Cards = () => {

  const [properties, setProperties] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  const { 
  hoveredPropertyId, 
  setHoveredProperty, 
  clearHoveredProperty 
  } = useHoverStore();


  useEffect(() => {
    fetchProperties();
  }, []);

  const fetchProperties = async () => {
    try {
      setLoading(true);
      setError(null);
      console.log('Fetching properties from API...');
      
      // Update this URL to match your Laravel backend URL
      const response = await axios.get('https://qhomesbackend.tfcmockup.com/api/properties');
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
    <div className="space-y-6">
    {properties.map((property) => (
    <div 
     className={`bg-white rounded-lg shadow-lg overflow-hidden border transition-all duration-200 ${hoveredPropertyId === property.id ? 'shadow-xl transform scale-105' : 'shadow-md'}`}
     key={property.id}
     onMouseEnter={() => setHoveredProperty(property.id)}
     onMouseLeave={clearHoveredProperty}
    >
   {/* Image */}
     <div className="relative h-64 bg-gray-200">
       {property.images && property.images.length > 0 ? (
        <>
         <div className="relative w-full h-full">

          <div className="absolute top-4 right-4 flex space-x-2 z-10">
            <div className="w-8 h-8 bg-white bg-opacity-80 rounded-full flex items-center justify-center hover:bg-opacity-100 cursor-pointer">
              <img src='/heart.svg' alt="Favorite" className="w-4 h-4" />
            </div>
            <div className="w-8 h-8 bg-white bg-opacity-80 rounded-full flex items-center justify-center hover:bg-opacity-100 cursor-pointer">
              <img src='/frame.svg' alt="Share" className="w-4 h-4" />
            </div>
          </div>

          <Carousel
           data={property.images.map((img, index) => ({
             src: `https://qhomesbackend.tfcmockup.com/storage/${img}`,
             alt: `${property.title} - Image ${index + 1}`
           }))}
          />

          <div className="absolute bottom-4 left-4">
            <div className="bg-white bg-opacity-90 rounded-lg px-3 py-2">
              <span className="text-lg font-bold text-gray-900">{property.display_price || formatPrice(property.price, property.currency)}</span>
              <div className="text-sm text-gray-600">{property.type}</div>
            </div>
          </div>

         </div>
        </>  
       ) : (
         <div className="w-full h-full flex items-center justify-center text-gray-500">
           <svg className="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
             <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
           </svg>
         </div>
       )}
       
     </div>
    
    
  
    {/* Description Section */}
    <Link to={`/property/${property.id}`}>
    <div className="p-6">

      <h3 className="text-xl font-semibold text-gray-900 mb-2">{property.title}</h3>
      <div className="flex items-center mb-4 text-gray-600">
        <img src='/location.svg' alt="Location" className="w-4 h-4 mr-2" />
        <span className="text-sm">{property.location}</span>
      </div>
      <div className="flex space-x-6 text-sm text-gray-600">
        <div className="flex items-center">
          <img src='/home.svg' alt="Area" className="w-4 h-4 mr-1" />
          <span>{property.bathrooms} m²</span>
        </div>
        <div className="flex items-center">
          <img src='/bed.svg' alt="Bedrooms" className="w-4 h-4 mr-1" />
          <span>{property.bathrooms}</span>
        </div>
        <div className="flex items-center">
          <img src='/shower.svg' alt="Bathrooms" className="w-4 h-4 mr-1" />
          <span>{property.bathrooms}</span>
        </div>
      </div>

    </div>
    </Link>

    </div>
    ))}
    </div>
  );
};

export default Cards;