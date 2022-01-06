<?php

namespace App\OSManager\Target;

use Illuminate\Http\JsonResponse;

interface IReceiptChecker
{
    /**
     * @param int   $receipt
     * @param array $credentials
     *
     * @return JsonResponse
     */
    public function checkReceipt(int $receipt, array $credentials): JsonResponse;
}
