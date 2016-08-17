<?php

namespace App\Providers;

use App\Record;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Touch Zone when save/delete a Record
        Record::saving(function (Record $record) {
            $zone = $record->zone()->first();
            $zone->setPendingChanges();
        });
        Record::deleting(function (Record $record) {
            $zone = $record->zone()->first();
            $zone->setPendingChanges();
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
