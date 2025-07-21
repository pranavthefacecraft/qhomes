import React from "react";
import { OverlayView } from "@react-google-maps/api";
import { Carousel } from './ImageSlider';
import { Link } from 'react-router-dom';
import { GrFavorite } from 'react-icons/gr';
import { IoShareSocialSharp } from "react-icons/io5";

const CARD_WIDTH = 220; // Slightly smaller than Cards.jsx
const CARD_HEIGHT = 250; // Adjusted height

const PropertyCardOverlay = ({ property, onClose }) => {
  if (!property) return null;

  // Offset so the card appears above the marker and centered horizontally
  const getPixelPositionOffset = (width = CARD_WIDTH, height = CARD_HEIGHT) => ({
    x: -(width / 2),
    y: -height - 20 // 20px gap above marker
  });

  return (
    <OverlayView
      position={{
        lat: property.latitude,
        lng: property.longitude,
      }}
      mapPaneName={OverlayView.OVERLAY_MOUSE_TARGET}
      getPixelPositionOffset={getPixelPositionOffset}
    >
      <div className="details" style={{ 
        width: `${CARD_WIDTH}px`, 
        transform: 'scale(0.9)', // Slightly scale down
        transformOrigin: 'top center'
      }}>
        {/* Close button */}
        <button
          onClick={onClose}
          className="property-popup-close"
          aria-label="Close"
          style={{
            position: 'absolute',
            top: '8px',
            right: '8px',
            zIndex: 10,
            background: 'rgba(78, 78, 78, 0.8)',
            border: 'none',
            borderRadius: '50%',
            width: '24px',
            height: '24px',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            cursor: 'pointer',
            fontSize: '16px'
          }}
        >
          ×
        </button>

        {/* Image */}
        <div className="image">
          {property.images && property.images.length > 0 ? (
            <>
              <div className="image-wrapper">

                <Carousel
                  data={property.images.map((img, index) => ({
                    src: `https://qhomesbackend.tfcmockup.com/storage/${img}`,
                    alt: `${property.title} - Image ${index + 1}`
                  }))}
                  style={{ height: '140px' }} // Slightly smaller height
                />

                <div className="label">
                  <span className="label-price">{property.display_price || property.price}</span>
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
              <img src='/location.svg' alt="Location" className="location-svg" style={{ marginTop: '0rem' }}/>
              <div className="location-text">{property.location}</div>
            </div>
            <div className="features" >
              <div className="feature" style={{ gap: '0.5px' }}>
                <img src='/home.svg' alt="Home" className="icon" style={{ marginTop: '0rem' }}/>
                <div className="feature-text" style={{ marginTop: '1px' }}>{property.bedrooms} m²</div>
              </div>
              <div className="feature" style={{ gap: '0.8px' }}>
                <img src='/bed.svg' alt="Bed" className="icon" style={{ marginTop: '1px' }}/>
                <div className="feature-text"  style={{ marginTop: '1px' }}>{property.bedrooms}</div>
              </div>
              <div className="feature" style={{ gap: '0.5px' }}>
                <img src='/shower.svg' alt="Bath" className="icon" style={{ marginTop: '0rem' }}/>
                <div className="feature-text"  style={{ marginTop: '1px' }}>{property.bathrooms}</div>
              </div>
            </div>
          </div>
        </Link>
      </div>
    </OverlayView>
  );
};

export default PropertyCardOverlay;