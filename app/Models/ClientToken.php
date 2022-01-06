<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClientToken extends Model
{
    use HasFactory;

    protected $table = 'client_tokens';

    protected $fillable = [
        'device_id',
        'token',
    ];

    /**
     * @param       $query
     * @param array $value
     *
     * @return mixed
     */
    public function scopeGetClientTokenByDeviceAndAppId($query, array $value): mixed
    {
        return $query
            ->where('device_id', $value['device_id'])
            ->where('app_id', $value['app_id'])
            ->first();
    }

    /**
     * @param $query
     * @param $value
     *
     * @return mixed
     */
    public function scopeGetClientTokenByToken($query, $value): mixed
    {
        return $query
            ->where('token', $value)
            ->first();
    }
}
