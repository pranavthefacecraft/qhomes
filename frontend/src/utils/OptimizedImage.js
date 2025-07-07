import React, { useState, useEffect } from 'react';

const OptimizedImage = ({ src, alt, className, fallback = '/image.jpg', onClick, ...props }) => {
    const [imageSrc, setImageSrc] = useState(src);
    const [hasError, setHasError] = useState(false);
    const [isLoading, setIsLoading] = useState(true);

    // Update imageSrc when src prop changes
    useEffect(() => {
        setImageSrc(src);
        setHasError(false);
        setIsLoading(true);
    }, [src]);

    // Ensure the image src is properly formatted
    const getImageSrc = (imagePath) => {
        if (!imagePath) return fallback;
        
        // If it's already a full URL, return as is
        if (imagePath.startsWith('http')) return imagePath;
        
        // If it starts with /, it's already relative to public
        if (imagePath.startsWith('/')) return imagePath;
        
        // Otherwise add the leading slash
        return `/${imagePath}`;
    };

    const handleError = () => {
        if (!hasError) {
            setHasError(true);
            setImageSrc(fallback);
        }
    };

    const handleLoad = () => {
        setIsLoading(false);
    };

    return (
        <div className="relative w-full h-full">
            {isLoading && (
                <div className="absolute inset-0 bg-gray-200 animate-pulse flex items-center justify-center z-10">
                    <span className="text-gray-400">Loading...</span>
                </div>
            )}
            <img
                {...props}
                src={getImageSrc(imageSrc)}
                alt={alt}
                className={`${className || ''} ${isLoading ? 'opacity-0' : 'opacity-100'} transition-opacity duration-300`}
                onError={handleError}
                onLoad={handleLoad}
            />
        </div>
    );
};

export default OptimizedImage;
