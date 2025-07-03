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
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->string('agent_name');
            $table->string('display_name');
            $table->string('code')->unique();
            $table->string('nric')->unique();
            $table->string('email')->unique();
            $table->string('mobile');
            $table->text('address');
            $table->string('designation');
            $table->string('upline')->nullable();
            $table->string('sponsor')->nullable();
            $table->string('branch');
            $table->string('payee_nric');
            $table->string('payee_nric_type');
            $table->string('bank');
            $table->string('bank_account_no');
            $table->string('ren_code')->nullable();
            $table->string('ren_license')->nullable();
            $table->date('ren_expired_date')->nullable();
            $table->date('join_date');
            $table->date('resign_date')->nullable();
            $table->string('leaderboard')->nullable();
            $table->enum('active', ['Yes', 'No'])->default('Yes');
            $table->text('remark')->nullable();
            $table->string('created_by');
            $table->string('last_modified_by')->nullable();
            $table->timestamp('last_modified_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
