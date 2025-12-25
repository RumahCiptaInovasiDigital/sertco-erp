<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('service_form_data', function (Blueprint $table) {
            $table->id();
            $table->string('project_no');
            $table->uuid('id_kategori_service');
            $table->uuid('id_service_type')->nullable();
            $table->boolean('other');
            $table->string('other_value')->nullable();
            $table->integer('qty');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_form_data');
    }
};
