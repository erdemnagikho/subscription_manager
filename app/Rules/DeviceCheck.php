<?php

namespace App\Rules;

use App\Models\Device;
use Illuminate\Contracts\Validation\Rule;

class DeviceCheck implements Rule
{
    /**
     * @var int
     */
    private int $appId;

    /**
     * @param int $appId
     */
    public function __construct(int $appId)
    {
        $this->appId = $appId;
    }

    /**
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $device = Device::query()
            ->where('uid', $value)
            ->first();

        if (!is_null($device)) {
            $deviceApplicationIds = $device->applications->pluck('id');

            foreach ($deviceApplicationIds as $applicationId) {
                if ($applicationId === $this->appId) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @return string
     */
    public function message(): string
    {
        return 'This device already registered with this app';
    }
}
