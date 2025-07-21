import React from 'react';
import Cards from './Cards';
import ThreeDMap from './3Dmap';

const Content = () => {

  return (
  <div className="flex min-h-screen">
    <div className="flex-1 p-4">
      <div className='mb-6'>
        <h1 className="text-2xl font-bold text-gray-900 mb-2">Results for Kuala Lumpur</h1>
        <p className="text-gray-600">Over 1,000 places in Kuala Lumpur</p>
      </div>
      <div className="h-full">
        <Cards/>
      </div>
    </div>
    <div className="flex-1 p-4">
      <div className="map-container h-full">
        <ThreeDMap/>
      </div>
    </div>
  </div>
  );
};

export default Content;
