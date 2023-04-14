<?php

namespace App\Http\Rules;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Arr;
use App\Models\Supplier;
use App\Http\Services\ProductFilterService;
use App\Utils\FormatDataUtil;
use Illuminate\Support\Facades\Cache;
use App\Utils\PaginateJsonUtil;

class ListAllProductsRule {
    // list all products of both suppliers
    public function listAllProducts($filters)
    {
        $products = Cache::get('products', null);

        if (!isset($products)) {
            $responses = Http::pool(function (Pool $pool) {
                Supplier::get()->each(function (Supplier $supplier) use ($pool) {
                    return $pool->as($supplier->name)->get($supplier->api_base_url);
                });
            });

            if (!$this->checkingIfAtLeastOneSupplierIsUp($responses))
                return response()->error([ 'message' => 'Suppliers API are current unavailable.', 'statusCode' => 502 ]);

            $products = $this->getProductsFromResponses($responses);

            // 1 hour in cache
            if (!Cache::has('products')) Cache::put('products', $products, 60 * 60 * 60);
        }

        if (count($filters) > 0) $products = (new ProductFilterService())->filter($filters, $products);

        return response()->success([ 'data' => (new PaginateJsonUtil())->paginate($products) ]);
    }

    private function checkingIfAtLeastOneSupplierIsUp($responses)
    {
        foreach ($responses as $response)
            if ($response->successful()) return true;

        return false;
    }

    // treating each product of response to grant the consistency of the data
    private function getProductsFromResponses($responses)
    {
        $formatDataUtil = new FormatDataUtil();
        $validProducts = [];

        foreach($responses as $supplier => $response)
            foreach ($response->json() as $item) $validProducts[] = $formatDataUtil->format($this->preventFromInvalidData($item, $supplier));

        // removing null values
        $validProducts = array_filter($validProducts, fn ($value) => !is_null($value));

        // sorting array by name, to mix both brazilian and europen products
        return array_values(Arr::sort($validProducts, function ($product) {
            return $product['name'];
        }));

        return $validProducts;
    }

    // check if the current value is a valid value, the API from brazilian_provider is dupplicating the list of products inside main product list
    private function preventFromInvalidData($item, $supplier)
    {
        if (!isset($item[0])) return [ ...$item, 'supplier' => $supplier ];
    }
}
