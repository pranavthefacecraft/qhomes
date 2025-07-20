import React, { useEffect, useState, useRef, useCallback } from 'react';
import { PerspectiveCamera,
    Scene,
    AmbientLight,
    WebGLRenderer,
    Matrix4
 } from 'three';
 import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader';
 import { MarkerClusterer } from '@googlemaps/markerclusterer';
 import axios from 'axios';

 const mapOptions = {
  mapId: process.env.REACT_APP_MAP_ID,
  center: { lat: 3.1581827058250758, lng: 101.71116104992754},
  zoom: 17,
  disableDefaultUI: true,
  clickableIcons: false,
  heading: 30,
  tilt: 25,
};

const MyMap = () => {
  const [map, setMap] = useState(null);
  const mapRef = useRef(null);
  const overlayRef = useRef(null);
  const [properties, setProperties] = useState([]);
  const [markers, setMarkers] = useState([]);
  const [loading, setLoading] = useState(true);
  const [geocoding, setGeocoding] = useState(false);
  const [directions, setDirections] = useState(null);
  const circlesRef = useRef([]);
  const markerClustererRef = useRef(null);
  const markersRef = useRef([]);
  const directionsRendererRef = useRef(null);

  useEffect(() => {
    if(!overlayRef.current) {
    const instance = new window.google.maps.Map(mapRef.current, mapOptions)
    setMap(instance);
    overlayRef.current = createOverlay(instance);
    }
  }, [])

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
        suppressMarkers: false, // Show A and B markers
      });
    }

    return () => {
      if (directionsRendererRef.current) {
        directionsRendererRef.current.setMap(null);
        directionsRendererRef.current = null;
      }
    };
  }, [map]);

  // Function to fetch and display directions
  const fetchDirections = useCallback((destination) => {
    if (!map || !window.google) return;

    const directionsService = new window.google.maps.DirectionsService();
    
    directionsService.route(
      {
        origin: mapOptions.center,
        destination: destination,
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
        category: 'Moderate',
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

  // Create clustered markers
  useEffect(() => {
    if (!map || markers.length === 0) return;

    // Clear existing markers and clusterer
    if (markerClustererRef.current) {
      markerClustererRef.current.clearMarkers();
    }
    markersRef.current.forEach(marker => marker.setMap(null));
    markersRef.current = [];

    // Create Google Maps markers
    const googleMarkers = markers.map(markerData => {
      // Calculate distance from map center to property
      const distanceFromCenter = calculateDistance(
        mapOptions.center.lat,
        mapOptions.center.lng,
        markerData.position.lat,
        markerData.position.lng
      );

      // Get commute category
      const commuteInfo = getCommuteCategory(distanceFromCenter);

      const marker = new window.google.maps.Marker({
        position: markerData.position,
        title: `${markerData.title} - ${markerData.currency}${markerData.price}`,
        icon: {
          url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(`
            <svg width="32" height="40" viewBox="0 0 32 40" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M16 0C7.16344 0 0 7.16344 0 16C0 24.8366 16 40 16 40C16 40 32 24.8366 32 16C32 7.16344 24.8366 0 16 0Z" fill="${commuteInfo.color}"/>
              <circle cx="16" cy="16" r="8" fill="white"/>
              <circle cx="16" cy="16" r="4" fill="${commuteInfo.color}"/>
            </svg>
          `),
          scaledSize: new window.google.maps.Size(32, 40),
          anchor: new window.google.maps.Point(16, 40)
        },
        animation: window.google.maps.Animation.DROP,
      });

      // Create info window
      const createInfoContent = (directionsResult = null) => {
        const property = markerData.property;
        let routeInfo = '';
        
        if (directionsResult) {
          const leg = directionsResult.routes[0]?.legs[0];
          if (leg) {
            routeInfo = `
              <div style="margin-top: 10px; padding-top: 8px; border-top: 1px solid #2196F3;">
                <div style="color: #1976D2; font-weight: bold; margin-bottom: 5px; font-size: 14px;">
                  🚗 Route from Center
                </div>
                <div style="color: #333; font-size: 13px; margin-bottom: 3px;">
                  <strong>Distance:</strong> ${leg.distance?.text || 'N/A'}
                </div>
                <div style="color: #333; font-size: 13px; margin-bottom: 3px;">
                  <strong>Duration:</strong> ${leg.duration?.text || 'N/A'}
                </div>
                <div style="color: #666; font-size: 12px;">
                  Travel Mode: Driving
                </div>
              </div>
            `;
          }
        }
        
        return `
          <div style="padding: 15px; font-family: Arial, sans-serif; max-width: 320px;">
            <h3 style="margin: 0 0 10px 0; color: #333; font-size: 18px; font-weight: bold;">
              ${property.title || 'Property'}
            </h3>
            <div style="margin-bottom: 10px;">
              <span style="font-size: 16px; font-weight: bold; color: #2196F3;">
                ${property.currency || ''}${property.price || 'Price not available'}
              </span>
            </div>
            <div style="margin-bottom: 10px; padding: 8px; background-color: ${commuteInfo.color}15; border-left: 4px solid ${commuteInfo.color}; border-radius: 4px;">
              <div style="color: ${commuteInfo.color}; font-weight: bold; font-size: 14px;">
                ${commuteInfo.emoji} ${commuteInfo.category}
              </div>
              <div style="color: #666; font-size: 12px; margin-top: 2px;">
                ${(distanceFromCenter / 1000).toFixed(1)} km from center
              </div>
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
            ${routeInfo}
            <div style="margin-top: 10px; padding-top: 8px; border-top: 1px solid #eee; font-size: 12px; color: #888;">
              Lat: ${markerData.position.lat.toFixed(6)}, Lng: ${markerData.position.lng.toFixed(6)}
            </div>
          </div>
        `;
      };

      const infoWindow = new window.google.maps.InfoWindow({
        content: createInfoContent(),
        disableAutoPan: true
      });

      marker.addListener('click', () => {
        // Clear previous directions
        clearDirections();
        
        // Show info window
        infoWindow.open(map, marker);
        
        // Fetch directions from center to this property and update info window
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
              
              // Update info window content with directions
              infoWindow.setContent(createInfoContent(result));
            } else {
              console.error('Directions request failed due to ' + status);
              // Show info window without directions
              infoWindow.setContent(createInfoContent());
            }
          }
        );
      });

      return marker;
    });

    // Store markers for cleanup
    markersRef.current = googleMarkers;

    // Create or update marker clusterer
    if (!markerClustererRef.current) {
      markerClustererRef.current = new MarkerClusterer({
        map,
        markers: googleMarkers,
        gridSize: 60,
        minimumClusterSize: 2,
      });
    } else {
      markerClustererRef.current.addMarkers(googleMarkers);
    }

    // Cleanup function
    return () => {
      if (markerClustererRef.current) {
        markerClustererRef.current.clearMarkers();
      }
      markersRef.current.forEach(marker => marker.setMap(null));
      markersRef.current = [];
    };
  }, [map, markers, fetchDirections, clearDirections, calculateDistance, getCommuteCategory]);  


  return (
    <>
    <div ref={mapRef} className='map-container'>
    </div>
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
          {markers.length} properties clustered
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


function createOverlay(map) {
  
   const overlay = new window.google.maps.WebGLOverlayView();
   let renderer, scene, camera, loader;

   overlay.onAdd = () => {

    scene = new Scene();
    camera = new PerspectiveCamera(45, window.innerWidth / window.innerHeight, 0.1, 1000);
    const light = new AmbientLight(0xffffff, 5);
    scene.add(light);

    loader = new GLTFLoader();
    loader.loadAsync('/tower.gltf').then(object => {
        const group = object.scene;
        group.scale.setScalar(5);
        group.rotation.set(Math.PI / 2, 0.5, 0);
        group.position.setZ(-120);
        group.position.setX(100);
        group.position.setY(-70);
        scene.add(group);
    }).catch( error => {
        
    })
   }

   overlay.onContextRestored = ({gl}) => {

    renderer = new WebGLRenderer({
      canvas: gl.canvas,
      context: gl,
      antialias: true,
      shadows: true,
      ...gl.getContextAttributes(),
    });
    renderer.autoClear = false;
   }

   overlay.onDraw = ({transformer}) => {
     
     if (!renderer || !scene || !camera) {
       return;
     }

     const matrix = transformer.fromLatLngAltitude({
       lat: 3.1581827058250758,
       lng: 101.71116104992754,
       altitude: 120
     });
     camera.projectionMatrix = new Matrix4().fromArray(matrix);

     overlay.requestRedraw();
     renderer.render(scene, camera);
     renderer.resetState();
   }

   overlay.setMap(map);

   return overlay;
}