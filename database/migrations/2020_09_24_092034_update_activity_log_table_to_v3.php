<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateActivityLogTableToV3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('v3', function (Blueprint $table) {
            $table->text('description')->change();
            $table->unsignedInteger('subject_id')->nullable()->change();
            $table->unsignedInteger('causer_id')->nullable()->change();
            $table->json('properties')->nullable()->change();

            $table->index('log_name');
            $table->index(['subject_id', 'subject_type'], 'subject');
            $table->index(['causer_id', 'causer_type'], 'causer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
