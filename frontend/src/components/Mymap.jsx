import React, { useEffect, useState, useRef, useCallback } from 'react';
import axios from 'axios';
import MapMarker from './MapMarker';
import ThreeDOverlay from './ThreeDOverlay';
import PropertyPopup from './PropertyPopup';
import useHoverStore from './store';

 const mapOptions = {
  mapId: '2d55830b3d259e649093f93b',
  center: { lat: 3.1581827058250758, lng: 101.71116104992754},
  zoom: 15,
  disableDefaultUI: true,
  clickableIcons: false,
  heading: 30,
  tilt: 25,
};

const MyMap = () => {
  const [map, setMap] = useState(null);
  const mapRef = useRef(null);
  const [properties, setProperties] = useState([]);
  const [markers, setMarkers] = useState([]);
  const [loading, setLoading] = useState(true);
  const [geocoding, setGeocoding] = useState(false);
  const [directions, setDirections] = useState(null);
  const circlesRef = useRef([]);
  const directionsRendererRef = useRef(null);
  const [markerComponents, setMarkerComponents] = useState([]);
  const animationRef = useRef(null); // Track current animation
  
  // Get hover state from store
  const { animatingPropertyId, shouldStopAnimation, clearAnimation } = useHoverStore();
  
  // Popup state
  const [selectedProperty, setSelectedProperty] = useState(null);
  const [popupPosition, setPopupPosition] = useState(null);
  const [isPopupVisible, setIsPopupVisible] = useState(false);

  useEffect(() => {
    const instance = new window.google.maps.Map(mapRef.current, mapOptions);
    setMap(instance);
  }, []);

  // Create distance circles
  useEffect(() => {
    if (!map) return;

    // Clear existing circles
    circlesRef.current.forEach(circle => circle.setMap(null));
    circlesRef.current = [];

    // Create new circles
    const circles = [
      new window.google.maps.Circle({
        strokeColor: "#8BC34A",
        strokeOpacity: 0.5,
        strokeWeight: 2,
        fillColor: "#8BC34A",
        fillOpacity: 0.01,
        map: map,
        center: mapOptions.center,
        radius: 15000, // 15km
        clickable: false,
        draggable: false,
        editable: false,
        zIndex: 3,
      }),
      new window.google.maps.Circle({
        strokeColor: "#FBC02D",
        strokeOpacity: 0.5,
        strokeWeight: 2,
        fillColor: "#FBC02D",
        fillOpacity: 0.01,
        map: map,
        center: mapOptions.center,
        radius: 30000, // 30km
        clickable: false,
        draggable: false,
        editable: false,
        zIndex: 2,
      }),
      new window.google.maps.Circle({
        strokeColor: "#FF5252",
        strokeOpacity: 0.5,
        strokeWeight: 2,
        fillColor: "#FF5252",
        fillOpacity: 0.01,
        map: map,
        center: mapOptions.center,
        radius: 45000, // 45km
        clickable: false,
        draggable: false,
        editable: false,
        zIndex: 1,
      }),
    ];

    circlesRef.current = circles;

    // Cleanup function
    return () => {
      circlesRef.current.forEach(circle => circle.setMap(null));
      circlesRef.current = [];
    };
  }, [map]);

  // Initialize DirectionsRenderer
  useEffect(() => {
    if (!map) return;

    if (!directionsRendererRef.current) {
      directionsRendererRef.current = new window.google.maps.DirectionsRenderer({
        map: map,
        polylineOptions: {
          zIndex: 50,
          strokeColor: "#1976D2",
          strokeWeight: 5,
        },
        suppressMarkers: true, // Hide A and B markers
        preserveViewport: true, // Disable autopan when showing directions
      });
    }

    return () => {
      if (directionsRendererRef.current) {
        directionsRendererRef.current.setMap(null);
        directionsRendererRef.current = null;
      }
    };
  }, [map]);

  // Clear directions function
  const clearDirections = useCallback(() => {
    setDirections(null);
    if (directionsRendererRef.current) {
      directionsRendererRef.current.set('directions', null);
    }
  }, []);  

  // Fetch properties from API
  useEffect(() => {
    const fetchProperties = async () => {
      try {
        const response = await axios.get('https://qhomesbackend.tfcmockup.com/api/properties');
        setProperties(Array.isArray(response.data) ? response.data : []);
      } catch (err) {
        console.error('Error fetching properties:', err);
        setProperties([]);
      }
      setLoading(false);
    };
    
    fetchProperties();
  }, []);

  // Geocode addresses and create markers
  useEffect(() => {
    const geocodeAddresses = async () => {
      if (!window.google || !window.google.maps || properties.length === 0) return;
      
      setGeocoding(true);
      const geocoder = new window.google.maps.Geocoder();

      // Process properties in batches to avoid rate limiting
      const batchSize = 5;
      const batches = [];
      for (let i = 0; i < properties.length; i += batchSize) {
        batches.push(properties.slice(i, i + batchSize));
      }

      const allMarkers = [];

      for (const batch of batches) {
        const markerPromises = batch.map(property =>
          new Promise(resolve => {
            
            geocoder.geocode({ address: property.full_address }, (results, status) => {
              if (status === 'OK' && results[0]) {
                const geocodedLat = results[0].geometry.location.lat();
                const geocodedLng = results[0].geometry.location.lng();
                
                console.log('Geocoded coordinates for', property.title, ':', property.full_address, geocodedLat, geocodedLng);
                
                resolve({
                  id: property.id,
                  position: {
                    lat: geocodedLat,
                    lng: geocodedLng,
                  },
                  title: property.title,
                  price: property.price,
                  currency: property.currency,
                  property: property
                });
              } else {
                resolve(null);
              }
            });
          })
        );

        const batchResults = await Promise.all(markerPromises);
        const validBatchMarkers = batchResults.filter(Boolean);
        allMarkers.push(...validBatchMarkers);

        // Add delay between batches to respect rate limits
        if (batches.length > 1) {
          await new Promise(resolve => setTimeout(resolve, 100));
        }
      }

      setMarkers(allMarkers);
      setGeocoding(false);
    };

    if (properties.length > 0) {
      geocodeAddresses();
    }
  }, [properties]);

  // Function to calculate distance between two lat/lng points using Haversine formula
  const calculateDistance = useCallback((lat1, lng1, lat2, lng2) => {
    const R = 6371000; // Earth's radius in meters
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLng = (lng2 - lng1) * Math.PI / 180;
    const a = 
      Math.sin(dLat/2) * Math.sin(dLat/2) +
      Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * 
      Math.sin(dLng/2) * Math.sin(dLng/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c; // Distance in meters
  }, []);

  // Function to determine commute category based on distance
  const getCommuteCategory = useCallback((distance) => {
    if (distance <= 15000) {
      return {
        category: 'Easy to commute',
        color: '#8BC34A',
        emoji: '🟢'
      };
    } else if (distance <= 30000) {
      return {
        category: 'Moderate to commute',
        color: '#FBC02D',
        emoji: '🟡'
      };
    } else if (distance <= 45000) {
      return {
        category: 'Hard to commute',
        color: '#FF5252',
        emoji: '🔴'
      };
    } else {
      return {
        category: 'Very far',
        color: '#9E9E9E',
        emoji: '⚫'
      };
    }
  }, []);

  // Function to calculate commute information for a property
  const calculateCommuteInfo = useCallback((markerPosition) => {
    const distance = calculateDistance(
      mapOptions.center.lat,
      mapOptions.center.lng,
      markerPosition.lat,
      markerPosition.lng
    );

    const commuteCategory = getCommuteCategory(distance);
    
    return {
      distance: Math.round(distance / 1000 * 100) / 100, // Distance in km, rounded to 2 decimal places
      distanceText: `${Math.round(distance / 1000 * 100) / 100} km`,
      travelMode: 'DRIVING', // Default travel mode
      ...commuteCategory
    };
  }, [calculateDistance, getCommuteCategory]);

  // Function to animate the appropriate circle based on marker position
  const animateCircleForMarker = useCallback((markerPosition, propertyId) => {
    if (!circlesRef.current.length) return;

    // Stop any existing animation
    if (animationRef.current) {
      animationRef.current.stop = true;
      animationRef.current = null;
    }

    const distance = calculateDistance(
      mapOptions.center.lat,
      mapOptions.center.lng,
      markerPosition.lat,
      markerPosition.lng
    );

    let circleIndex = -1;
    
    // Determine which circle to animate based on distance
    if (distance <= 15000) {
      circleIndex = 0; // Green circle (15km)
    } else if (distance <= 30000) {
      circleIndex = 1; // Yellow circle (30km)
    } else if (distance <= 45000) {
      circleIndex = 2; // Red circle (45km)
    }

    // Animate the appropriate circle
    if (circleIndex >= 0 && circlesRef.current[circleIndex]) {
      const circle = circlesRef.current[circleIndex];
      const originalStrokeOpacity = circle.get('strokeOpacity');
      const originalStrokeWeight = circle.get('strokeWeight');
      const originalFillOpacity = circle.get('fillOpacity');

      // Create animation control object
      const animationControl = { stop: false, propertyId };
      animationRef.current = animationControl;

      // Create pulse animation manually
      let pulseCount = 0;
      const maxPulses = 3;
      const pulseDuration = 2000; // 2 seconds per pulse
      
      const pulse = () => {
        if (animationControl.stop || pulseCount >= maxPulses) {
          // Reset to original values
          circle.setOptions({
            strokeOpacity: originalStrokeOpacity,
            strokeWeight: originalStrokeWeight,
            fillOpacity: originalFillOpacity
          });
          if (animationRef.current === animationControl) {
            animationRef.current = null;
            clearAnimation();
          }
          return;
        }

        const startTime = Date.now();
        
        const animate = () => {
          if (animationControl.stop) {
            // Reset to original values immediately
            circle.setOptions({
              strokeOpacity: originalStrokeOpacity,
              strokeWeight: originalStrokeWeight,
              fillOpacity: originalFillOpacity
            });
            if (animationRef.current === animationControl) {
              animationRef.current = null;
              clearAnimation();
            }
            return;
          }

          const elapsed = Date.now() - startTime;
          const progress = Math.min(elapsed / pulseDuration, 1);
          
          // Create easing function for smooth animation
          const easeInOut = t => t < 0.5 ? 2 * t * t : -1 + (4 - 2 * t) * t;
          const easedProgress = easeInOut(progress);
          
          // Calculate animated values
          const strokeOpacity = originalStrokeOpacity + (0.4 * Math.sin(easedProgress * Math.PI));
          const strokeWeight = originalStrokeWeight + (4 * Math.sin(easedProgress * Math.PI));
          const fillOpacity = originalFillOpacity + (0.07 * Math.sin(easedProgress * Math.PI));
          
          circle.setOptions({
            strokeOpacity: Math.max(0.2, strokeOpacity),
            strokeWeight: Math.max(2, strokeWeight),
            fillOpacity: Math.max(0.01, fillOpacity)
          });
          
          if (progress < 1) {
            requestAnimationFrame(animate);
          } else {
            pulseCount++;
            setTimeout(pulse, 100); // Small delay between pulses
          }
        };
        
        requestAnimationFrame(animate);
      };
      
      pulse();
    }
  }, [calculateDistance, clearAnimation]);

  // Trigger circle animation on hover
  useEffect(() => {
    if (animatingPropertyId && markers.length > 0) {
      const hoveredMarker = markers.find(marker => marker.property.id === animatingPropertyId);
      if (hoveredMarker && hoveredMarker.position) {
        console.log('Starting animation for property:', animatingPropertyId);
        animateCircleForMarker(hoveredMarker.position, animatingPropertyId);
      }
    }
  }, [animatingPropertyId, markers, animateCircleForMarker]);

  // Stop animation when shouldStopAnimation is true
  useEffect(() => {
    if (shouldStopAnimation && animationRef.current) {
      console.log('Stopping animation for property:', animationRef.current.propertyId);
      animationRef.current.stop = true;
    }
  }, [shouldStopAnimation]);

  // Handle marker clicks for directions and popup
  const handleMarkerClick = useCallback((markerData) => {
    // Calculate commute info for this property
    const commuteInfo = calculateCommuteInfo(markerData.position);
    console.log('MyMap - Calculated commute info:', commuteInfo);
    
    // Show popup with commute info
    const propertyWithCommute = {...markerData.property, commuteInfo};
    console.log('MyMap - Property with commute:', propertyWithCommute);
    setSelectedProperty(propertyWithCommute);
    setPopupPosition(markerData.position);
    setIsPopupVisible(true);
    
    // Clear previous directions
    clearDirections();
    
    // Fetch directions from center to this property
    const directionsService = new window.google.maps.DirectionsService();
    
    directionsService.route(
      {
        origin: mapOptions.center,
        destination: markerData.position,
        travelMode: window.google.maps.TravelMode.DRIVING,
      },
      (result, status) => {
        if (status === "OK" && result) {
          setDirections(result);
          if (directionsRendererRef.current) {
            directionsRendererRef.current.setDirections(result);
          }
        } else {
          console.error('Directions request failed due to ' + status);
        }
      }
    );
  }, [clearDirections, calculateCommuteInfo]);

  // Handle popup close
  const handlePopupClose = useCallback(() => {
    setIsPopupVisible(false);
    setSelectedProperty(null);
    setPopupPosition(null);
  }, []);

  // Create marker components data
  useEffect(() => {
    if (!map || markers.length === 0) return;

    // Calculate commute info for all markers
    const commuteData = {};
    markers.forEach(markerData => {
      commuteData[markerData.id] = calculateCommuteInfo(markerData.position);
    });

    // Create marker component data
    const markerComponentsData = markers.map(markerData => ({
      id: markerData.id,
      position: markerData.position,
      title: markerData.title,
      property: markerData.property,
      mapCenter: mapOptions.center,
      commuteInfo: commuteData[markerData.id] // Add commute info to each marker
    }));

    setMarkerComponents(markerComponentsData);

  }, [map, markers, calculateCommuteInfo]);  


  return (
    <>
    <div ref={mapRef} className='map-container'>
    </div>
    
    {/* 3D WebGL Overlay */}
    {map && <ThreeDOverlay map={map} />}
    
    {/* Property Popup */}
    {isPopupVisible && popupPosition && selectedProperty && (
      <PropertyPopup
        map={map}
        position={popupPosition}
        property={selectedProperty}
        isVisible={isPopupVisible}
        onClose={handlePopupClose}
        commuteInfo={selectedProperty.commuteInfo}
      />
    )}
    
    {/* Render MapMarker components */}
    {markerComponents.map((markerData) => (
      <MapMarker
        key={markerData.id}
        map={map}
        position={markerData.position}
        title={markerData.title}
        property={markerData.property}
        mapCenter={markerData.mapCenter}
        commuteInfo={markerData.commuteInfo}
        onClick={handleMarkerClick}
      />
    ))}
    
    {/* Status overlay positioned at top-left corner */}
    {(loading || geocoding) && (
      <div style={{
        position: 'absolute',
        top: '10px',
        left: '10px',
        background: 'rgba(0, 0, 0, 0.7)',
        color: 'white',
        padding: '8px 12px',
        borderRadius: '4px',
        fontSize: '14px',
        fontFamily: 'Arial, sans-serif',
        zIndex: 1000,
        pointerEvents: 'none'
      }}>
        {loading ? 'Loading properties...' : 
         geocoding ? `Geocoding addresses... (${markers.length} completed)` : 
         `${markers.length} properties loaded`}
      </div>
    )}

    {map && markers.length > 0 && (
      <div style={{
        position: 'absolute',
        bottom: '10px',
        left: '10px',
        background: 'rgba(0, 0, 0, 0.7)',
        color: 'white',
        padding: '8px 12px',
        borderRadius: '4px',
        fontSize: '14px',
        fontFamily: 'Arial, sans-serif',
        zIndex: 1000,
        display: 'flex',
        alignItems: 'center',
        gap: '10px'
      }}>
        <span style={{ pointerEvents: 'none' }}>
          {markers.length} properties loaded
        </span>
        {directions && (
          <button 
            onClick={clearDirections}
            style={{
              background: '#ff4444',
              color: 'white',
              border: 'none',
              padding: '4px 8px',
              borderRadius: '3px',
              fontSize: '12px',
              cursor: 'pointer'
            }}
          >
            Clear Route
          </button>
        )}
      </div>
    )}
    </>
  );
};

export default MyMap;