<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    /**
     * The database table used by the model.
     */
    protected $table = 'records';
    protected $fillable = [
        'name',
        'ttl',
        'type',
        'priority',
        'data'
    ];

    /*
     * $aa = ['A', 'AAAA', 'CNAME', 'MX', 'NS', 'PTR', 'SOA', 'SRV', 'TXT']
    */

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'ttl' => 'integer',
        'type' => 'string',
        'priority' => 'integer',
        'data' => 'string'
    ];

    /**
     * A record belongs to a zone.
     */
    public function zone()
    {
        return $this->belongsTo('App\Zone');
    }
}
