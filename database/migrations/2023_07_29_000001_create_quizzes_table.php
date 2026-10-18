<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizzesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('description');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->enum('difficulty', ['easy', 'medium', 'hard']);
            $table->integer('duration')->comment('Duration in minutes');
            $table->integer('passing_score')->default(60);
            $table->boolean('review_enabled')->default(true);
            $table->timestamp('availability_start')->nullable();
            $table->timestamp('availability_end')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
