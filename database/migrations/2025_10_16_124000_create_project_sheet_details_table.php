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
        Schema::create('project_sheet_details', function (Blueprint $table) {
            $table->uuid('id_detail')->primary();
            $table->uuid('id_project');
            $table->foreign('id_project')->references('id_project')->on('project_sheets')->onDelete('cascade');
            $table->string('client')->nullable();
            $table->string('owner')->nullable();
            $table->string('contract_no')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('ph_no')->nullable();
            $table->string('email_client')->nullable();
            $table->string('hp_no')->nullable();
            $table->text('contract_description')->nullable();
            $table->string('contract_period')->nullable();
            $table->string('payment_term')->nullable();
            $table->dateTime('schedule_start')->nullable();
            $table->dateTime('schedule_end')->nullable();
            $table->string('pricedoc')->nullable();
            $table->string('unpricedoc')->nullable();
            $table->string('pricedoclink')->nullable();
            $table->string('unpricedoclink')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_sheet_details');
    }
};
