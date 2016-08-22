<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateZonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zones', function(Blueprint $table) {
            $table->increments('id');
            $table->string('domain', 253)->unique();
            $table->integer('serial')->unsigned();
            $table->string('master', 45)->nullable();
            $table->boolean('updated')->default(true);

            $table->boolean('custom_settings')->default(false);
            $table->integer('refresh')->unsigned()->nullable();
            $table->integer('retry')->unsigned()->nullable();
            $table->integer('expire')->unsigned()->nullable();
            $table->integer('negative_ttl')->unsigned()->nullable();
            $table->integer('default_ttl')->unsigned()->nullable();

            $table->softDeletes();
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
        Schema::drop('zones');
    }
}
