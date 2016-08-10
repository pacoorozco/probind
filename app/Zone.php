<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Zone extends Model
{

    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    /**
     * The database table used by the model.
     */
    protected $table = 'zones';
    protected $fillable = [
        'domain',
        'master',
    ];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'domain'  => 'string',
        'serial'  => 'integer',
        'master'  => 'string',
        'updated' => 'boolean',
    ];

    /**
     * Set the Zone's domain lowercase.
     *
     * @param  string $value
     * @return string
     */
    public function setDomainAttribute($value)
    {
        $this->attributes['domain'] = strtolower($value);
    }

    /**
     * Set the Zone's master lowercase.
     *
     * @param  string $value
     * @return string
     */
    public function setMasterAttribute($value)
    {
        $this->attributes['master'] = strtolower($value);
    }

    /**
     * A zone will have some records.
     */
    public function records()
    {
        return $this->hasMany('App\Record');
    }
}
