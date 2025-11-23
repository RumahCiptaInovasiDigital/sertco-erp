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
        Schema::create('project_sheets', function (Blueprint $table) {
            $table->uuid('id_project')->primary();
            $table->string('project_no');
            $table->text('project_detail')->nullable();
            $table->string('prepared_by')->nullable();
            $table->dateTime('issued_date')->nullable();
            $table->string('signature_by')->nullable();
            $table->dateTime('signature_date')->nullable();
            $table->enum('status', ['draft', 'progress', 'complete'])->default('draft');
            $table->integer('progress')->default(100);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_sheets');
    }
};
