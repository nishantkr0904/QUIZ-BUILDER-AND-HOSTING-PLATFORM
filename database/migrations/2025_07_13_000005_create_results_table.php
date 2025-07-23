<?php
// Migration for results table
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultsTable extends Migration {
    public function up()
    {
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('quiz_id')->constrained('quizzes');
            $table->integer('score');
            $table->json('details')->nullable();
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('results');
    }
};
