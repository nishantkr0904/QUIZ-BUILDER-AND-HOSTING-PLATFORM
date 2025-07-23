<?php
// Migration for quizzes table
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizzesTable extends Migration {
    public function up()
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('category_id')->constrained('categories');
            $table->string('difficulty');
            $table->integer('duration'); // in minutes
            $table->integer('passing_score');
            $table->boolean('review_enabled')->default(false);
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('quizzes');
    }
};
