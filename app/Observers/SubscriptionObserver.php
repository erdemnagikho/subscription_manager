<?php

namespace App\Observers;

use App\Models\Subscription;
use Illuminate\Support\Facades\Cache;

class SubscriptionObserver
{
    /**
     * @param Subscription $subscription
     *
     * @return void
     */
    public function created(Subscription $subscription)
    {
    }

    /**
     * @param Subscription $subscription
     *
     * @return void
     */
    public function updated(Subscription $subscription)
    {
        Cache::tags(Subscription::CACHE_TAG_SUBSCRIPTIONS)->flush();
    }

    /**
     * @param Subscription $subscription
     *
     * @return void
     */
    public function deleted(Subscription $subscription)
    {
    }

    /**
     * @param Subscription  $subscription
     *
     * @return void
     */
    public function restored(Subscription $subscription)
    {
    }

    /**
     * @param Subscription $subscription
     *
     * @return void
     */
    public function forceDeleted(Subscription $subscription)
    {
    }
}
