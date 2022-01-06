<?php

namespace App\Jobs;

use App\Models\ClientToken;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ClientTokenInserting implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int
     */
    private int $deviceId;

    /**
     * @var int
     */
    private int $appId;

    /**
     * @param int $deviceId
     * @param int $appId
     */
    public function __construct(int $deviceId, int $appId)
    {
        $this->deviceId = $deviceId;
        $this->appId = $appId;
    }

    /**
     * @return void
     */
    public function handle()
    {
        $clientToken = new ClientToken();
        $clientToken->device_id = $this->deviceId;
        $clientToken->app_id = $this->appId;
        $clientToken->token = $this->generateRandomString(20);
        $clientToken->save();
    }

    /**
     * @param int $length
     *
     * @return string
     */
    private function generateRandomString($length = 10): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
