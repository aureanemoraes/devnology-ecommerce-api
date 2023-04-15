<?php

namespace App\Http\Rules;

use Illuminate\Http\JsonResponse;
use Ramsey\Uuid\Uuid;
use App\Models\Supplier;
use App\Http\Requests\Order\BuyOrderRequest;
use Illuminate\Support\Facades\Cache;
use App\Models\Order;
use App\Models\Product;

class BuyOrder {
    public function buy(BuyOrderRequest $request): JsonResponse
    {
        $inputs = $request->all();

        $resume = Cache::get($inputs['resume_id'], null);

        if (!isset($resume)) return response()->error([ 'message' => 'Something went wront. Please retry finish your order.', 'statusCode' => 404 ]);

        $order = Order::create([ 'user_id' => $request->user()->id, 'identifier' => (string) Uuid::uuid4() ]);

        foreach($resume['resume_info']['items'] as $item) {
            $product = $this->getProduct($item);
            $order->products()->attach($product->id, [ 'info' => $item ]);
        }


        return response()->success([ 'data' => $order->load('products') ]);
    }

    private function getProduct($item): Product
    {
        $product = Product::where('api_identifier', $item['id'])->first();

        if (!isset($product)) {
            $product = Product::create([
                'api_identifier' => $item['id'],
                'supplier_id' => Supplier::where('name', $item['supplier'])->first()->id
            ]);
        }

        return $product;
    }
}
