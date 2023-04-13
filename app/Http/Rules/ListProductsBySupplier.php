<?php

namespace App\Http\Rules;

use Illuminate\Support\Facades\Http;
use App\Models\Supplier ;
use App\Http\Services\ProductFilterService;
use App\Utils\FormatDataUtil;

class ListProductsBySupplier {
    // list all products of both suppliers
    public function listProductsBySupplier($supplierId, $filters)
    {
        $supplier = Supplier::findOrFail($supplierId);

        $response = Http::get($supplier->api_base_url);

        if ($response->failed())
            return response()->error("Could not retrieve data from $supplier->name.", $response->status());

        $products = (new FormatDataUtil())->formatArray($response->json());
        // $products = $response->json();

        if (count($filters) > 0) $products = (new ProductFilterService())->filter($filters, $products);

        return response()->success($products);
    }
}
