import React, { useState, useMemo, useEffect } from 'react';
import { GoogleMap } from '@react-google-maps/api';
import NewMarker from './NewMarker';
import axios from 'axios';

import PropertyCardOverlay from './PropertyOverlay';

import useHoverStore from './store';

const DEFAULT_CENTER = { lat: 3.1319, lng: 101.6841 };


const UpdatedMap = () => {
  // State
  const [mapRef, setMapRef] = useState(null);
  const [properties, setProperties] = useState([]);
  const [markers, setMarkers] = useState([]);
  const [center, setCenter] = useState(DEFAULT_CENTER);
  const [loading, setLoading] = useState(true);

  const [selectedProperty, setSelectedProperty] = useState(null);

  const { 
  hoveredPropertyId, 
  setHoveredProperty, 
  clearHoveredProperty 
  } = useHoverStore();


  // Fetch properties from API
  useEffect(() => {
    const fetchProperties = async () => {
      try {
        const response = await axios.get('https://qhomesbackend.tfcmockup.com/api/properties');
        setProperties(Array.isArray(response.data) ? response.data : []);
        
      } catch (err) {
        setProperties([]);
      }
      setLoading(false);
     
    };
    
    fetchProperties();
  }, []);

  useEffect(() => {
  const geocodeAddresses = async () => {
    if (!window.google || !window.google.maps) return;
    const geocoder = new window.google.maps.Geocoder();

    const markerPromises = properties.map(property =>
        new Promise(resolve => {
          // Log the address being geocoded
          console.log('Geocoding address:', property.full_address);
          
          geocoder.geocode({ address: property.full_address }, (results, status) => {
            if (status === 'OK' && results[0]) {
              const geocodedLat = results[0].geometry.location.lat();
              const geocodedLng = results[0].geometry.location.lng();
              
              console.log('Geocoded coordinates for', property.title, ':', property.full_address, geocodedLat, geocodedLng, property.price);
              
              resolve({
                id: property.id,
                position: {
                  lat: geocodedLat,   // Use geocoded latitude
                  lng: geocodedLng,   // Use geocoded longitude
                },
                title: property.title,
                price: property.price,
                currency: property.currency,
              });
            } else {
              console.log('Geocoding failed for:', property.title, 'Status:', status);
              resolve(null);
            }
          });
        })
    );

    const results = await Promise.all(markerPromises);
    const validMarkers = results.filter(Boolean);
    setMarkers(validMarkers);

    // Center map on first marker if available
    if (validMarkers.length > 0) {
      setCenter(validMarkers[0].position);
    }
  };

  if (properties.length > 0) {
    geocodeAddresses();
  }
}, [properties]);


  
  

  const options = useMemo(
    () => ({
      disableDefaultUI: true,
      clickableIcons: true,
      styles: 
      [
        {
            "featureType": "administrative",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#6195a0"
                }
            ]
        },
        {
            "featureType": "administrative.province",
            "elementType": "labels.text",
            "stylers": [
                {
                    "visibility": "off"
                }
            ]
        },
        {
            "featureType": "administrative.neighborhood",
            "elementType": "labels.text",
            "stylers": [
                {
                    "visibility": "on"
                }
            ]
        },
        {
            "featureType": "landscape",
            "elementType": "all",
            "stylers": [
                {
                    "color": "#f2f2f2"
                }
            ]
        },
        {
            "featureType": "landscape",
            "elementType": "geometry.fill",
            "stylers": [
                {
                    "color": "#ffffff"
                }
            ]
        },
        {
            "featureType": "landscape.natural.landcover",
            "elementType": "geometry.fill",
            "stylers": [
                {
                    "visibility": "off"
                },
                {
                    "color": "#b5cc79"
                }
            ]
        },
        {
            "featureType": "landscape.natural.terrain",
            "elementType": "geometry.fill",
            "stylers": [
                {
                    "visibility": "off"
                },
                {
                    "color": "#ff0000"
                }
            ]
        },
        {
            "featureType": "poi",
            "elementType": "all",
            "stylers": [
                {
                    "visibility": "off"
                }
            ]
        },
        {
            "featureType": "poi.business",
            "elementType": "geometry.fill",
            "stylers": [
                {
                    "visibility": "off"
                }
            ]
        },
        {
            "featureType": "poi.government",
            "elementType": "geometry.fill",
            "stylers": [
                {
                    "visibility": "off"
                },
                {
                    "color": "#ff0000"
                }
            ]
        },
        {
            "featureType": "poi.park",
            "elementType": "geometry.fill",
            "stylers": [
                {
                    "color": "#e6f3d6"
                },
                {
                    "visibility": "on"
                }
            ]
        },
        {
            "featureType": "road",
            "elementType": "all",
            "stylers": [
                {
                    "saturation": -100
                },
                {
                    "lightness": 45
                },
                {
                    "visibility": "simplified"
                }
            ]
        },
        {
            "featureType": "road.highway",
            "elementType": "all",
            "stylers": [
                {
                    "visibility": "simplified"
                }
            ]
        },
        {
            "featureType": "road.highway",
            "elementType": "geometry.fill",
            "stylers": [
                {
                    "color": "#f4d2c5"
                },
                {
                    "visibility": "simplified"
                }
            ]
        },
        {
            "featureType": "road.highway",
            "elementType": "labels.text",
            "stylers": [
                {
                    "color": "#4e4e4e"
                }
            ]
        },
        {
            "featureType": "road.highway.controlled_access",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "visibility": "off"
                }
            ]
        },
        {
            "featureType": "road.arterial",
            "elementType": "geometry.fill",
            "stylers": [
                {
                    "color": "#f4f4f4"
                }
            ]
        },
        {
            "featureType": "road.arterial",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#787878"
                },
                {
                    "visibility": "off"
                }
            ]
        },
        {
            "featureType": "road.arterial",
            "elementType": "labels.icon",
            "stylers": [
                {
                    "visibility": "off"
                }
            ]
        },
        {
            "featureType": "transit",
            "elementType": "all",
            "stylers": [
                {
                    "visibility": "off"
                }
            ]
        },
        {
            "featureType": "water",
            "elementType": "all",
            "stylers": [
                {
                    "color": "#eaf6f8"
                },
                {
                    "visibility": "on"
                }
            ]
        },
        {
            "featureType": "water",
            "elementType": "geometry.fill",
            "stylers": [
                {
                    "color": "#6ce1f4"
                }
            ]
        }
    ]


    
      
    }),
    []
  );

  const handleMarkerClick = (marker) => {
  // Find the full property object by id
  const property = properties.find(p => p.id === marker.id);
  setSelectedProperty(property);
  };

  useEffect(() => {
  if (selectedProperty) {
    console.log("Selected property changed:", selectedProperty);
  }
  }, [selectedProperty]);

  const closePopup = () => setSelectedProperty(null);

  const handleMapClick = () => {
    if (selectedProperty) setSelectedProperty(null);
  };

  return (
    <div style={{ position: "relative", width: "100%", height: "100%" }}>
      {loading ? (
        <div className="flex items-center justify-center h-full">Loading map...</div>
      ) : (
        <GoogleMap
          mapContainerClassName="map-container"
          center={center}
          options={options}
          zoom={11}
          onLoad={setMapRef}
        >
          {mapRef &&
            markers.map(marker => (
            <NewMarker
              key={marker.id}
              position={marker.position}
              propertyId={marker.id} // Pass propertyId to connect with cards
              onClick={() => handleMarkerClick(marker)}
            >
             <span className="marker-text text-xs font-bold flex items-center">
               {marker.currency && <span>{marker.currency}&nbsp;</span>}
               <span>{marker.price?.toLocaleString()}</span>
             </span>
            </NewMarker>
           ))}

          
          {/* Use the new overlay component */}
        <PropertyCardOverlay
          property={selectedProperty}
          onClose={() => setSelectedProperty(null)}
        />
        </GoogleMap>
      )}
    </div>
  );
};

export default UpdatedMap;