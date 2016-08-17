<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Server model
 *
 * @property integer $id
 * @property string $hostname
 * @property string $ip_address
 * @property string $type
 * @property boolean $push_updates
 * @property boolean $ns_record
 * @property string $directory
 * @property string $template
 * @property string $script
 * @property boolean active *
 */
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
     * @return string|null
     */
    public function setHostnameAttribute($value)
    {
        $this->attributes['hostname'] = strtolower($value);
    }

    /**
     * Set the Server's ip_address lowercase.
     *
     * @param  string $value
     * @return string|null
     */
    public function setIpAddressAttribute($value)
    {
        $this->attributes['ip_address'] = strtolower($value);
    }

    /**
     * Set the Server's type lowercase.
     *
     * @param  string $value
     * @return string|null
     */
    public function setTypeAttribute($value)
    {
        $this->attributes['type'] = strtolower($value);
    }

}
