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
        'script',
        'active'
    ];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'hostname'   => 'string',
        'ip_address' => 'string',
        'type'       => 'string',
        'directory'  => 'string',
        'template'   => 'string',
        'script'     => 'string'
    ];

    /**
     * Set the Server's hostname lowercase.
     *
     * @param  string $value
     * @return string
     */
    public function setHostnameAttribute($value)
    {
        $this->attributes['hostname'] = strtolower($value);
    }

    /**
     * Set the Server's ip_address lowercase.
     *
     * @param  string $value
     * @return string
     */
    public function setIpAddressAttribute($value)
    {
        $this->attributes['ip_address'] = strtolower($value);
    }

    /**
     * Set the Server's type lowercase.
     *
     * @param  string $value
     * @return string
     */
    public function setTypeAttribute($value)
    {
        $this->attributes['type'] = strtolower($value);
    }

}