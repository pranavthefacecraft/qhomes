import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import axios from 'axios';
import { GrFavorite } from 'react-icons/gr'
import { IoShareSocialSharp } from "react-icons/io5";

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
    <>
    

    {properties.map((property) => (
    <div 
     className={`details ${hoveredPropertyId === property.id ? 'card-hovered' : ''}`}
     key={property.id}
     onMouseEnter={() => setHoveredProperty(property.id)}
     onMouseLeave={clearHoveredProperty}
    >
   {/* Image */}
     <div className="image">
       {property.images && property.images.length > 0 ? (
        <>
         <div className="image-wrapper">

          <div className="card-icons">
            <img src='/heart.svg' alt="Bedrooms" className="icon" />
            <img src='/Frame.svg' alt="Bedrooms" className="icon" />
          </div>

          {/* <img
             src={`https://qhomesbackend.tfcmockup.com/storage/${property.images[0]}`}
             alt={property.title}
             className=""
          /> */}
          <Carousel
           data={property.images.map((img, index) => ({
             src: `https://qhomesbackend.tfcmockup.com/storage/${img}`,
             alt: `${property.title} - Image ${index + 1}`
           }))}
          />

          <div className="label">
            <span className="label-price">{property.display_price || formatPrice(property.price, property.currency)}</span>
            <span className="label-type">{property.type}</span>
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
    <div className="description">

      <div className="title">{property.title}</div>
      <div className="location">
        <img src='/location.svg' alt="Bedrooms" className="location-svg" />
        <div className="location-text">{property.location}</div>
      </div>
      <div className="features">
        <div className="feature">
          <img src='/home.svg' alt="Bedrooms" className="icon" />
          <div className="feature-text">{property.bathrooms} m²</div>
        </div>
        <div className="feature">
          <img src='/bed.svg' alt="Bedrooms" className="icon" />
          <div className="feature-text">{property.bathrooms}</div>
        </div>
        <div className="feature">
          <img src='/shower.svg' alt="Bedrooms" className="icon" />
          <div className="feature-text">{property.bathrooms}</div>
        </div>
      </div>
      

    </div>
    </Link>


    </div>
    ))}
    </>
  );
};

export default Cards;