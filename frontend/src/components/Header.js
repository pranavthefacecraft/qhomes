import React from 'react';
import { Link } from 'react-router-dom';

const Header = () => {
  return (
    <header className="header bg-white shadow-md">
      <div className="container mx-auto px-4">
        <div className="flex justify-between items-center py-4">
          {/* Logo */}
          <Link to="/" className="flex items-center hover:opacity-80 transition-opacity">
            <img 
              src="/logo.png" 
              alt="QHomes Logo" 
              className="h-10 w-10 mr-1"
            />
            <h1 className="main text-2xl font-bold text-primary-700">QHomes</h1>
          </Link>
          
          {/* Custom Styled Search Bar */}
          <div className="search-bar-container">
            <div className="search-bar">
              <input
                type="text"
                placeholder="Search properties..."
                className="search-input"
              />
              <button className="search-button">
                <svg className="search-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </button>
            </div>
          </div>
          
          {/* Navigation */}
          <nav className="options hidden md:flex space-x-8">
            <Link 
              to="/" 
              className="text-gray-700 hover:text-primary-600 font-medium transition-colors"
            >
              Home
            </Link>
            <a 
              href="#properties" 
              className="text-gray-700 hover:text-primary-600 font-medium transition-colors"
            >
              Properties
            </a>
            <a 
              href="#about" 
              className="text-gray-700 hover:text-primary-600 font-medium transition-colors"
            >
              About
            </a>
            <a 
              href="#contact" 
              className="text-gray-700 hover:text-primary-600 font-medium transition-colors"
            >
              Contact
            </a>
          </nav>

          {/* Mobile Menu Button */}
          <div className="md:hidden">
            <button className="text-gray-700 hover:text-primary-600">
              <svg className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </header>
  );
};

export default Header;