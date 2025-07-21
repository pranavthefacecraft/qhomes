import React, { useState, useEffect, useCallback, useMemo } from 'react';
import { useParams, Link } from 'react-router-dom';
import OptimizedImage from '../utils/OptimizedImage';

const PropertyDetail = () => {
    const { id } = useParams();
    const [property, setProperty] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [lightboxOpen, setLightboxOpen] = useState(false);
    const [lightboxImageIndex, setLightboxImageIndex] = useState(0);

    useEffect(() => {
        const fetchProperty = async () => {
            try {
                setLoading(true);
                const response = await fetch(`https://qhomesbackend.tfcmockup.com/api/properties/${id}`);
                
                if (!response.ok) {
                    throw new Error('Property not found');
                }
                
                const data = await response.json();
                setProperty(data);
            } catch (err) {
                setError(err.message);
            } finally {
                setLoading(false);
            }
        };

        if (id) {
            fetchProperty();
        }
    }, [id]);

    const formatPrice = (price, currency = 'USD') => {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: currency,
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
        }).format(price);
    };

    const formatDate = (dateString) => {
        return new Date(dateString).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    };

    const openLightbox = (index = 0) => {
        setLightboxImageIndex(index);
        setLightboxOpen(true);
        document.body.style.overflow = 'hidden';
    };

    const closeLightbox = () => {
        setLightboxOpen(false);
        setLightboxImageIndex(0);
        document.body.style.overflow = 'unset'; // Restore scrolling
    };

    // Memoize images to prevent unnecessary re-renders
    const images = useMemo(() => {
        if (property?.images && property.images.length > 0) {
            return property.images.map(img => `https://qhomesbackend.tfcmockup.com/storage/${img}`);
        }
        return ['/image.jpg'];
    }, [property?.images]);

    const displayImages = useMemo(() => {
        return images.slice(0, 5); // First 5 images for grid
    }, [images]);

    const hasMoreImages = useMemo(() => {
        return images.length > 5;
    }, [images]);

    const nextLightboxImage = useCallback(() => {
        console.log('Next clicked - Current index:', lightboxImageIndex, 'Total images:', images.length);
        if (images && images.length > 0) {
            setLightboxImageIndex((prev) => {
                const newIndex = prev === images.length - 1 ? 0 : prev + 1;
                console.log('Moving from', prev, 'to', newIndex);
                return newIndex;
            });
        }
    }, [images, lightboxImageIndex]);

    const prevLightboxImage = useCallback(() => {
        console.log('Previous clicked - Current index:', lightboxImageIndex, 'Total images:', images.length);
        if (images && images.length > 0) {
            setLightboxImageIndex((prev) => {
                const newIndex = prev === 0 ? images.length - 1 : prev - 1;
                console.log('Moving from', prev, 'to', newIndex);
                return newIndex;
            });
        }
    }, [images, lightboxImageIndex]);

    // Close lightbox on Escape key and navigate with arrow keys
    useEffect(() => {
        const handleKeydown = (e) => {
            if (e.key === 'Escape') {
                closeLightbox();
            } else if (e.key === 'ArrowRight') {
                nextLightboxImage();
            } else if (e.key === 'ArrowLeft') {
                prevLightboxImage();
            }
        };

        if (lightboxOpen) {
            document.addEventListener('keydown', handleKeydown);
        }

        return () => {
            document.removeEventListener('keydown', handleKeydown);
        };
    }, [lightboxOpen, nextLightboxImage, prevLightboxImage]);

    if (loading) {
        return (
            <div className="min-h-screen bg-gray-50 flex items-center justify-center">
                <div className="text-center">
                    <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
                    <p className="mt-4 text-gray-600">Loading property details...</p>
                </div>
            </div>
        );
    }

    if (error) {
        return (
            <div className="min-h-screen bg-gray-50 flex items-center justify-center">
                <div className="text-center">
                    <h2 className="text-2xl font-bold text-gray-900 mb-4">Property Not Found</h2>
                    <p className="text-gray-600 mb-6">{error}</p>
                    <Link 
                        to="/" 
                        className="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200"
                    >
                        Back to Properties
                    </Link>
                </div>
            </div>
        );
    }

    if (!property) {
        return (
            <div className="min-h-screen bg-gray-50 flex items-center justify-center">
                <div className="text-center">
                    <h2 className="text-2xl font-bold text-gray-900 mb-4">Property Not Found</h2>
                    <Link 
                        to="/" 
                        className="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200"
                    >
                        Back to Properties
                    </Link>
                </div>
            </div>
        );
    }

    return (
        <div className="min-h-screen bg-gray-50">
           

            {/* Main Container */}
            <div className="container mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 py-8">
                {/* Property Title Only */}
                <div className="rounded-lg mb-6">
                    <h1 
                        className="font-bold text-gray-900 text-left" 
                        style={{ 
                            fontFamily: '"Rubik", sans-serif', 
                            fontSize: '26px' 
                        }}
                    >
                        {property.title}
                    </h1>
                </div>

                {/* Image Grid */}
                <div className="rounded-lg overflow-hidden mb-8">
                    <div className="grid grid-cols-2 gap-2" style={{ height: '480px' }}>
                        {/* Main large image - left side */}
                        <div 
                            className="relative cursor-pointer bg-gray-200 hover:opacity-90 transition-opacity duration-200" 
                            onClick={() => openLightbox(0)}
                        >
                            <OptimizedImage
                                src={displayImages[0]}
                                alt={`${property.title} - Image 1`}
                                className="w-full h-full object-cover"
                            />
                        </div>
                        
                        {/* Right side - 4 smaller images in 2x2 grid */}
                        <div className="grid grid-cols-2 gap-2">
                            {displayImages.slice(1, 5).map((image, index) => (
                                <div 
                                    key={index + 1} 
                                    className="relative h-full cursor-pointer bg-gray-200 hover:opacity-90 transition-opacity duration-200" 
                                    onClick={() => openLightbox(index + 1)}
                                >
                                    <OptimizedImage
                                        src={image}
                                        alt={`${property.title} - Image ${index + 2}`}
                                        className="w-full h-full object-cover"
                                    />
                                    {/* If this is the 4th small image and there are more images, show overlay */}
                                    {index === 3 && hasMoreImages && (
                                        <div className="absolute inset-0 bg-black bg-opacity-60 flex items-center justify-center hover:bg-opacity-70 transition-all duration-200">
                                            <div className="text-center text-white">
                                                <span className="text-2xl font-bold">+{images.length - 5}</span>
                                                <p className="text-sm mt-1">More Photos</p>
                                            </div>
                                        </div>
                                    )}
                                </div>
                            ))}
                        </div>
                    </div>
                </div>

                {/* Lightbox */}
                {lightboxOpen && (
                    <div 
                        className="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center justify-items-center"
                        style={{ 
                            zIndex: 99999,
                            position: 'fixed',
                            top: 0,
                            left: 0,
                            width: '100vw',
                            height: '100vh',
                            justifyContent: 'center'
                        }}
                        onClick={closeLightbox}
                    >
                        {/* Close button */}
                        <button
                            onClick={(e) => {
                                e.stopPropagation();
                                closeLightbox();
                            }}
                            className="absolute top-4 right-4 text-white text-4xl hover:text-gray-300 z-50"
                            style={{ position: 'absolute', top: '20px', right: '20px', zIndex: 100000 }}
                        >
                            ×
                        </button>
                        
                        {/* Image container */}
                        <div 
                            className="relative max-w-4xl max-h-full mx-auto"
                            onClick={(e) => e.stopPropagation()}
                        >
                            <div className="relative w-full h-full flex items-center justify-center" style={{ height: '80vh' }}>
                                <img
                                    key={lightboxImageIndex} // This forces re-render for animation
                                    src={images[lightboxImageIndex]}
                                    alt={`${property.title} - ${lightboxImageIndex + 1}`}
                                    className="max-w-full max-h-full object-contain transition-opacity duration-300 ease-in-out"
                                    style={{ maxWidth: '90vw', maxHeight: '80vh' }}
                                />
                            </div>
                            
                            {/* Debug info */}
                            <div className="absolute top-2 left-2 bg-black bg-opacity-50 text-white p-2 rounded text-sm">
                                Image {lightboxImageIndex + 1} of {images.length}
                            </div>
                        </div>
                        
                        {/* Navigation */}
                        {images.length > 1 && (
                            <>
                                <button
                                    onClick={(e) => {
                                        e.stopPropagation();
                                        prevLightboxImage();
                                    }}
                                    className="absolute left-4 top-1/2 transform -translate-y-1/2 text-white text-4xl hover:text-gray-300 bg-black bg-opacity-50 rounded-full w-12 h-12 flex items-center justify-center"
                                >
                                    ‹
                                </button>
                                <button
                                    onClick={(e) => {
                                        e.stopPropagation();
                                        nextLightboxImage();
                                    }}
                                    className="absolute right-4 top-1/2 transform -translate-y-1/2 text-white text-4xl hover:text-gray-300 bg-black bg-opacity-50 rounded-full w-12 h-12 flex items-center justify-center"
                                >
                                    ›
                                </button>
                            </>
                        )}
                        
                        {/* Counter */}
                        <div className="absolute bottom-4 left-1/2 transform -translate-x-1/2 text-white text-lg bg-black bg-opacity-50 px-4 py-2 rounded">
                            {lightboxImageIndex + 1} / {images.length}
                        </div>
                    </div>
                )}

                {/* Main Content - 2 Columns */}
                <div className='content-property'>
                <div class="flex flex-row">
  
  <div class="basis-2/3 mr-7">
  <div className="roboto">
                            <p className="text-[14px] text-gray-600 mb-4">
                                📍 {property.address}
                            </p>
                            <div className="flex flex-wrap gap-2">
                                <span className={`px-3 py-1 rounded-full text-[12px] roboto ${
                                    property.status === 'available' 
                                        ? 'bg-[#6091ED] text-white'
                                        : property.status === 'sold'
                                        ? 'bg-[#6091ED] text-white'
                                        : 'bg-[#6091ED] text-white'
                                }`}>
                                    {property.status ? property.status.charAt(0).toUpperCase() + property.status.slice(1) : 'Available'}
                                </span>
                                {property.featured && (
                                    <span className="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                        Featured
                                    </span>
                                )}
                            </div>
                            
                        </div>
                        {/* Property Details */}
                        <div className="roboto">
                            <h2 className="text-[18px] font-semibold text-gray-900 mt-4 mb-4">Property Details</h2>
                            <div className="space-y-3 text-[14px] ">
                                <div className="flex justify-between">
                                    <span className="text-gray-600">Property Type:</span>
                                    <span className="capitalize">{property.type}</span>
                                </div>
                                <div className="flex justify-between">
                                    <span className="text-gray-600">Bedrooms:</span>
                                    <span className="font-medium">{property.bedrooms}</span>
                                </div>
                                <div className="flex justify-between">
                                    <span className="text-gray-600">Bathrooms:</span>
                                    <span className="font-medium">{property.bathrooms}</span>
                                </div>
                                <div className="flex justify-between">
                                    <span className="text-gray-600">Square Feet:</span>
                                    <span className="font-medium">{property.sqft?.toLocaleString() || 'N/A'}</span>
                                </div>
                                <div className="flex justify-between">
                                    <span className="text-gray-600">Listed Date:</span>
                                    <span className="font-medium">{formatDate(property.created_at)}</span>
                                </div>
                                {property.year_built && (
                                    <div className="flex justify-between">
                                        <span className="text-gray-600">Year Built:</span>
                                        <span className="font-medium">{property.year_built}</span>
                                    </div>
                                )}
                                {property.lot_size && (
                                    <div className="flex justify-between">
                                        <span className="text-gray-600">Lot Size:</span>
                                        <span className="font-medium">{property.lot_size} acres</span>
                                    </div>
                                )}
                                <div className="flex justify-between">
                                    <span className="text-gray-600">Property ID:</span>
                                    <span className="font-medium">#{property.id}</span>
                                </div>
                            </div>
                        </div>
                        {/* Property Features Grid */}
                        <div className="roboto">
                            <h2 className="text-[18px] font-semibold text-gray-900 mt-4 mb-4">Property Features</h2>
                            <div className=" grid grid-cols-4 gap-4">
                                <div className="text-center p-3 bg-white rounded-lg">
                                    <div className="text-2xl font-bold text-gray-900">{property.bedrooms}</div>
                                    <div className="text-sm text-black">Bedrooms</div>
                                </div>
                                <div className="text-center p-3 bg-white rounded-lg">
                                    <div className="text-2xl font-bold text-gray-900">{property.bathrooms}</div>
                                    <div className="text-sm text-gray-600">Bathrooms</div>
                                </div>
                                <div className="text-center p-3 bg-white rounded-lg">
                                    <div className="text-2xl font-bold text-gray-900">{property.sqft?.toLocaleString() || 'N/A'}</div>
                                    <div className="text-sm text-gray-600">Sq Ft</div>
                                </div>
                                <div className="text-center p-3 bg-white rounded-lg">
                                    <div className="text-2xl font-bold text-gray-900 capitalize">{property.type}</div>
                                    <div className="text-sm text-gray-600">Property Type</div>
                                </div>
                            </div>
                        </div>
                        {/* Features List */}
                        {property.features && property.features.length > 0 && (
                            <div className="roboto">
                                <h2 className="text-[18px] font-semibold text-gray-900 mt-4 mb-4">Features</h2>
                                <div className="grid grid-cols-1 gap-2">
                                    {property.features.map((feature, index) => (
                                        <div key={index} className="flex items-center space-x-2">
                                            <span className="text-green-500">✓</span>
                                            <span className="text-gray-700">{feature}</span>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        )}

                        {/* Description */}
                        <div className="roboto">
                            <h2 className="text-[18px] font-semibold text-gray-900 mt-4 mb-4">Description</h2>
                            <div 
                                className="text-gray-700 leading-[20px] roboto text-[14px] prose prose-sm max-w-none"
                                dangerouslySetInnerHTML={{ 
                                    __html: property.description || 'No description available for this property.' 
                                }}
                            />
                        </div>
                        
  </div>
  <div class="basis-1/3">
  {/* Price & Booking Card */}
                        <div className="bg-white rounded-lg shadow-sm p-6 sticky top-4">
                            {/* Price */}
                            <div className="mb-4">
                                <div className="text-[18px] roboto font-bold text-blue-600 mb-1">
                                    {property.display_price || formatPrice(property.price, property.currency)}
                                </div>
                                <p className="text-gray-600 roboto text-[14px]">
                                    {property.status === 'for_rent' ? 'per month' : 'total price'}
                                </p>
                            </div>

                            {/* Check-in & Check-out Calendar */}
                            <div className="space-y-4 mb-6">
                                <div className="grid grid-cols-2 gap-4">
                                    <div>
                                        <label className="block roboto text-[14px] text-gray-700 mb-0">
                                            Check-in
                                        </label>
                                        <input 
                                            type="date" 
                                            className="w-full border border-gray-300 rounded-lg px-3 py-1 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        />
                                    </div>
                                    <div>
                                        <label className="block roboto text-[14px] text-gray-700 mb-0">
                                            Check-out
                                        </label>
                                        <input 
                                            type="date" 
                                            className="w-full border border-gray-300 rounded-lg px-3 py-1 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        />
                                    </div>
                                </div>
                                
                                <div>
                                    <label className="block roboto text-[14px] text-gray-700 mb-0">
                                        Guests
                                    </label>
                                    <select className="w-full border border-gray-300 rounded-lg px-3 py-1 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="1">1 guest</option>
                                        <option value="2">2 guests</option>
                                        <option value="3">3 guests</option>
                                        <option value="4">4 guests</option>
                                        <option value="5">5+ guests</option>
                                    </select>
                                </div>
                            </div>

                            {/* Reserve Button */}
                            <button className="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition duration-200 text-lg font-semibold mb-4">
                                Reserve Now
                            </button>

                            {/* Additional Info */}
                            <div className="space-y-2 text-sm text-gray-600">
                                <div className="flex justify-between">
                                    <span>Cleaning fee</span>
                                    <span>$50</span>
                                </div>
                                <div className="flex justify-between">
                                    <span>Service fee</span>
                                    <span>$25</span>
                                </div>
                                <hr className="my-3" />
                                <div className="flex justify-between font-semibold text-gray-900">
                                    <span>Total</span>
                                    <span>{property.display_price || formatPrice((property.price || 0) + 75, property.currency)}</span>
                                </div>
                            </div>
                        </div>
  </div>
</div>
                </div>
                <div className="grid grid-cols-2 lg:grid-cols-2 gap-8 border hidden">
                    {/* Left Column - Property Details */}
                    

                    {/* Right Column - Price & Booking */}
                    <div className="space-y-6">
                        

                        {/* Agent Information */}
                        {property.agent && (
                            <div className="bg-white rounded-lg shadow-sm p-6">
                                <h2 className="text-xl font-semibold text-gray-900 mb-4">Listed by</h2>
                                <div className="text-center">
                                    <div className="w-20 h-20 bg-gray-200 rounded-full mx-auto mb-4 flex items-center justify-center">
                                        {property.agent.avatar ? (
                                            <OptimizedImage
                                                src={property.agent.avatar}
                                                alt={property.agent.name}
                                                className="w-20 h-20 rounded-full object-cover"
                                            />
                                        ) : (
                                            <span className="text-2xl text-gray-500">👤</span>
                                        )}
                                    </div>
                                    <h3 className="text-lg font-semibold text-gray-900 mb-1">
                                        {property.agent.name}
                                    </h3>
                                    {property.agent.company && (
                                        <p className="text-gray-600 mb-2">{property.agent.company}</p>
                                    )}
                                    <div className="space-y-2">
                                        {property.agent.phone && (
                                            <p className="text-sm text-gray-600">
                                                📞 {property.agent.phone}
                                            </p>
                                        )}
                                        {property.agent.email && (
                                            <p className="text-sm text-gray-600">
                                                ✉️ {property.agent.email}
                                            </p>
                                        )}
                                    </div>
                                    <div className="mt-4 space-y-2">
                                        <button className="w-full bg-gray-100 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-200 transition duration-200">
                                            Contact Agent
                                        </button>
                                        <button className="w-full border border-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-50 transition duration-200">
                                            Schedule Tour
                                        </button>
                                    </div>
                                </div>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
};

export default PropertyDetail;
