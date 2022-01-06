<?php

namespace App\Rules;

use App\Models\ClientToken;
use App\Models\Subscription;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Validation\Rule;

class SubscriptionCheck implements Rule
{
    /**
     * @var string
     */
    private string $message = 'You have already a subscription.';

    /**
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $clientToken = ClientToken::getClientTokenByToken($value);

        if (!($clientToken instanceof ClientToken)) {
            $this->message = 'Unvalid client token.';

            return false;
        }

        $subscription = Subscription::query()
            ->where('device_id', $clientToken->device_id)
            ->where('app_id', $clientToken->app_id)
            ->where('status', '!=', Subscription::SUBSCRIPTION_CANCELLED)
            ->whereDate('expire_date', '>=', Carbon::now()->format('Y-m-d H:i:s'))
            ->first();

        if (!is_null($subscription)) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function message(): string
    {
        return $this->message;
    }
}
