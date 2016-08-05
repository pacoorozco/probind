<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    /**
     * The database table used by the model.
     */
    protected $table = 'servers';
    protected $fillable = [
        'hostname',
        'ip_address',
        'type',
        'push_updates',
        'ns_record',
        'directory',
        'template',
        'script'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'hostname' => 'string',
        'ip_address' => 'string',
        'type' => 'string',
        'push_updates' => 'boolean',
        'ns_record' => 'boolean',
        'directory' => 'string',
        'template' => 'string',
        'script' => 'string'
    ];

}
