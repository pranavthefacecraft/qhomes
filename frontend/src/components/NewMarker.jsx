import { useEffect, useRef } from 'react';
import useHoverStore from './store';

const MapMarker = ({ map, position, title = "Property Location", icon = null, property = null, mapCenter = { lat: 3.1581827058250758, lng: 101.71116104992754 }, commuteInfo = null, onClick = null }) => {
  const markerRef = useRef(null);
  
  // Get hover state and actions from store
  const { hoveredPropertyId, setHoveredProperty, clearHoveredProperty } = useHoverStore();
  const isHovered = hoveredPropertyId === property?.id;

  // Log commute info for debugging
  if (commuteInfo && property) {
    console.log(`Commute info for ${property.title}:`, commuteInfo);
  }

  // Function to calculate distance between two lat/lng points using Haversine formula
  const calculateDistance = (lat1, lng1, lat2, lng2) => {
    const R = 6371000; // Earth's radius in meters
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLng = (lng2 - lng1) * Math.PI / 180;
    const a = 
      Math.sin(dLat/2) * Math.sin(dLat/2) +
      Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * 
      Math.sin(dLng/2) * Math.sin(dLng/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c; // Distance in meters
  };

  useEffect(() => {
    if (!map || !position) return;

    // Function to determine marker color based on commute info or distance calculation
    const getMarkerColor = () => {
      // Use passed commute info if available, otherwise calculate distance
      if (commuteInfo && commuteInfo.color) {
        return commuteInfo.color;
      }
      
      // Fallback to distance calculation if commute info not available
      const distance = calculateDistance(
        mapCenter.lat,
        mapCenter.lng,
        position.lat,
        position.lng
      );

      if (distance <= 15000) {
        return '#8BC34A'; // Green - Easy to commute
      } else if (distance <= 30000) {
        return '#FBC02D'; // Yellow - Moderate
      } else if (distance <= 45000) {
        return '#FF5252'; // Red - Hard to commute
      } else {
        return '#9E9E9E'; // Gray - Very far
      }
    };

    const markerColor = getMarkerColor();
    const displayPrice = property?.price ? `${property.currency || ''}${property.price}` : 'Price N/A';

    // Create marker with normal size initially
    const marker = new window.google.maps.Marker({
      position: position,
      map: map,
      title: "", // Empty title to prevent default tooltip
      icon: icon || {
        url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(`
          <svg width="120" height="45" viewBox="0 0 120 45" fill="none" xmlns="http://www.w3.org/2000/svg">
            <!-- Drop shadow -->
            <rect x="4" y="4" width="112" height="36" rx="8" fill="rgba(0,0,0,0.2)"/>
            
            <!-- Main tooltip background with distance-based color -->
            <rect x="2" y="2" width="112" height="36" rx="8" fill="${markerColor}" stroke="#ffffff" stroke-width="2"/>
            
            <!-- Small triangle pointer -->
            <path d="M58 38L62 42L66 38Z" fill="${markerColor}" stroke="#ffffff" stroke-width="2"/>
            
            <!-- Price text in black -->
            <text x="58" y="24" text-anchor="middle" fill="#000000" font-size="14" font-weight="600" font-family="Arial, sans-serif">${displayPrice}</text>
          </svg>
        `),
        scaledSize: new window.google.maps.Size(120, 45),
        anchor: new window.google.maps.Point(60, 42)
      },
      animation: window.google.maps.Animation.DROP,
    });

    // Function to update marker appearance with smooth CSS-like transitions
    const updateMarkerHoverState = (shouldHighlight) => {
      // Use requestAnimationFrame for smoother transitions
      requestAnimationFrame(() => {
        const scale = shouldHighlight ? 1.1 : 1;
        const shadowOpacity = shouldHighlight ? 0.4 : 0.2;
        
        // Create new icon with updated styling but same SVG structure for smoothness
        const newIcon = {
          url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(`
            <svg width="${120 * scale}" height="${45 * scale}" viewBox="0 0 120 45" fill="none" xmlns="http://www.w3.org/2000/svg">
              <!-- Drop shadow -->
              <rect x="4" y="4" width="112" height="36" rx="8" fill="rgba(0,0,0,${shadowOpacity})"/>
              
              <!-- Main tooltip background with distance-based color -->
              <rect x="2" y="2" width="112" height="36" rx="8" fill="${markerColor}" stroke="#ffffff" stroke-width="2"/>
              
              <!-- Small triangle pointer -->
              <path d="M58 38L62 42L66 38Z" fill="${markerColor}" stroke="#ffffff" stroke-width="2"/>
              
              <!-- Price text in black -->
              <text x="58" y="24" text-anchor="middle" fill="#000000" font-size="14" font-weight="600" font-family="Arial, sans-serif">${displayPrice}</text>
            </svg>
          `),
          scaledSize: new window.google.maps.Size(120 * scale, 45 * scale),
          anchor: new window.google.maps.Point(60 * scale, 42 * scale)
        };

        // Use setIcon but with optimized approach to reduce flickering
        marker.setIcon(newIcon);
      });
    };

    // Store the update function on the marker for external access
    marker.updateHoverState = updateMarkerHoverState;

    // Add click listener to marker (no info window, just custom onClick)
    marker.addListener('click', () => {
      if (onClick) {
        onClick({ position, property });
      }
      // Removed info window display
    });

    // Add hover listeners to marker
    marker.addListener('mouseover', () => {
      if (property?.id) {
        setHoveredProperty(property.id);
      }
    });

    marker.addListener('mouseout', () => {
      clearHoveredProperty();
    });

    markerRef.current = marker;

    // Cleanup function
    return () => {
      if (markerRef.current) {
        markerRef.current.setMap(null);
        markerRef.current = null;
      }
    };
  }, [map, position, title, icon, property, mapCenter, commuteInfo, onClick, setHoveredProperty, clearHoveredProperty]);

  // Separate useEffect to handle hover state changes without recreating marker
  useEffect(() => {
    if (markerRef.current && markerRef.current.updateHoverState) {
      markerRef.current.updateHoverState(isHovered);
    }
  }, [isHovered]);

  // This component doesn't render anything visible in React
  return null;
};

export default MapMarker;
