import React from "react";
import { OverlayView } from "@react-google-maps/api";

const NewMarker = ({ position, children, hovered }) => (
  <OverlayView
    position={position}
    mapPaneName={OverlayView.OVERLAY_MOUSE_TARGET}
  >
    <div className={`tooltip-container${hovered ? " hovered" : ""}`}>
      <div className="tooltip-bubble">{children}</div>
      <div className="tooltip-pointer-border"></div>
      <div className="tooltip-pointer"></div>
    </div>
  </OverlayView>
);

export default NewMarker;