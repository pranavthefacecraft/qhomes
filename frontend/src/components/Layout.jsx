import React, { useEffect, useRef } from 'react';
import { Routes, Route } from 'react-router-dom';
import Header from './Header';
import Footer from './Footer';
import Cards from './Cards';
import ThreeDMap from './3Dmap';
import PropertyDetail from './PropertyDetail';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

// Content component for the home route
const Content = () => {
  const mapWrapperRef = useRef(null);
  const rightWrapperRef = useRef(null);

  useEffect(() => {
    const mapWrapper = mapWrapperRef.current;
    const rightWrapper = rightWrapperRef.current;
    const leftWrapper = document.querySelector('.left-wrapper');

    if (mapWrapper && rightWrapper && leftWrapper) {
      // Calculate square dimensions based on available width
      const rightWrapperWidth = rightWrapper.offsetWidth - 32; // subtract padding (1rem * 2)
      mapWrapper.style.width = `${rightWrapperWidth}px`;
      mapWrapper.style.height = `${rightWrapperWidth}px`;

      // Create GSAP sticky animation
      ScrollTrigger.create({
        trigger: leftWrapper, // Use left wrapper as trigger to match its scroll length
        start: "top 20px",
        end: "+=560px", // Manual pixel value - adjust as needed
        pin: mapWrapper,
        pinSpacing: false,
        pinType: "fixed" // Use fixed positioning to avoid layout issues
      });
    }

    // Cleanup function
    return () => {
      ScrollTrigger.getAll().forEach(trigger => trigger.kill());
    };
  }, []);

  return (
    <main style={{
      flex: 1,
      backgroundColor: '#f8f9fa',
      minHeight: 'calc(100vh - 120px)',
      padding: '2rem',
      display: 'flex',
      gap: '1rem'
    }}>
      <div className="left-wrapper" style={{
        width: '55%',
        backgroundColor: '#ffffff',
        borderRadius: '8px',
        padding: '1rem'
      }}>
         <div className='headline'>
          <span>Results for Kuala Lumpur</span>
          <span>Over 1,000 places in Kuala Lumpur</span>
         </div>
         <div className="detailsWrapper">
           <Cards/>
         </div>
      </div>
      
      <div className="right-wrapper" ref={rightWrapperRef} style={{
        width: '45%',
        backgroundColor: '#ffffff',
        borderRadius: '8px',
        padding: '1rem',
        height: 'fit-content' // Prevents extra space
      }}>
        <div className="map-wrapper" ref={mapWrapperRef} style={{
          width: '100%',
          aspectRatio: '1/1', // Makes it square
          borderRadius: '8px',
          position: 'relative'
        }}>
          <ThreeDMap/>
        </div>
      </div>
    </main>
  );
};

const Layout = () => {
  return (
    <div style={{
      minHeight: '100vh',
      display: 'flex',
      flexDirection: 'column',
      fontFamily: 'Arial, sans-serif'
    }}>
      {/* Header Component */}
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
        
      {/* Footer Component */}
      <Footer />
    </div>
  );
};

export default Layout;
