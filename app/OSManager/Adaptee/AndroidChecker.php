<?php

namespace App\OSManager\Adaptee;

use Carbon\Carbon;
use App\OSManager\BaseChecker;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AndroidChecker extends BaseChecker
{
    const USERNAME = 'android';
    const PASSWORD = 'oreo1';

    /**
     * @param int   $receipt
     * @param array $credentials
     *
     * @return JsonResponse
     */
    public function checkReceipt(int $receipt, array $credentials): JsonResponse
    {
        if ($credentials['username'] !== self::USERNAME || $credentials['password'] !== self::PASSWORD) {
            return response()->json([
                'status' => false,
                'expire_date' => null,
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $lastDigit = substr($receipt, -1);

        $isOddNumber = $lastDigit % 2 != 0;

        if (!$isOddNumber) {
            return response()->json([
                'status' => false,
                'expire_date' => null,
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->json([
            'status' => true,
            'expire_date' => Carbon::now()->addYear()->format('Y-m-d H:i:s'),
        ], Response::HTTP_OK);
    }
}
