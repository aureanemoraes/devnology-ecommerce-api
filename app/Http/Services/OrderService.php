<?php

namespace App\Http\Services;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Order\ResumeOrderRequest;
use App\Http\Requests\Order\BuyOrderRequest;
use App\Http\Rules\GetOrdersHistory;
use App\Http\Rules\GetOrderResume;
use App\Http\Rules\BuyOrder;

class OrderService {
    public function history(Request $request): JsonResponse
    {
        return (new GetOrdersHistory())->history($request);
    }

    public function resume(ResumeOrderRequest $request): JsonResponse
    {
        return (new GetOrderResume())->resume($request);
    }

    public function buy(BuyOrderRequest $request): JsonResponse
    {
        return (new BuyOrder())->buy($request);
    }
}
