<?php

namespace App\Http\Controllers\api;

use App\Jobs\EventInserting;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\SubscriptionService;
use App\Http\Requests\SubscriptionRequest;
use App\Http\Resources\SubscriptionResource;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionController extends Controller
{
    /**
     * @param SubscriptionRequest $request
     * @param SubscriptionService $subscriptionService
     *
     * @return JsonResponse
     */
    public function store(SubscriptionRequest $request, SubscriptionService $subscriptionService): JsonResponse
    {
        if(is_null($request->header('username')) || is_null($request->header('password'))) {
            return response()->json([
                'error' => 'Credentials information can not be blank',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $subscriptionData = $request->validated();

        $subscriptionData['username'] = $request->header('username');
        $subscriptionData['password'] = $request->header('password');

        $response = $subscriptionService->createSubscription($subscriptionData);

        if (!($response['subscription'] instanceof Subscription)) {
            return response()->json([
                'error' => 'An error occurred while creating subscription. Check your receipt and credentials',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $subscription = $response['subscription'];

        EventInserting::dispatch($subscription->device_id, $subscription->app_id, Subscription::SUBSCRIPTION_STARTED);

        return response()->json([
            'subscription' => new SubscriptionResource($response['subscription']),
        ], Response::HTTP_OK);
    }

    /**
     * @param string              $clientToken
     * @param SubscriptionService $subscriptionService
     *
     * @return JsonResponse
     */
    public function checkSubscription(string $clientToken, SubscriptionService $subscriptionService): JsonResponse
    {
        if (is_null($clientToken)) {
            return response()->json([
                'error' => 'Client token parameter can not be null',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $subscription = $subscriptionService->getSubscription($clientToken);

        if (!($subscription instanceof Subscription)) {
            return response()->json([
                'error' => 'An error occurred while getting subscription. Check your client token.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'subscription' => new SubscriptionResource($subscription),
        ], Response::HTTP_OK);
    }
}
