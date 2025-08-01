<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('quiz_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            $table->json('answers');
            $table->integer('score');
            $table->integer('total_points');
            $table->integer('percentage');
            $table->boolean('passed');
            $table->timestamp('started_at');
            $table->timestamp('completed_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('quiz_results');
    }
};
