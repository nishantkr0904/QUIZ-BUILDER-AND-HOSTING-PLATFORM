<?php
// Migration for questions table
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quizzes');
            $table->string('type'); // MCQ, single, true_false
            $table->text('question_text');
            $table->json('options')->nullable();
            $table->string('correct_answer');
            $table->integer('points')->default(1);
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('questions');
    }
};
