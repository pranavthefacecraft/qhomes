import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import { useLoadScript } from "@react-google-maps/api";
import PropertyDetail from './components/PropertyDetail'
import Header from './components/Header';
import Content from './components/Content';
import Footer from './components/Footer';
import './App.css';

function App() {
  const { isLoaded } = useLoadScript({
    googleMapsApiKey: "AIzaSyCjEGPM8XfoV22BVzUmPTRtjoxYcrCTQcI",
    libraries: ["places"],
  });

  if (!isLoaded) return <div>Loading...</div>;
  
  return (
    <Router>
      <div className="page min-h-screen">
        <Header />
        
        <Routes>
          {/* Home route */}
          <Route path="/" element={
            <>
              <Content />
            </>
          } />
          
          <Route path="/property/:id" element={<PropertyDetail />} />
        </Routes>
        
        <Footer />
      </div>
    </Router>
  );
}

export default App;