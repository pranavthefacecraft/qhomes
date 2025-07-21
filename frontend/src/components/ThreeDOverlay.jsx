import { useEffect, useRef } from 'react';
import { 
  PerspectiveCamera,
  Scene,
  AmbientLight,
  WebGLRenderer,
  Matrix4,
  ShaderMaterial,
  DoubleSide
} from 'three';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader';

const ThreeDOverlay = ({ map, position = { lat: 3.1581827058250758, lng: 101.71116104992754 }, altitude = 120 }) => {
  const overlayRef = useRef(null);

  useEffect(() => {
    if (!map || !window.google) return;

    // Create the WebGL overlay
    const createOverlay = () => {
      const overlay = new window.google.maps.WebGLOverlayView();
      let renderer, scene, camera, loader;

      overlay.onAdd = () => {
        // Initialize Three.js scene
        scene = new Scene();
        camera = new PerspectiveCamera(45, window.innerWidth / window.innerHeight, 0.1, 1000);
        const light = new AmbientLight(0xffffff, 5);
        scene.add(light);

        // Load 3D model
        loader = new GLTFLoader();
        loader.loadAsync('/tower.gltf').then(object => {
          const group = object.scene;
          group.scale.setScalar(6);
          group.rotation.set(Math.PI / 2, 1, 0);
          group.position.setZ(-120);
          group.position.setX(80);
          group.position.setY(-50);
          scene.add(group);
        }).catch(error => {
          console.error('Error loading 3D model:', error);
        });
      };

      overlay.onContextRestored = ({ gl }) => {
        // Initialize WebGL renderer
        renderer = new WebGLRenderer({
          canvas: gl.canvas,
          context: gl,
          antialias: true,
          shadows: true,
          ...gl.getContextAttributes(),
        });
        renderer.autoClear = false;
      };

      overlay.onDraw = ({ transformer }) => {
        // Render the 3D scene
        if (!renderer || !scene || !camera) {
          return;
        }

        const matrix = transformer.fromLatLngAltitude({
          lat: position.lat,
          lng: position.lng,
          altitude: altitude
        });
        camera.projectionMatrix = new Matrix4().fromArray(matrix);

        overlay.requestRedraw();
        renderer.render(scene, camera);
        renderer.resetState();
      };

      overlay.setMap(map);
      return overlay;
    };

    // Create and store the overlay
    if (!overlayRef.current) {
      overlayRef.current = createOverlay();
    }

    // Cleanup function
    return () => {
      if (overlayRef.current) {
        overlayRef.current.setMap(null);
        overlayRef.current = null;
      }
    };
  }, [map, position.lat, position.lng, altitude]);

  // This component doesn't render anything visible in React
  return null;
};

export default ThreeDOverlay;
