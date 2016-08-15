<?php

namespace App;

use Carbon\Carbon;
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

    /**
     * Set Zone's serial parameter if needed.
     *
     * We only need to modify this field is has been pushed to a server.
     *
     * @param  boolean $force
     * @return int
     */
    public function setSerialNumber($force = false)
    {
        if ($this->updated and !$force) {
            return $this->serial;
        }

        $currentSerial = (int)$this->serial;
        $nowSerial = Zone::createSerialNumber();

        $this->serial = ($currentSerial >= $nowSerial)
            ? $currentSerial + 1
            : $nowSerial;
        $this->save();

        return $this->serial;
    }

    /**
     * Create a new Serial Number based on a specified format
     *
     * @return int
     */
    public static function createSerialNumber()
    {
        return (int)Carbon::now()->format('Ymd') . '01';
    }

    /**
     * Returns if this is a master zone.
     *
     * The DNS server is the primary source for information about this zone, and it stores
     * the master copy of zone data in a local file.
     *
     * @return bool
     */
    public function isMasterZone()
    {
        return (!$this->master);
    }

    /**
     * Marks / unmark pending changes on a zone.
     *
     * @param bool $value
     * @return bool
     */
    public function setPendingChanges($value = true)
    {
        if ($this->hasPendingChanges() != $value) {
            $this->updated = $value;
            $this->save();
        }

        return $this->updated;
    }

    /**
     * Returns if this zone has changes to send to servers.
     *
     * @return bool
     */
    public function hasPendingChanges()
    {
        return $this->updated;
    }
}
