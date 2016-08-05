<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    /**
     * The database table used by the model.
     */
    protected $table = 'zones';
    protected $fillable = [
        'domain',
        'serial',
        'refresh',
        'retry',
        'expire',
        'master',
        'file',
        'updated',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'domain' => 'string',
        'serial' => 'integer',
        'refresh' => 'integer',
        'retry' => 'integer',
        'expire' => 'integer',
        'master' => 'string',
        'file' => 'string',
        'updated' => 'boolean',
        'status' => 'string'
    ];

    /**
     * A zone will have some records.
     */
    public function records()
    {
        return $this->hasMany('App\Record');
    }
}
