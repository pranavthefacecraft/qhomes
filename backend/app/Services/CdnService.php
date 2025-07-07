<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CdnService
{
    /**
     * Get the appropriate storage disk based on configuration
     */
    public static function getDisk()
    {
        // Use CDN disk if AWS is configured, otherwise fall back to public
        if (config('filesystems.disks.cdn.key') && config('filesystems.disks.cdn.bucket')) {
            return Storage::disk('cdn');
        }
        
        return Storage::disk('public');
    }

    /**
     * Store a file and return the URL
     */
    public static function store($file, $path = 'property-images')
    {
        try {
            $disk = self::getDisk();
            $storedPath = $disk->put($path, $file);
            
            // Return the full URL
            return $disk->url($storedPath);
        } catch (\Exception $e) {
            Log::error('CDN Storage Error: ' . $e->getMessage());
            
            // Fallback to local storage
            $localPath = Storage::disk('public')->put($path, $file);
            return Storage::disk('public')->url($localPath);
        }
    }

    /**
     * Get the URL for a stored file
     */
    public static function url($path)
    {
        try {
            $disk = self::getDisk();
            return $disk->url($path);
        } catch (\Exception $e) {
            Log::error('CDN URL Error: ' . $e->getMessage());
            
            // Fallback to local storage URL
            return Storage::disk('public')->url($path);
        }
    }

    /**
     * Delete a file from storage
     */
    public static function delete($path)
    {
        try {
            $disk = self::getDisk();
            return $disk->delete($path);
        } catch (\Exception $e) {
            Log::error('CDN Delete Error: ' . $e->getMessage());
            
            // Try to delete from local storage as fallback
            return Storage::disk('public')->delete($path);
        }
    }

    /**
     * Check if CDN is enabled and configured
     */
    public static function isEnabled()
    {
        return config('filesystems.disks.cdn.key') && 
               config('filesystems.disks.cdn.bucket') && 
               config('filesystems.disks.cdn.url');
    }

    /**
     * Get the CDN base URL
     */
    public static function getBaseUrl()
    {
        if (self::isEnabled()) {
            return config('filesystems.disks.cdn.url');
        }
        
        return config('app.url') . '/storage';
    }
}
