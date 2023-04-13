<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Cache;

class ShoppingCartController extends Controller
{
    public function listItems()
    {

    }

    public function addItem($itemId, Request $request)
    {
        $shopping_cart = [
            'id' => (string) Uuid::uuid4(),
            'item_id' => $itemId,
        ];

        \Illuminate\Support\Facades\Cache::put('key', 'value', $seconds = 10);

        $request->session()->put('shopping_cart', $shopping_cart);

        return response()->success($shopping_cart);
    }

    public function removeItem()
    {

    }

    public function buyItems()
    {

    }
}
