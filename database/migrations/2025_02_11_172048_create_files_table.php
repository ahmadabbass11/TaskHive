<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Uploader
            $table->foreignId('task_id')->constrained()->onDelete('cascade'); // Associated Task
            $table->string('file_name'); // Original file name
            $table->string('file_path'); // Storage path
            $table->string('mime_type'); // File type (e.g., PDF, image, etc.)
            $table->integer('file_size'); // File size in bytes
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
