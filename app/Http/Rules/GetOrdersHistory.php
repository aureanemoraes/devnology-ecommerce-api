<?php

namespace App\Http\Rules;

use App\Http\Resources\OrderResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Utils\PaginateJsonUtil;

class GetOrdersHistory {
    public function history(Request $request): JsonResponse
    {
        $history = (new PaginateJsonUtil())->paginate(
            OrderResource::collection(
                Order::where('user_id', $request->user()->id)->get()
            )
        );

        return response()->success(
            [ 'data' =>  $history ]
        );
    }
}
