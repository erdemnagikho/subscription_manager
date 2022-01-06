<?php

namespace App\OSManager;

use Illuminate\Http\JsonResponse;
use App\OSManager\Target\IReceiptChecker;

class ReceiptChecker
{
    private IReceiptChecker $receiptChecker;

    public function __construct(IReceiptChecker $receiptChecker)
    {
        $this->receiptChecker = $receiptChecker;
    }

    /**
     * @param int   $receipt
     * @param array $credentials
     *
     * @return JsonResponse
     */
    public function checkReceipt(int $receipt, array $credentials): JsonResponse
    {
        return $this->receiptChecker->checkReceipt($receipt, $credentials);
    }
}
