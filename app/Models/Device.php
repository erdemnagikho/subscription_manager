<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Device extends Model
{
    use HasFactory;

    const OS_ANDROID = 'Android';
    const OS_IOS = 'Ios';

    protected $fillable = [
        'uid',
        'app_id',
        'language',
        'os',
    ];

    /**
     * @return BelongsToMany
     */
    public function applications(): BelongsToMany
    {
        return $this->belongsToMany(Application::class, 'devices_applications', 'device_id', 'app_id');
    }

    /**
     * @return HasMany
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'device_id', 'id');
    }

    /**
     * @param $query
     * @param $value
     *
     * @return mixed
     */
    public function scopeGetDeviceByUid($query, $value): mixed
    {
        return $query
            ->where('uid', $value)
            ->first();
    }

    /**
     * @param $query
     * @param $value
     *
     * @return mixed
     */
    public function scopeGetDeviceById($query, $value): mixed
    {
        return $query
            ->where('id', $value)
            ->first();
    }
}
