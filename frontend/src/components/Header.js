import React from 'react';
import { Link } from 'react-router-dom';

import Searchbar from './SearchBar';

const Header = () => {
  return (
    <header className="header shadow-md">
      <div className="header-containers">
        {/* Logo */}
        <div className="logo">

          <div className='logo-text'>
            <span className='logo-first'>Q</span>
            <span className='logo-second'>Homes</span>
          </div>
          
        </div>

        {/* Search Bar */}
        <div className="search">

           <Searchbar/>
          
        </div>

        {/* Links */}
        <div className="links">

          <nav className='nav-links space-x-8'>

            <Link 
              to="/" 
              className="text-gray-700 hover:text-primary-600 transition-colors"
            >
              Home
            </Link>
            <a 
              href="#properties" 
              className="text-gray-700 hover:text-primary-600 transition-colors"
            >
              Properties
            </a>
            <a 
              href="#about" 
              className="text-gray-700 hover:text-primary-600 transition-colors"
            >
              About
            </a>
            <a 
              href="#contact" 
              className="text-gray-700 hover:text-primary-600 transition-colors"
            >
              Contact
            </a>

          </nav>
          
        </div>
      </div>
    </header>
  );
};

export default Header;