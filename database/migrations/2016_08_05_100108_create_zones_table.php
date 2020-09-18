<?php
/**
 * ProBIND v3 - Professional DNS management made easy.
 *
 * Copyright (c) 2016 by Paco Orozco <paco@pacoorozco.info>
 *
 * This file is part of some open source application.
 *
 * Licensed under GNU General Public License 3.0.
 * Some rights reserved. See LICENSE, AUTHORS.
 *
 *  @author      Paco Orozco <paco@pacoorozco.info>
 *  @copyright   2016 Paco Orozco
 *  @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *  @link        https://github.com/pacoorozco/probind
 *
 */

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
            $table->bigIncrements('id');
            $table->string('domain', 253)->unique();
            $table->integer('serial')->unsigned()->default(0);
            $table->string('master_server', 45)->nullable();
            $table->boolean('has_modifications')->default(true);
            $table->boolean('reverse_zone')->default(false);

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
        Schema::dropIfExists('zones');
    }
}
