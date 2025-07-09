import React from "react";
import { OverlayView } from "@react-google-maps/api";

const CARD_WIDTH = 200;
const CARD_HEIGHT = 200;

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
      <div
        className="property-popup-card property-popup-card-custom z-50"
        style={{
          borderRadius: '18px', // custom radius
          background: '#FAF7F3',    // black background
          color: '#fff',         // white text for contrast
          width: '250px',
          minHeight: '240px',
          boxShadow: '0 4px 16px rgba(0,0,0,0.25)',
          padding: 0,
          position: 'relative',
          overflow: 'hidden',
          display: 'flex',
          flexDirection: 'column'
        }}
      >
        {/* Close button */}
        <button
          onClick={onClose}
          className="property-popup-close"
          aria-label="Close"
          style={{
            background: 'rgba(255,255,255,0.2)',
            color: '#fff',
            border: 'none',
            borderRadius: '50%',
            width: '24px',
            height: '24px',
            position: 'absolute',
            top: '8px',
            right: '8px',
            zIndex: 2,
            fontSize: '18px',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            cursor: 'pointer'
          }}
        >
          ×
        </button>

        {/* Card Markup - order: image, title, location, price */}
        <div style={{ width: '100%', height: '110px', background: '#FAF7F3', borderTopLeftRadius: '18px', borderTopRightRadius: '18px', overflow: 'hidden', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
          {property.images && property.images.length > 0 ? (
            <img
              src="/image.jpg"
              alt={property.title}
              style={{
                width: '100%',
                height: '100%',
                objectFit: 'cover',
                borderTopLeftRadius: '18px',
                borderTopRightRadius: '18px'
              }}
            />
          ) : (
            <div style={{
              width: '100%',
              height: '100%',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              background: '#FAF7F3'
            }}>
              <svg className="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="#fff">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </div>
          )}
        </div>

        {/* Description section */}
        <div style={{
          padding: '16px 16px 12px 16px',
          display: 'flex',
          flexDirection: 'column',
          gap: '8px',
          flex: 1
        }}>
          <div style={{ fontSize: '1.05rem', fontWeight: 600, color: '#000', marginBottom: '2px', lineHeight: '1.25', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>
            {property.title}
          </div>
          <div style={{ fontSize: '0.92rem', color: '#bbb', marginBottom: '2px', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>
            {property.location}
          </div>
          <div style={{ fontWeight: 500, color: '#000', fontSize: '1rem', marginTop: '6px' }}>
            {property.currency} {property.price?.toLocaleString()}
          </div>
        </div>
      </div>
    </OverlayView>
  );
};

export default PropertyCardOverlay;