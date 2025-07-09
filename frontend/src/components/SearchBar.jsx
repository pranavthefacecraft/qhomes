import React, { useState } from 'react';

const Searchbar = () => {
  const [type, setType] = useState('Buy');
  const [showDropdown, setShowDropdown] = useState(false);

  return (
    <>
    <div className="airbnb-searchbar">

        <div className="drop-down" onClick={() => setShowDropdown(!showDropdown)}>

            <span className="segment-label">{type}</span>
            <img src='/arrow-down.svg' className='arrow-icon'/> 
            

        </div>

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

    </>
  );
};

export default Searchbar;
