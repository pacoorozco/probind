<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Record model, represents a DNS entry on a specified zone.
 *
 * @property string $name
 * @property integer $ttl
 * @property string $type
 * @property integer $priority
 * @property string $data
 */
class Record extends Model
{

    /**
     * Valid Record Types. These will be used to validation.
     *
     * @var array
     */
    public static $validInputTypes = [
        'A'     => 'A (IPv4 address)',
        'AAAA'  => 'AAAA (IPv6 address)',
        'CNAME' => 'CNAME (canonical name)',
        'MX'    => 'MX (mail exchange)',
        'NS'    => 'NS (name server)',
        'PTR'   => 'PTR (pointer)',
        'SRV'   => 'SRV (service locator)',
        'TXT'   => 'TXT (text)',
    ];
    /**
     * The database table used by the model.
     */
    protected $table = 'records';
    protected $fillable = [
        'name',
        'type',
        'data'
    ];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name'     => 'string',
        'ttl'      => 'integer',
        'type'     => 'string',
        'priority' => 'integer',
        'data'     => 'string'
    ];
    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['zone'];

    /**
     * Set the Record's type uppercase.
     *
     * @param  string $value
     * @return string
     */
    public function setTypeAttribute($value)
    {
        $this->attributes['type'] = strtoupper($value);
    }

    /**
     * Set the Record's name lowercase.
     *
     * @param  string $value
     * @return string
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }

    /**
     * Set the Record's data lowercase.
     *
     * @param  string $value
     * @return string
     */
    public function setDataAttribute($value)
    {
        $this->attributes['data'] = strtolower($value);
    }

    /**
     * A record belongs to a zone.
     */
    public function zone()
    {
        return $this->belongsTo('App\Zone');
    }
}
