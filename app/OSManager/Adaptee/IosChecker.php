<?php

namespace App\OSManager\Adaptee;

use Carbon\Carbon;
use App\OSManager\BaseChecker;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class IosChecker extends BaseChecker
{
    const USERNAME = 'ios';
    const PASSWORD = 'ios15';

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

        $isEvenNumber = $lastDigit % 2 == 0;

        if (!$isEvenNumber) {
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
