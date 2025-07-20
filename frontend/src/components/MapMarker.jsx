import { useEffect, useRef } from 'react';

const MapMarker = ({ map, position, title = "Property Location", icon = null, property = null }) => {
  const markerRef = useRef(null);

  useEffect(() => {
    if (!map || !position) return;

    // Create marker
    const marker = new window.google.maps.Marker({
      position: position,
      map: map,
      title: title,
      icon: icon || {
        url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(`
          <svg width="32" height="40" viewBox="0 0 32 40" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M16 0C7.16344 0 0 7.16344 0 16C0 24.8366 16 40 16 40C16 40 32 24.8366 32 16C32 7.16344 24.8366 0 16 0Z" fill="#FF4444"/>
            <circle cx="16" cy="16" r="8" fill="white"/>
            <circle cx="16" cy="16" r="4" fill="#FF4444"/>
          </svg>
        `),
        scaledSize: new window.google.maps.Size(32, 40),
        anchor: new window.google.maps.Point(16, 40)
      },
      animation: window.google.maps.Animation.DROP,
    });

    // Create enhanced info window content
    const createInfoContent = () => {
      if (property) {
        return `
          <div style="padding: 15px; font-family: Arial, sans-serif; max-width: 300px;">
            <h3 style="margin: 0 0 10px 0; color: #333; font-size: 18px; font-weight: bold;">
              ${property.title || 'Property'}
            </h3>
            <div style="margin-bottom: 10px;">
              <span style="font-size: 16px; font-weight: bold; color: #2196F3;">
                ${property.currency || ''}${property.price || 'Price not available'}
              </span>
            </div>
            <div style="margin-bottom: 8px; color: #666; font-size: 14px;">
              <strong>Address:</strong><br>
              ${property.full_address || 'Address not available'}
            </div>
            ${property.bedrooms ? `
              <div style="margin-bottom: 5px; color: #666; font-size: 14px;">
                <strong>Bedrooms:</strong> ${property.bedrooms}
              </div>
            ` : ''}
            ${property.bathrooms ? `
              <div style="margin-bottom: 5px; color: #666; font-size: 14px;">
                <strong>Bathrooms:</strong> ${property.bathrooms}
              </div>
            ` : ''}
            ${property.sqft ? `
              <div style="margin-bottom: 5px; color: #666; font-size: 14px;">
                <strong>Size:</strong> ${property.sqft} sqft
              </div>
            ` : ''}
            <div style="margin-top: 10px; padding-top: 8px; border-top: 1px solid #eee; font-size: 12px; color: #888;">
              Lat: ${position.lat.toFixed(6)}, Lng: ${position.lng.toFixed(6)}
            </div>
          </div>
        `;
      } else {
        return `
          <div style="padding: 10px; font-family: Arial, sans-serif;">
            <h3 style="margin: 0 0 8px 0; color: #333; font-size: 16px;">${title}</h3>
            <p style="margin: 0; color: #666; font-size: 14px;">
              Lat: ${position.lat.toFixed(6)}<br>
              Lng: ${position.lng.toFixed(6)}
            </p>
          </div>
        `;
      }
    };

    // Create info window
    const infoWindow = new window.google.maps.InfoWindow({
      content: createInfoContent(),
      disableAutoPan: true // Prevents automatic panning when opening the info window
    });

    // Add click listener to marker
    marker.addListener('click', () => {
      infoWindow.open(map, marker);
    });

    markerRef.current = marker;

    // Cleanup function
    return () => {
      if (markerRef.current) {
        markerRef.current.setMap(null);
        markerRef.current = null;
      }
    };
  }, [map, position, title, icon, property]);

  // This component doesn't render anything visible in React
  return null;
};

export default MapMarker;
