<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('records', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 253);
            $table->integer('zone_id')->unsigned();
            $table->foreign('zone_id')
                ->references('id')->on('zones')
                ->onDelete('cascade');
            $table->integer('ttl')->unsigned();
            $table->string('type', 10);
            $table->integer('priority')->length(3)->nullable();
            $table->string('data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('records');
    }
}
