<?php

namespace App\Services;

use App\Models\Device;
use App\Models\ClientToken;
use App\Jobs\ClientTokenInserting;
use Illuminate\Support\Facades\Log;

class DeviceService
{
    /**
     * @param array $deviceData
     *
     * @return array|null
     */
    public function createDevice(array $deviceData): array|null
    {
        try {
            $device = Device::getDeviceByUid($deviceData['uid']);

            if ((!$device instanceof Device)) {
                $device = new Device();
                $device->uid = $deviceData['uid'];
                $device->language = $deviceData['language'];
                $device->os = $deviceData['os'];
                $device->save();
            }

            $device->applications()->attach($deviceData['app_id']);

            ClientTokenInserting::dispatch($device->id, $deviceData['app_id']);

            $clientToken = ClientToken::getClientTokenByDeviceAndAppId([
                'device_id' => $device->id,
                'app_id' => $deviceData['app_id'],
            ]);

            return [
                'uid' => $device->uid,
                'app_id' => $deviceData['app_id'],
                'client_token' => $clientToken->token,
            ];
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [
                'data' => $deviceData,
                'class' => 'DeviceService',
                'function' => 'createDevice',
            ]);
        }

        return null;
    }
}
