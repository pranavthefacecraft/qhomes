<?php

namespace App\Console\Commands;

use App\Models\Property;
use App\Services\GeocodingService;
use Illuminate\Console\Command;

class UpdatePropertyCoordinates extends Command
{
    protected $signature = 'properties:update-coordinates';
    protected $description = 'Update properties with missing latitude and longitude using geocoding';

    public function handle(GeocodingService $geocodingService)
    {
        $this->info('Starting to update property coordinates...');
        
        $properties = Property::whereNull('latitude')->orWhereNull('longitude')->get();
        
        if ($properties->isEmpty()) {
            $this->info('No properties need coordinate updates.'); 
            return Command::SUCCESS;
        }
        
        $this->info("Found {$properties->count()} properties to update.");
        
        $progressBar = $this->output->createProgressBar($properties->count());
        $progressBar->start();
        
        $updated = 0;
        $failed = 0;
        
        foreach ($properties as $property) {
            try {
                $coordinates = $geocodingService->getCoordinates(
                    $property->address,
                    $property->city,
                    $property->state,
                    $property->postal_code,
                    $property->country ?? 'US'
                );
                
                if ($coordinates) {
                    $property->update([
                        'latitude' => $coordinates['latitude'],
                        'longitude' => $coordinates['longitude']
                    ]);
                    $updated++;
                    $this->newLine();
                    $this->info("Updated property ID {$property->id}: {$coordinates['latitude']}, {$coordinates['longitude']}");
                } else {
                    $failed++;
                    $this->newLine();
                    $this->warn("Failed to geocode property ID {$property->id}: {$property->address}, {$property->city}");
                }
                
                // Be nice to the geocoding service
                sleep(1);
                
            } catch (\Exception $e) {
                $failed++;
                $this->newLine();
                $this->error("Error updating property ID {$property->id}: " . $e->getMessage());
            }
            
            $progressBar->advance();
        }
        
        $progressBar->finish();
        $this->newLine();
        $this->info("Update completed: {$updated} updated, {$failed} failed.");
        
        return Command::SUCCESS;
    }
}
