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
    <Wrapper apiKey="AIzaSyCjEGPM8XfoV22BVzUmPTRtjoxYcrCTQcI">
       <MyMap/>
    </Wrapper>
  );
};

export default ThreeDMap;