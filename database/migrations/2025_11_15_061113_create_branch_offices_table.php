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
        Schema::create('branch_offices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('address',255)->nullable();
            $table->string('city',255)->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code',20)->nullable();
            $table->string('country',100)->nullable();
            $table->string('phone',20)->nullable();
            $table->string('fax',20)->nullable();
            $table->string('email',255)->nullable();
            $table->json('coordinates')->nullable();
            $table->json('ip_registered')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_offices');
    }
};
