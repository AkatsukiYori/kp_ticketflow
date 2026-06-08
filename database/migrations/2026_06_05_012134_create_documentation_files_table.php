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
        Schema::create('documentation_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')
                ->constrained('documentations')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->string('filename', length: 255);
            $table->string('file_path', length: 255);
            $table->string('mime_type', length: 100);
            $table->unsignedBigInteger('size');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentation_files');
    }
};
