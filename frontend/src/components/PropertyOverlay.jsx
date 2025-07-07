import React from "react";
import { OverlayView } from "@react-google-maps/api";

const CARD_WIDTH = 250;
const CARD_HEIGHT = 250;

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
      <div className="property-popup-card z-50">
        {/* Close button */}
        <button
          onClick={onClose}
          className="property-popup-close"
          aria-label="Close"
        >
          ×
        </button>

        {/* Card Markup */}
        <div className="details property-popup-details">
          {property.images && property.images.length > 0 ? (
            <img
              src={`http://localhost:8000/storage/${property.images[0]}`}
              alt={property.title}
              className="property-popup-image"
            />
          ) : (
            <div className="property-popup-noimage">
              <svg className="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </div>
          )}

          <div className="description property-popup-description">
            <div className="title property-popup-title">
              {property.title}
            </div>
            <div className="location property-popup-location">
              {property.location}
            </div>
            <div className="price-type property-popup-price-type">
              <span className="property-popup-pricing">
                {property.currency} {property.price?.toLocaleString()}
              </span>
            </div>    
          </div>
        </div>
      </div>
    </OverlayView>
  );
};

export default PropertyCardOverlay;