<?php

namespace App\Console\Commands;

use App\Models\Device;
use App\Jobs\EventInserting;
use App\Models\Subscription;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use App\OSManager\ReceiptChecker;
use App\OSManager\Adaptee\IosChecker;
use App\OSManager\Adaptee\AndroidChecker;
use Illuminate\Database\Eloquent\Collection;
use App\OSManager\Adapters\IosCheckerAdapter;
use App\OSManager\Adapters\AndroidCheckerAdapter;

class ExpiredSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
        protected $signature = 'expired-subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $subscriptions = Subscription::whereDate('expire_date', '<', Carbon::now()->format('Y-m-d H:i:s'))->get();

        if ($subscriptions->count() > 0) {
            $rateLimitsCollection = $this->makeRenewed($subscriptions);

            if ($rateLimitsCollection->count() > 0) {
                $this->makeRenewed($rateLimitsCollection);
            }
        }
    }

    /**
     * @param Collection $subscriptionCollection
     */
    private function makeRenewed(Collection $subscriptionCollection): Collection
    {
        $rateLimitsCollection = new Collection();

        foreach ($subscriptionCollection->chunk(1000) as $subscriptions) {
            foreach ($subscriptions as $subscription) {
                $confirmed = $this->checkReceipt($subscription);

                if ($confirmed) {
                    $lastTwoDigits = (int) substr($subscription->receipt, -2);

                    if ($lastTwoDigits % 6 == 0) {
                        if (!$rateLimitsCollection->contains($subscription)) {
                            $rateLimitsCollection->push($subscription);
                        }
                    } else {
                        $subscription->update([
                            'expire_date' => Carbon::now()->addYear()->format('Y-m-d H:i:s'),
                            'status' => Subscription::SUBSCRIPTION_RENEWED,
                        ]);

                        if ($rateLimitsCollection->contains($subscription)) {
                            $rateLimitsCollection->forget($subscription);
                        }

                        EventInserting::dispatch($subscription->device_id, $subscription->app_id, Subscription::SUBSCRIPTION_RENEWED);
                    }
                }
            }
        }

        return $rateLimitsCollection;
    }

    /**
     * @param Subscription $subscription
     *
     * @return bool
     */
    private function checkReceipt(Subscription $subscription): bool
    {
        $device = $subscription->device;

        $osManager = match ($device->os) {
            Device::OS_ANDROID => $receiptChecker = new ReceiptChecker(new AndroidCheckerAdapter()),
            Device::OS_IOS => $receiptChecker = new ReceiptChecker(new IosCheckerAdapter()),
            default => $receiptChecker = new ReceiptChecker(new AndroidCheckerAdapter()),
        };

        $credentials = [];

        if (Device::OS_ANDROID === $device->os) {
            $credentials['username'] = AndroidChecker::USERNAME;
            $credentials['password'] = AndroidChecker::PASSWORD;
        } else {
            $credentials['username'] = IosChecker::USERNAME;
            $credentials['password'] = IosChecker::PASSWORD;
        }

        $response = $osManager->checkReceipt($subscription->receipt, $credentials);
        $response = $response->getData(true);

        return $response['status'];
    }
}
