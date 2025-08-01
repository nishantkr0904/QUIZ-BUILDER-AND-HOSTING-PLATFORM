<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            $table->text('question');
            $table->enum('type', ['multiple_choice', 'single_choice', 'true_false']);
            $table->json('options')->nullable();
            $table->string('correct_answer');
            $table->text('explanation')->nullable();
            $table->integer('points')->default(1);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('questions');
    }
};
