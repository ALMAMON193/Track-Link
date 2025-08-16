<?php

namespace App\Http\Controllers\API\Shipper;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shipper\MMGPaymentRequest;
use App\Http\Resources\Shipper\MMGPaymentResource;
use App\Services\MMGPaymentService;
use Exception;

class MMGPaymentController extends Controller
{
    protected MMGPaymentService $paymentService;

    public function __construct(MMGPaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function merchantPayment(MMGPaymentRequest $request)
    {
        try {
            $payment = $this->paymentService->merchantPayment($request->validated());
            return new MMGPaymentResource($payment);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
