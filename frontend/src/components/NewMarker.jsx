import React from "react";
import { OverlayView } from "@react-google-maps/api";
import useHoverStore from './store';

const NewMarker = ({ position, children, propertyId, onClick }) => {
  const { hoveredPropertyId, setHoveredProperty, clearHoveredProperty } = useHoverStore();
  const hovered = hoveredPropertyId === propertyId;

  return (
    <OverlayView
      position={position}
      mapPaneName={OverlayView.OVERLAY_MOUSE_TARGET}
    >
      <div
        className={`tooltip-container${hovered ? " hovered" : ""}`}
        onClick={onClick}
        onMouseEnter={() => setHoveredProperty(propertyId)}
        onMouseLeave={clearHoveredProperty}
        style={{ cursor: "pointer", pointerEvents: "auto" }}
      >
        <div className="tooltip-bubble">{children}</div>
        <div className="tooltip-pointer-border"></div>
        <div className="tooltip-pointer"></div>
      </div>
    </OverlayView>
  );
};

export default NewMarker;