import React, { useEffect, useState, useRef } from 'react';
import { Wrapper } from '@googlemaps/react-wrapper';
import { PerspectiveCamera,
    Scene,
    AmbientLight,
    WebGLRenderer,
    Matrix4
 } from 'three';
 import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader';

 import MyMap from './Mymap';




const ThreeDMap = () => {
  return (
    <Wrapper apiKey={process.env.REACT_APP_GOOGLE_MAPS_API_KEY}>
       <MyMap/>
    </Wrapper>
  );
};

export default ThreeDMap;