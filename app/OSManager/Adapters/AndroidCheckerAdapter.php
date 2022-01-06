<?php

namespace App\OSManager\Adapters;

use Illuminate\Http\JsonResponse;
use App\OSManager\Adaptee\AndroidChecker;
use App\OSManager\Target\IReceiptChecker;

class AndroidCheckerAdapter implements IReceiptChecker
{
    /**
     * @param int   $receipt
     * @param array $credentials
     *
     * @return JsonResponse
     */
    public function checkReceipt(int $receipt, array $credentials): JsonResponse
    {
       $androidChecker = new AndroidChecker();

       return $androidChecker->checkReceipt($receipt, $credentials);
    }
}
