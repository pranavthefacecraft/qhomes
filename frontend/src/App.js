import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import Header from './components/Header';
import PropertyList from './components/PropertyList';
import PropertyDetail from './components/PropertyDetail';
import Footer from './components/Footer';
import './App.css';

function App() {
  return (
    <Router>
      <div className="min-h-screen bg-gray-50">
        <Header />
        <Routes>
          <Route path="/" element={
            <main className="container mx-auto px-4 py-8">
              <section className="mb-12">
                <h1 className="text-4xl font-bold text-center text-gray-800 mb-2">
                  Welcome to QHomes
                </h1>
                <p className="text-xl text-center text-gray-600 mb-8">
                  Find your dream property with us
                </p>
                <PropertyList />
              </section>
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