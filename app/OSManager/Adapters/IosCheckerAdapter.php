<?php

namespace App\OSManager\Adapters;

use Illuminate\Http\JsonResponse;
use App\OSManager\Adaptee\IosChecker;
use App\OSManager\Target\IReceiptChecker;

class IosCheckerAdapter implements IReceiptChecker
{
    /**
     * @param int   $receipt
     * @param array $credentials
     *
     * @return JsonResponse
     */
    public function checkReceipt(int $receipt, array $credentials): JsonResponse
    {
        $iosChecker = new IosChecker();

        return $iosChecker->checkReceipt($receipt, $credentials);
    }
}
