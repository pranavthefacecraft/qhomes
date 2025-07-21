import React from 'react';
import { BrowserRouter as Router } from 'react-router-dom';
import { useLoadScript } from "@react-google-maps/api";
import Layout from './components/Layout';
import './App.css';

function App() {
  const { isLoaded } = useLoadScript({
    googleMapsApiKey: "AIzaSyCjEGPM8XfoV22BVzUmPTRtjoxYcrCTQcI",
    libraries: ["places"],
  });

  if (!isLoaded) return <div>Loading...</div>;
  
  return (
    <Router>
      <Layout />
    </Router>
  );
}

export default App;