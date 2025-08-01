<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('instructions')->nullable();
            $table->integer('duration')->comment('Duration in minutes');
            $table->integer('pass_score')->default(60)->comment('Passing score percentage');
            $table->integer('attempts_allowed')->default(1);
            $table->boolean('is_featured')->default(false);
            $table->boolean('show_answers')->default(false);
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->integer('total_questions')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamp('availability_start')->nullable();
            $table->timestamp('availability_end')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('quizzes');
    }
};
