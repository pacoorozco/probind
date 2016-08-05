<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateZonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zones', function (Blueprint $table) {
            $table->increments('id');
            $table->string('domain', 253)->unique();
            $table->integer('serial')->unsigned();
            $table->integer('refresh')->unsigned();
            $table->integer('retry')->unsigned();
            $table->integer('expire')->unsigned();
            $table->string('master', 45)->nullable();
            $table->string('file');
            $table->boolean('updated')->default(false);
            $table->enum('status', ['active', 'inactive', 'deleted'])->default('active');
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
