<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\OrderService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Order\BuyOrderRequest;
use App\Http\Requests\Order\ResumeOrderRequest;

class OrderController extends Controller
{
    private $orderService;

    function __construct()
    {
        $this->orderService = new OrderService();
    }

    public function history(Request $request): JsonResponse
    {
        return (new OrderService())->history($request);
    }

    public function resume(ResumeOrderRequest $request)
    {
        return (new OrderService())->resume($request);
    }

    public function buy(BuyOrderRequest $request): JsonResponse
    {
        return (new OrderService())->buy($request);
    }
}
