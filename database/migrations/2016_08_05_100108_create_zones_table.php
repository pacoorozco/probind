<?php
/*
 * Copyright (c) 2016-2022 Paco Orozco <paco@pacoorozco.info>
 *
 * This file is part of ProBIND v3.
 *
 * ProBIND v3 is free software: you can redistribute it and/or modify it under the terms of the GNU
 * General Public License as published by the Free Software Foundation, either version 3 of the
 * License, or any later version.
 *
 * ProBIND v3 is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See
 * the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with ProBIND v3. If not,
 * see <https://www.gnu.org/licenses/>.
 *
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZonesTable extends Migration
{
    public function up(): void
    {
        Schema::create('zones', function (Blueprint $table) {
            $table->id();
            $table->string('domain')->unique();
            $table->integer('serial')->unsigned()->default(0);
            $table->string('server')->nullable();
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

    public function down(): void
    {
        Schema::dropIfExists('zones');
    }
}
