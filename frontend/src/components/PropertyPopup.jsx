import { useEffect, useRef } from 'react';
import ReactDOM from 'react-dom';
import { Carousel } from './ImageSlider';
import useHoverStore from './store';

const PropertyPopup = ({ 
  map, 
  position, 
  property, 
  isVisible, 
  onClose,
  commuteInfo = null
}) => {
  const overlayRef = useRef(null);
  const { 
    hoveredPropertyId, 
    setHoveredProperty, 
    clearHoveredProperty 
  } = useHoverStore();

  const formatPrice = (price, currency = 'USD') => {
    return new Intl.NumberFormat('en-US', {
      style: 'currency',
      currency: currency,
    }).format(price);
  };

  // JSX Component for the popup content
  const PopupContent = () => {
    // Log commute info for debugging
    if (property?.commuteInfo) {
      console.log('PropertyPopup - Commute info for', property.title, ':', property.commuteInfo);
    }
    
    return (
    <div 
      className="property-popup-container"
      style={{
        width: '250px',
        height: 'auto',
        maxHeight: '550px',
        background: 'white',
        borderRadius: '12px',
        boxShadow: '0 8px 24px rgba(0, 0, 0, 0.15)',
        border: '1px solid #e1e5e9',
        position: 'relative',
        overflow: 'hidden',
        fontFamily: 'Arial, sans-serif'
      }}
    >
      <button
        onClick={(e) => {
          e.stopPropagation();
          if (onClose) onClose();
        }}
        style={{
          position: 'absolute',
          top: '8px',
          right: '8px',
          background: 'rgba(0, 0, 0, 0.5)',
          color: 'white',
          border: 'none',
          borderRadius: '50%',
          width: '24px',
          height: '24px',
          cursor: 'pointer',
          fontSize: '16px',
          fontWeight: 'bold',
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          zIndex: 10
        }}
      >
        ×
      </button>
      
      {property ? (
        <>
        <div 
          className={`details ${hoveredPropertyId === property.id ? 'card-hovered' : ''}`}
          onMouseEnter={() => setHoveredProperty(property.id)}
          onMouseLeave={clearHoveredProperty}
        >
          {/* Image */}
          <div className="image">
            {property.images && property.images.length > 0 ? (
              <>
                <div className="image-wrapper">
                  <div className="card-icons">
                    <img src='/heart.svg' alt="Favorite" className="icon" />
                    <img src='/frame.svg' alt="Gallery" className="icon" />
                  </div>

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
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 002 2z" />
                </svg>
              </div>
            )}
          </div>
          
          {/* Description Section */}
          <div 
            onClick={() => {
              // Navigate to property detail page
              window.location.href = `/property/${property.id}`;
            }}
            style={{ cursor: 'pointer' }}
          >
            <div className="description">
              <div className="title">{property.title}</div>
              <div className="location">
                <img src='/location.svg' alt="Location" className="location-svg" />
                <div className="location-text">{property.location}</div>
              </div>
              <div className="features">
                <div className="feature">
                  <img src='/home.svg' alt="Area" className="icon" />
                  <div className="feature-text">{property.area || property.bathrooms} m²</div>
                </div>
                <div className="feature">
                  <img src='/bed.svg' alt="Bedrooms" className="icon" />
                  <div className="feature-text">{property.bedrooms || property.bathrooms}</div>
                </div>
                <div className="feature">
                  <img src='/shower.svg' alt="Bathrooms" className="icon" />
                  <div className="feature-text">{property.bathrooms}</div>
                </div>
              </div>
              
             
            </div>
             
          </div>
          {/* Commute Information */}
              
        </div>
        {property.commuteInfo && (
         <div className="commute-info" style={{
           marginTop: '1px',
           marginBottom: '2px',
           marginLeft: '1px',
           fontWeight: '400',
           marginRight: '1px',
           width: '100%',
           height: 'auto',
           padding: '4px 2px',
           backgroundColor: '#f8f9fa',
           fontFamily: '"Roboto", sans-serif',
           fontOpticalSizing: 'auto',
           fontStyle: 'normal'
         }}>
           <div style={{
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'space-between',
              marginBottom: '6px'
            }}>
              <span style={{
                fontSize: '12px',
                fontWeight: '600',
                color: '#333'
              }}>
                Commute Info
              </span>
              <span style={{
                fontSize: '14px'
              }}>
                {property.commuteInfo.emoji}
              </span>
            </div>
            
            <div style={{
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'space-between',
              fontSize: '11px',
              color: '#666'
            }}>
              <span>Distance: {property.commuteInfo.distanceText}</span>
              <span style={{
                color: property.commuteInfo.color,
                fontWeight: '600'
              }}>
                {property.commuteInfo.category}
              </span>
            </div>
         </div>
        )}
        </>
      ) : (
        <div
          style={{
            width: '100%',
            height: '100%',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            color: '#666',
            fontSize: '14px'
          }}
        >
          Property details will be displayed here
        </div>
      )}
    </div>
  );
  };

  useEffect(() => {
    if (!map || !window.google || !isVisible) {
      // Clean up existing overlay if popup is hidden
      if (overlayRef.current) {
        overlayRef.current.setMap(null);
        overlayRef.current = null;
      }
      return;
    }

    // Create custom overlay for the popup
    class PopupOverlay extends window.google.maps.OverlayView {
      constructor(position) {
        super();
        this.position = position;
      }

      onAdd() {
        const div = document.createElement('div');
        div.style.borderStyle = 'none';
        div.style.borderWidth = '0px';
        div.style.position = 'absolute';
        div.style.zIndex = '1000';
        
        // Use ReactDOM.render for React 17
        ReactDOM.render(<PopupContent />, div);
        
        this.div = div;
        const panes = this.getPanes();
        panes.overlayMouseTarget.appendChild(div);
      }

      draw() {
        const overlayProjection = this.getProjection();
        const sw = overlayProjection.fromLatLngToDivPixel(this.position);
        
        const div = this.div;
        div.style.left = (sw.x - 125) + 'px'; // Center the popup (250px width / 2)
        div.style.top = (sw.y - 300) + 'px';  // Position above the marker (adjusting for larger height)
      }

      onRemove() {
        if (this.div) {
          ReactDOM.unmountComponentAtNode(this.div);
          this.div.parentNode.removeChild(this.div);
          this.div = null;
        }
      }
    }

    // Create and add overlay
    const overlay = new PopupOverlay(
      new window.google.maps.LatLng(position.lat, position.lng)
    );
    
    overlay.setMap(map);
    overlayRef.current = overlay;

    // Cleanup function
    return () => {
      if (overlayRef.current) {
        overlayRef.current.setMap(null);
        overlayRef.current = null;
      }
    };
  }, [map, position, property, isVisible, onClose]);

  // This component doesn't render anything visible in React's main tree
  return null;
};

export default PropertyPopup;
