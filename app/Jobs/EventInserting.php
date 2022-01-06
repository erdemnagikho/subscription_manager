<?php

namespace App\Jobs;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class EventInserting implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int
     */
    protected int $deviceId;

    /**
     * @var int
     */
    protected int $appId;

    /**
     * @var string
     */
    protected string $eventType;

    /**
     * @param int    $deviceId
     * @param int    $appId
     * @param string $eventType
     */
    public function __construct(int $deviceId, int $appId, string $eventType)
    {
        $this->deviceId = $deviceId;
        $this->appId = $appId;
        $this->eventType = $eventType;
    }

    /**
     * @return void
     */
    public function handle()
    {
        $event = new Event();
        $event->create([
           'device_id' => $this->deviceId,
           'app_id' => $this->appId,
           'event_type' => $this->eventType,
        ]);
    }
}
