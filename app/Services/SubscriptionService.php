<?php

namespace App\Services;

use App\Models\Device;
use App\Models\ClientToken;
use App\Models\Subscription;
use App\OSManager\ReceiptChecker;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\OSManager\Adapters\IosCheckerAdapter;
use App\OSManager\Adapters\AndroidCheckerAdapter;

class SubscriptionService
{
    /**
     * @param array $subscriptionData
     *
     * @return array
     */
    public function createSubscription(array $subscriptionData): array
    {
        try {
            $clientToken = ClientToken::getClientTokenByToken($subscriptionData['client_token']);

            $device = Device::getDeviceById($clientToken->device_id);

            if (is_null($device)) {
                return [
                    'status' => false,
                    'subscription' => null,
                ];
            }

            $osManager = match ($device->os) {
                Device::OS_ANDROID => $receiptChecker = new ReceiptChecker(new AndroidCheckerAdapter()),
                Device::OS_IOS => $receiptChecker = new ReceiptChecker(new IosCheckerAdapter()),
                default => $receiptChecker = new ReceiptChecker(new AndroidCheckerAdapter()),
            };

            $response = $osManager->checkReceipt($subscriptionData['receipt'], [
                'username' => $subscriptionData['username'],
                'password' => $subscriptionData['password'],
            ]);
            $response = $response->getData(true);

            if ($response['status']) {
                $subscription = new Subscription();
                $subscription->receipt = $subscriptionData['receipt'];
                $subscription->device_id = $device->id;
                $subscription->app_id = $clientToken->app_id;
                $subscription->status = Subscription::SUBSCRIPTION_STARTED;
                $subscription->expire_date = $response['expire_date'];
                $subscription->save();

                return [
                    'status' => true,
                    'subscription' => $subscription,
                ];
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [
                'data' => $subscriptionData,
                'class' => 'SubscriptionService',
                'function' => 'createSubscription',
            ]);
        }

        return [
            'status' => false,
            'subscription' => null,
        ];
    }

    /**
     * @param string $token
     * @param bool   $useCache
     *
     * @return Subscription|null
     */
    public function getSubscription(string $token, bool $useCache = true): Subscription|null
    {
        try {
            $cacheKey = sprintf('%s%s', Subscription::CACHE_KEY_SUBSCRIPTION_BY_CLIENT_TOKEN, $token);

            if ($useCache && !is_null(Cache::tags(Subscription::CACHE_TAG_SUBSCRIPTIONS)->get($cacheKey))) {
                return Cache::tags(Subscription::CACHE_TAG_SUBSCRIPTIONS)->get($cacheKey);
            }

            $clientToken = ClientToken::getClientTokenByToken($token);

            if (is_null($clientToken)) {
                return null;
            }

            $device = Device::getDeviceById($clientToken->device_id);

            if (!($device instanceof Device)) {
                return null;
            }

            $subscription = Subscription::getByDeviceAndAppId([
               'device_id' => $device->id,
               'app_id' => $clientToken->app_id,
            ]);

            if (($subscription instanceof Subscription) && $useCache) {
                Cache::tags([Subscription::CACHE_TAG_SUBSCRIPTIONS])->put($cacheKey, $subscription, Subscription::CACHE_LIFETIME_ALL);
            }

            if ($subscription instanceof Subscription) {
                return $subscription;
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [
                'data' => $token,
                'class' => 'SubscriptionService',
                'function' => 'getSubscription',
            ]);
        }

        return null;
    }
}
