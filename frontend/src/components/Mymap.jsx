import React, { useEffect, useState, useRef } from 'react';
import { Wrapper } from '@googlemaps/react-wrapper';
import * as THREE from 'three';
import { PerspectiveCamera,
    Scene,
    AmbientLight,
    WebGLRenderer,
    Matrix4
 } from 'three';
 import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader';

 const mapOptions = {
  mapId: process.env.REACT_APP_MAP_ID,
  center: { lat: 3.1581827058250758, lng: 101.71116104992754},
  zoom: 25,
  disableDefaultUI: true,
  heading: 25,
  tilt: 25,
};



const MyMap = () => {
  
  const [map, setMap] = useState(null);
  const mapRef = useRef(null);
  const overlayRef = useRef(null);

  useEffect(() => {
    if(!overlayRef.current) {
    const instance = new window.google.maps.Map(mapRef.current, mapOptions)
    setMap(instance);
    overlayRef.current = createOverlay(instance);
    }
  }, [])  


  return (
    <>
    <div ref={mapRef} className='map-container'>
        Hello
    </div>
    </>
  );
};

export default MyMap;


function createOverlay(map) {
   console.log("Creating overlay") 
  
   const overlay = new window.google.maps.WebGLOverlayView();
   let renderer, scene, camera, loader;

   overlay.onAdd = () => {
    console.log('onAdd called');

    scene = new Scene();
    camera = new PerspectiveCamera(45, window.innerWidth / window.innerHeight, 0.1, 1000);
    const light = new AmbientLight(0xffffff, 5);
    scene.add(light);

    loader = new GLTFLoader();
    loader.loadAsync('/tower.gltf').then(object => {
        console.log("model loaded")
        const group = object.scene;
        group.scale.setScalar(5);
        group.rotation.set(Math.PI / 2, 0.5, 0);
        group.position.setZ(-120);
        group.position.setX(100);
        group.position.setY(-70);
        scene.add(group);
    }).catch( error => {
        console.log("error loading model", error)
    })
   }

   overlay.onContextRestored = ({gl}) => {
    console.log('onContextRestored called');

    renderer = new WebGLRenderer({
      canvas: gl.canvas,
      context: gl,
      antialias: true,
      shadows: true,
      ...gl.getContextAttributes(),
    });
    renderer.autoClear = false;
    console.log('Renderer created:', renderer);
   }

   overlay.onDraw = ({transformer}) => {
     console.log('onDraw called');
     
     if (!renderer || !scene || !camera) {
       console.log('Missing components:', {renderer: !!renderer, scene: !!scene, camera: !!camera});
       return;
     }

     const matrix = transformer.fromLatLngAltitude({
       lat: 3.1581827058250758,
       lng: 101.71116104992754,
       altitude: 120
     });
     console.log('Matrix:', matrix);
     camera.projectionMatrix = new Matrix4().fromArray(matrix);

     overlay.requestRedraw();
     renderer.render(scene, camera);
     renderer.resetState();
   }

   overlay.setMap(map);
   console.log('Overlay set to map');

   return overlay;
}