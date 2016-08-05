<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hostname')->unique();
            $table->string('ip_address', 45);
            $table->enum('type', ['master', 'slave']);
            $table->boolean('push_updates')->default(true);
            $table->boolean('ns_record')->default(true);
            $table->string('directory');
            $table->string('template');
            $table->string('script');
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
        Schema::drop('servers');
    }
}
