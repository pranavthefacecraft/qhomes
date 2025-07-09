import React, {useLayoutEffect} from 'react';
import UpdatedMap from './Newmap';
import Cards from './Cards';




const Content = () => {

  return (
  <div className="gallery">
    <div className="left">
      <div className='headline'>
        <span>Results for Kuala Lumpur</span>
        <span>Over 1,000 places in Kuala Lumpur</span>
      </div>
      <div className="detailsWrapper">
        <Cards/>
      </div>
    </div>
    <div className="right">
      <div className="map-container">
        <UpdatedMap/>
      </div>
    </div>
  </div>
  );
};

export default Content;
