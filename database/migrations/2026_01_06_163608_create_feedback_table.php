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
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id')->nullable();
        
            $table->enum('type', [
                'bug',
                'ui',
                'feature',
                'performance',
                'other'
            ]);
        
            $table->text('message');
            $table->string('page')->nullable();
        
            $table->enum('status', [
                'open',
                'in_progress',
                'resolved'
            ])->default('open');
        
            $table->string('browser')->nullable();
            $table->string('ip_address')->nullable();
        
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
