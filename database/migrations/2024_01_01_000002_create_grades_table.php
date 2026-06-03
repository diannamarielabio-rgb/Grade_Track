<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->string('subject');
            $table->decimal('score', 5, 2);
            $table->string('semester');
            $table->string('school_year')->default('2025-2026');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('grades'); }
};
