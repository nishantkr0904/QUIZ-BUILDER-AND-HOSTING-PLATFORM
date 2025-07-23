<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompletedToResultsTable extends Migration
{
    public function up()
    {
        Schema::table('results', function (Blueprint $table) {
            $table->boolean('completed')->default(false)->after('details');
            $table->timestamp('completed_at')->nullable()->after('completed');
            $table->integer('answered_questions_count')->default(0)->after('score');
        });
    }

    public function down()
    {
        Schema::table('results', function (Blueprint $table) {
            $table->dropColumn(['completed', 'completed_at', 'answered_questions_count']);
        });
    }
};
