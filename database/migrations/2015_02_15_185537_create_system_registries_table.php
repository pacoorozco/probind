<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemRegistriesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::create(\Config::get('registry.table', 'system_registries'), function(Blueprint $table)
        {
            $table->string('key');
            $table->text('value');

            $table->primary('key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::drop(\Config::get('registry.table', 'system_registries'));
    }

}
