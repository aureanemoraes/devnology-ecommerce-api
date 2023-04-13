<?php

namespace App\Http\Rules;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Arr;

class ListAllProductsRule {
    // list all products of both suppliers
    public function listAllProducts()
    {
        $responses = Http::pool(fn (Pool $pool) => [
            $pool->as('brazilian_provider')->get('http://616d6bdb6dacbb001794ca17.mockapi.io/devnology/brazilian_provider'),
            $pool->as('european_provider')->get('http://616d6bdb6dacbb001794ca17.mockapi.io/devnology/european_provider'),
        ]);

        if ($this->checkingIfAtLeastOneSupplierIsUp($responses)) {
            $products = $this->getProductsFromResponses($responses);

            return response()->success($products);
        }

        return response()->error('Suppliers API are current unavailable.', 502);
    }

    private function checkingIfAtLeastOneSupplierIsUp($responses)
    {
        foreach ($responses as $response)
            if ($response->ok()) return true;

        return false;
    }

    // treating each product of response to grant the consistency of the data
    private function getProductsFromResponses($responses)
    {
        $validProducts = [];

        foreach($responses as $supplier => $response)
            foreach ($response->json() as $item) $validProducts[] = $this->preventFromInvalidData($item, $supplier);

        // removing null values
        $validProducts = array_filter($validProducts, fn ($value) => !is_null($value));

        // sorting array by name, to mix both brazilian and europen products
        return array_values(Arr::sort($validProducts, function ($product) {
            if (isset($product['nome'])) return $product['nome'];
            if (isset($product['name'])) return $product['name'];
        }));

        return $validProducts;
    }

    // check if the current value is a valid value, the API from brazilian_provider is dupplicating the list of products inside main product list
    private function preventFromInvalidData($item, $supplier)
    {
        if (!isset($item[0])) return [ ...$item, 'supplier' => $supplier ];
    }
}
