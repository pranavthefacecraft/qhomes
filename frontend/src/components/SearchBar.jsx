import React, { useState, useRef, useEffect } from 'react';

const Searchbar = () => {
  const [type, setType] = useState('Buy');
  const [showDropdown, setShowDropdown] = useState(false);
  const dropdownRef = useRef(null);

  // Close dropdown when clicking outside
  useEffect(() => {
    const handleClickOutside = (event) => {
      if (dropdownRef.current && !dropdownRef.current.contains(event.target)) {
        setShowDropdown(false);
      }
    };

    document.addEventListener('mousedown', handleClickOutside);
    return () => {
      document.removeEventListener('mousedown', handleClickOutside);
    };
  }, []);

  const handleTypeChange = (newType) => {
    setType(newType);
    setShowDropdown(false);
  };

  return (
    <>
      <div className="airbnb-searchbar">
        <div 
          className="drop-down" 
          onClick={() => setShowDropdown(!showDropdown)}
          ref={dropdownRef}
        >
          <span className="segment-label">{type}</span>
          <img src='/arrow-down.svg' className='arrow-icon'/> 
          
          {showDropdown && (
            <div className="dropdown-menu">
              <div 
                className={`dropdown-item ${type === 'Buy' ? 'active' : ''}`}
                onClick={() => handleTypeChange('Buy')}
              >
                Buy
              </div>
              <div 
                className={`dropdown-item ${type === 'Rent' ? 'active' : ''}`}
                onClick={() => handleTypeChange('Rent')}
              >
                Rent
              </div>
            </div>
          )}
        </div>

        <div className='divider'/>

        <div className="searcharea">
          <div className="search-segment">
            <img src='/search.svg' className='search-icon'/>  
            <input
              type="text"
              placeholder="Search by location..."
              className="search-input"
            />
          </div>
        </div>
        <div className="searchbutton">
          <img src='/Container.svg' className='button-icon'/>
        </div>
      </div>

      {/* Filter Button */}
      <div className="filterbutton">
        <button className="filter">
          <img src='/Vector.svg' className='filter-icon'/>
          <span className='filter-text'>Filters</span>
        </button>
      </div>

      <style jsx>{`
        .dropdown-menu {
          position: absolute;
          top: 100%;
          left: 0;
          width: 100%;
          background-color: white;
          border: 1px solid #dcdcdc;
          border-radius: 12px;
          box-shadow: 0 2px 10px rgba(0,0,0,0.1);
          z-index: 100;
          margin-top: 8px;
          overflow: hidden;
        }
        
        .dropdown-item {
          padding: 10px 16px;
          font-size: 14px;
          font-weight: 500;
          cursor: pointer;
          color: #333;
        }
        
        .dropdown-item:hover {
          background-color: #f7f7f7;
        }
        
        .dropdown-item.active {
          color: #6091ED;
          background-color: #f0f5ff;
        }
        
        .drop-down {
          position: relative;
          cursor: pointer;
        }
      `}</style>
    </>
  );
};

export default Searchbar;