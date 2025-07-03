import React, { useEffect, useState, useMemo } from 'react';
import axios from 'axios';
import { GoogleMap, Marker } from '@react-google-maps/api';

import CustomMarker from './CustomMarker';
import useHoverStore from './store';



const defaultCenter = { lat: 3.1319, lng: 101.6841 }; // Example: Delhi

const Map = () => {
  const [properties, setProperties] = useState([]);
  const [mapRef, setMapRef] = useState(null);
  const [markers, setMarkers] = useState([]);
  const [center, setCenter] = useState(defaultCenter);
  const hoveredPropertyId = useHoverStore(state => state.hoveredPropertyId);

  const options = useMemo(
    () => ({
      disableDefaultUI: true,
      clickableIcons: false,
      styles : [
    {
        "featureType": "administrative",
        "elementType": "labels",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "landscape",
        "elementType": "all",
        "stylers": [
            {
                "visibility": "on"
            }
        ]
    },
    {
        "featureType": "poi.attraction",
        "elementType": "labels",
        "stylers": [
            {
                "visibility": "on"
            }
        ]
    },
    {
        "featureType": "poi.business",
        "elementType": "all",
        "stylers": [
            {
                "visibility": "on"
            }
        ]
    },
    {
        "featureType": "poi.business",
        "elementType": "labels",
        "stylers": [
            {
                "visibility": "on"
            }
        ]
    },
    {
        "featureType": "poi.business",
        "elementType": "labels.icon",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "poi.government",
        "elementType": "labels",
        "stylers": [
            {
                "visibility": "on"
            }
        ]
    },
    {
        "featureType": "poi.school",
        "elementType": "all",
        "stylers": [
            {
                "visibility": "on"
            }
        ]
    },
    {
        "featureType": "poi.school",
        "elementType": "labels",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "all",
        "stylers": [
            {
                "visibility": "on"
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "labels",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    }
]
    }),
    []
  );

  useEffect(() => {
    const fetchProperties = async () => {
      try {
        const response = await axios.get('http://localhost:8000/api/properties');
        setProperties(Array.isArray(response.data) ? response.data : []);
      } catch (err) {
        setProperties([]);
      }
    };
    fetchProperties();
  }, []);

  useEffect(() => {
    const geocodeAddresses = async () => {
      if (!window.google || !window.google.maps) return;
      const geocoder = new window.google.maps.Geocoder();

      const markerPromises = properties.map(
        property =>
          new Promise(resolve => {
            geocoder.geocode({ address: property.location }, (results, status) => {
              if (status === 'OK' && results[0]) {
                resolve({
                  id: property.id,
                  position: {
                    lat: results[0].geometry.location.lat(),
                    lng: results[0].geometry.location.lng(),
                  },
                  title: property.title,
                  price: property.price, // <-- add this line
                  currency: property.currency, // <-- add this if you want to show currency
                });
              } else {
                resolve(null);
              }
            });
          })
        );

      const results = await Promise.all(markerPromises);
      const validMarkers = results.filter(Boolean);
      setMarkers(validMarkers);

      // Center on the first marker if available
      if (validMarkers.length > 0) {
        setCenter(validMarkers[0].position);
      }
    };

    if (properties.length > 0) {
      geocodeAddresses();
    }
  }, [properties]);

  return (
    <div style={{ position: "relative", width: "100%", height: "100%" }}>
    <GoogleMap
      mapContainerClassName="map-container"
      center={center}
      options={options}
      zoom={12}
      onLoad={setMapRef}
    />
    {mapRef &&
      markers.map(marker => (
      <CustomMarker key={marker.id} map={mapRef} position={marker.position}>
        <div
          className={`bg-white select-none text-grey rounded-full shadow-lg px-1 py-0 min-w-[20px] min-h-[16px] border border-gray-200 flex items-center justify-center transition-colors duration-200 ${
            hoveredPropertyId === marker.id ? 'bg-black text-white border-black' : ''
          }`}
        >
        
        <span className="labels text-xs font-bold flex items-center">
          {/* Custom icon SVG */}
        <svg
          className="w-6 h-6 mr-4"
          stroke="currentColor"
          strokeWidth={1.5}
          viewBox="0 0 24 24"
        >
          <path
            strokeLinecap="round"
            strokeLinejoin="round"
            d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5z"
          />
        </svg>
          {marker.currency && <span>{marker.currency}&nbsp;</span>}
          <span>{marker.price.toLocaleString()}</span>
        </span>
        </div>
      </CustomMarker>
      ))}
  </div>
  );
};

export default Map;