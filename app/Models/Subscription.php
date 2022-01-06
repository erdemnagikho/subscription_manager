<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscription extends Model
{
    use HasFactory;

    const CACHE_LIFETIME_ALL = 60 * 60 * 5;

    const CACHE_KEY_SUBSCRIPTION_BY_CLIENT_TOKEN = 'subscription-client-token-';

    const CACHE_TAG_SUBSCRIPTIONS = 'subscriptions';

    const SUBSCRIPTION_STARTED = 'Started';
    const SUBSCRIPTION_RENEWED = 'Renewed';
    const SUBSCRIPTION_CANCELLED = 'Cancelled';

    protected $fillable = [
        'receipt',
        'device_id',
        'app_id',
        'status',
        'expire_date',
        'cancelled_date',
    ];

    /**
     * @return BelongsTo
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class, 'device_id', 'id');
    }

    /**
     * @param       $query
     * @param array $value
     *
     * @return mixed
     */
    public function scopeGetByDeviceAndAppId($query, array $value): mixed
    {
        return $query
            ->where('device_id', $value['device_id'])
            ->where('app_id', $value['app_id'])
            ->first();
    }
}
