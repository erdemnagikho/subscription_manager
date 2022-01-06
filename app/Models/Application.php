<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Application extends Model
{
    use HasFactory;

    protected $table = 'applications';

    /**
     * @return BelongsToMany
     */
    public function applications(): BelongsToMany
    {
        return $this->belongsToMany(Device::class, 'devices_applications', 'app_id', 'device_id');
    }
}
