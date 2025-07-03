import React, { useEffect, useRef, useState } from "react";

const CustomMarker = ({ map, position, children }) => {
  const [point, setPoint] = useState(null);
  const markerRef = useRef();
  const [visible, setVisible] = useState(true);
  

  useEffect(() => {
    if (!map || !window.google) return;

    const updatePosition = () => {
      const scale = Math.pow(2, map.getZoom());
      const proj = map.getProjection();
      if (!proj) return;
      const bounds = map.getBounds();
      if (!bounds) return;
      const topRight = proj.fromLatLngToPoint(bounds.getNorthEast());
      const bottomLeft = proj.fromLatLngToPoint(bounds.getSouthWest());
      const worldPoint = proj.fromLatLngToPoint(new window.google.maps.LatLng(position));
      const left = (worldPoint.x - bottomLeft.x) * scale;
      const top = (worldPoint.y - topRight.y) * scale;
      setPoint({ left, top });

      // Hide marker if out of map container bounds
      const mapDiv = map.getDiv();
      if (
        left < 0 ||
        top < 0 ||
        left > mapDiv.offsetWidth ||
        top > mapDiv.offsetHeight
      ) {
        setVisible(false);
      } else {
        setVisible(true);
      }
    };

    updatePosition();
    const boundsListener = map.addListener("bounds_changed", updatePosition);
    const idleListener = map.addListener("idle", updatePosition);
    window.addEventListener("resize", updatePosition);

    return () => {
      window.google.maps.event.removeListener(boundsListener);
      window.google.maps.event.removeListener(idleListener);
      window.removeEventListener("resize", updatePosition);
    };
  }, [map, position]);

  if (!point || !visible) return null;

  return (
    <div
      ref={markerRef}
      style={{
        position: "absolute",
        left: point.left,
        userSelect: 'none',
        top: point.top,
        transform: "translate(-50%, -100%)",
        zIndex: 10,
        pointerEvents: "auto",
      }}
    >
      {children}
    </div>
  );
};

export default CustomMarker;