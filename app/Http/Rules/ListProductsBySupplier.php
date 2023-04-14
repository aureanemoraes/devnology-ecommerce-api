<?php

namespace App\Http\Rules;

use Illuminate\Support\Facades\Http;
use App\Models\Supplier ;
use App\Http\Services\ProductFilterService;
use App\Utils\FormatDataUtil;
use Illuminate\Support\Facades\Cache;
use App\Utils\PaginateJsonUtil;
use Illuminate\Http\JsonResponse;

class ListProductsBySupplier {
    // list all products of both suppliers
    public function listProductsBySupplier($supplierId, $filters): JsonResponse
    {
        $supplier = Supplier::findOrFail($supplierId);

        $products = Cache::get('productsBySupplier', null);

        if (!isset($products)) {
            $response = Http::get($supplier->api_base_url);

            if ($response->failed())
                return response()->error([ 'message' => "Could not retrieve data from $supplier->name.", 'statusCode' => $response->status() ]);

            $products = $response->json();

            // 1 hour in cache
            if (!Cache::has('productsBySupplier')) Cache::put('productsBySupplier', $products, 60 * 60 * 60);
        }

        $products = (new FormatDataUtil())->formatArray($products);

        if (count($filters) > 0) $products = (new ProductFilterService())->filter($filters, $products);

        return response()->success([ 'data' => (new PaginateJsonUtil())->paginate($products) ]);
    }
}
