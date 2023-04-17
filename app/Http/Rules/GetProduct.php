<?php

namespace App\Http\Rules;

use Illuminate\Support\Facades\Http;
use App\Models\Supplier ;
use App\Http\Services\ProductFilterService;
use App\Utils\FormatDataUtil;
use Illuminate\Support\Facades\Cache;
use App\Utils\PaginateJsonUtil;
use Illuminate\Http\JsonResponse;

class GetProduct {
    // list all products of both suppliers
    public function getProduct($supplierName, $productId): JsonResponse
    {
        $supplier = Supplier::where('name', $supplierName)->first();

        if (!$supplier) return response()->error([ 'message' => 'Not found.', 'statusCode' => 404 ]);

        $cacheName = "product-" . $supplierName . '-' . $productId;

        $product = Cache::get($cacheName, null);

        if (!isset($product)) {
            $url = $supplier->api_base_url . '/' . $productId;
            $response = Http::get($url);

            if ($response->failed())
                return response()->error([ 'message' => "Could not retrieve data from $supplier->name.", 'statusCode' => $response->status() ]);

            $product = $response->json();

            // 1 hour in cache
            if (!Cache::has($cacheName)) Cache::put($cacheName, $product, 60 * 60 * 60);
        }

        $product = (new FormatDataUtil())->format($product, $supplier->name);

        return response()->success([ 'data' => $product ]);
    }
}
