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
        Schema::table('questions', function (Blueprint $table) {
            $table->enum('type', ['multiple_choice', 'single_choice', 'true_false'])->after('question');
            $table->json('options')->nullable()->after('type');
            $table->string('correct_answer')->after('options');
            $table->text('explanation')->nullable()->after('correct_answer');
            $table->integer('points')->default(1)->after('explanation');
            $table->integer('order')->default(0)->after('points');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn(['type', 'options', 'correct_answer', 'explanation', 'points', 'order']);
        });
    }
};
