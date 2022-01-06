<?php

namespace App\Http\Controllers\api;

use App\Models\Device;
use App\Services\DeviceService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\DeviceRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\DeviceResource;
use Symfony\Component\HttpFoundation\Response;

class DeviceController extends Controller
{
    /**
     * @param DeviceRequest $request
     * @param DeviceService $deviceService
     *
     * @return JsonResponse
     */
    public function store(DeviceRequest $request, DeviceService $deviceService): JsonResponse
    {
        $deviceData = $request->validated();

        $response = $deviceService->createDevice($deviceData);

        if (null === $response) {
            return response()->json([
                'error' => 'An error occurred while registering device',
            ], 500);
        }

        return response()->json([
            'device' => new DeviceResource($response),
        ], Response::HTTP_OK);
    }
}
