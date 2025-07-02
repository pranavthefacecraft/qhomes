<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->enum('type', ['house', 'apartment', 'condo', 'townhouse', 'commercial', 'land', 'other']);
            $table->enum('status', ['for_sale', 'for_rent', 'sold', 'rented', 'draft']);
            $table->decimal('price', 15, 2);
            $table->string('currency', 3)->default('USD');
            
            // Location fields
            $table->text('address');
            $table->string('city');
            $table->string('state');
            $table->string('postal_code');
            $table->string('country')->default('US');
            
            // Property details
            $table->integer('bedrooms')->nullable();
            $table->decimal('bathrooms', 3, 1)->nullable();
            $table->integer('square_feet')->nullable();
            $table->integer('lot_size')->nullable();
            $table->integer('year_built')->nullable();
            
            // Features
            $table->json('features')->nullable(); // Pool, Garage, Garden, etc.
            
            // Description
            $table->longText('description')->nullable();
            $table->text('key_features')->nullable();
            
            // Media
            $table->json('images')->nullable();
            $table->string('virtual_tour_link')->nullable();
            
            // Contact & Additional
            $table->string('agent_name')->nullable();
            $table->string('agent_phone')->nullable();
            $table->string('agent_email')->nullable();
            $table->date('availability_date')->nullable();
            $table->string('property_id')->unique()->nullable();
            
            // SEO & Admin
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('views')->default(0);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
