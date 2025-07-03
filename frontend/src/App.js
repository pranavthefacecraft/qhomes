import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import { useLoadScript } from "@react-google-maps/api";
import Header from './components/Header';
import PropertyList from './components/PropertyList';
import PropertyDetail from './components/PropertyDetail';
import Footer from './components/Footer';
import './App.css';

import Map from './components/Map';

function App() {

  const { isLoaded } = useLoadScript({
    googleMapsApiKey: "AIzaSyCjEGPM8XfoV22BVzUmPTRtjoxYcrCTQcI",
    libraries: ["places"],
  });

  if (!isLoaded) return <div>Loading...</div>;
  return (
    <Router>
      <div className="min-h-screen bg-gray-50">
        <Header />
        <Routes>
          <Route path="/" element={
            <main className="container-full mx-auto px-2 py-4">
              {/* Headings at the top center */}
              <div className="maintext w-full items-center">
                <h1 className="text-3xl md:text-4xl font-bold text-center text-gray-800 mb-2">
                  Welcome to QHomes
                </h1>
                <p className="text-lg md:text-xl text-center text-gray-600"> 
                  Find your dream property with us
                </p>
              </div>
              {/* Responsive sections below */}
              <div className="flex flex-col lg:flex-row gap-6 w-full">
                <section className="mb-8 lg:mb-0 w-full lg:w-[60vw]">
                  <PropertyList />
                </section>
                <section className="brounded shadow w-full lg:w-[40vw] min-h-[400px] flex items-center justify-center">
                  <Map/>
                </section>
              </div>
            </main>
          } />
          <Route path="/property/:id" element={<PropertyDetail />} />
        </Routes>
        <Footer />
      </div>
    </Router>
  );
}

export default App;