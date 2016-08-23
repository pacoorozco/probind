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
        Schema::create('servers', function(Blueprint $table) {
            $table->increments('id');
            $table->string('hostname')->unique();
            $table->string('ip_address', 45);
            $table->enum('type', ['master', 'slave']);
            $table->boolean('push_updates')->default(false);
            $table->boolean('ns_record')->default(false);
            $table->string('directory');
            $table->string('template');
            $table->string('script');
            $table->boolean('active')->default(true);
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
