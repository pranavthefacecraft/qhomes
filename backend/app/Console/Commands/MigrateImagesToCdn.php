<?php

namespace App\Console\Commands;

use App\Models\Property;
use App\Services\CdnService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MigrateImagesToCdn extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'migrate:images-to-cdn {--dry-run : Show what would be migrated without actually doing it}';

    /**
     * The console command description.
     */
    protected $description = 'Migrate existing property images from local storage to CDN'; 

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->info('🔍 DRY RUN MODE - No changes will be made');
        }

        $this->info('🚀 Starting image migration to CDN...');

        if (!CdnService::isEnabled()) {
            $this->error('❌ CDN is not properly configured. Please check your .env file.');
            return 1;
        }

        $properties = Property::whereNotNull('images')->get();
        $totalProperties = $properties->count();
        
        if ($totalProperties === 0) {
            $this->info('ℹ️  No properties with images found.');
            return 0;
        }

        $this->info("📊 Found {$totalProperties} properties with images");

        $progressBar = $this->output->createProgressBar($totalProperties);
        $progressBar->start();

        $migratedCount = 0;
        $errorCount = 0;
        $skippedCount = 0;

        foreach ($properties as $property) {
            $images = $property->images;
            $updatedImages = [];
            $hasChanges = false;

            // Migrate main image
            if (isset($images['main'])) {
                $result = $this->migrateImage($images['main'], $dryRun);
                if ($result['success']) {
                    $updatedImages['main'] = $result['url'];
                    if ($result['migrated']) {
                        $hasChanges = true;
                    }
                } else {
                    $this->error("❌ Failed to migrate main image for property {$property->id}: {$result['error']}");
                    $errorCount++;
                }
            }

            // Migrate additional images
            if (isset($images['additional']) && is_array($images['additional'])) {
                $updatedAdditional = [];
                foreach ($images['additional'] as $additionalImage) {
                    $result = $this->migrateImage($additionalImage, $dryRun);
                    if ($result['success']) {
                        $updatedAdditional[] = $result['url'];
                        if ($result['migrated']) {
                            $hasChanges = true;
                        }
                    } else {
                        $this->error("❌ Failed to migrate additional image for property {$property->id}: {$result['error']}");
                        $errorCount++;
                    }
                }
                if (!empty($updatedAdditional)) {
                    $updatedImages['additional'] = $updatedAdditional;
                }
            }

            // Update property if there were changes
            if ($hasChanges && !$dryRun) {
                $property->update(['images' => $updatedImages]);
                $migratedCount++;
            } elseif (!$hasChanges) {
                $skippedCount++;
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        // Summary
        $this->info('📈 Migration Summary:');
        $this->table(
            ['Status', 'Count'],
            [
                ['✅ Successfully migrated', $migratedCount],
                ['⏭️  Skipped (already on CDN)', $skippedCount],
                ['❌ Errors', $errorCount],
                ['📊 Total processed', $totalProperties]
            ]
        );

        if ($dryRun) {
            $this->info('🔍 This was a dry run. Use the command without --dry-run to perform the actual migration.');
        }

        return $errorCount > 0 ? 1 : 0;
    }

    /**
     * Migrate a single image to CDN
     */
    private function migrateImage(string $imagePath, bool $dryRun): array
    {
        // Check if image is already a CDN URL
        if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
            return [
                'success' => true,
                'url' => $imagePath,
                'migrated' => false
            ];
        }

        try {
            // Check if local file exists
            if (!Storage::disk('public')->exists($imagePath)) {
                return [
                    'success' => false,
                    'error' => "Local file not found: {$imagePath}"
                ];
            }

            if ($dryRun) {
                return [
                    'success' => true,
                    'url' => 'https://cdn.example.com/' . $imagePath,
                    'migrated' => true
                ];
            }

            // Get file content from local storage
            $fileContent = Storage::disk('public')->get($imagePath);
            $fileName = basename($imagePath);
            $directory = dirname($imagePath);

            // Upload to CDN
            $cdnDisk = CdnService::getDisk();
            $cdnPath = $cdnDisk->put($directory . '/' . $fileName, $fileContent);
            
            if ($cdnPath) {
                $cdnUrl = $cdnDisk->url($cdnPath);
                
                // Optionally delete local file after successful upload
                // Storage::disk('public')->delete($imagePath);
                
                return [
                    'success' => true,
                    'url' => $cdnUrl,
                    'migrated' => true
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'Failed to upload to CDN'
                ];
            }

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
