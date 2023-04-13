<?php

namespace App\Http\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ProductFilterService {

    private $validFilters = [
        'hasDiscount', // boolean
        'discountValue', // array
        'adjective', // array
        'material', // array
        'priceMin', // string
        'priceMax', // string
        'department', // string
        'category', // string
        'search'
    ];

    // all filters will work with OR logic, except for price
    // price will work: price && ...filters
    // other filters: filter 1 OR filter 2 OR filter 3

    public function filter(Array $filters, Array $products)
    {
        $containValidFilters = count(array_intersect(array_keys($filters), $this->validFilters)) > 0 ? true : false;

        if (!$containValidFilters) return $products;

        $filteredProducts = [];

        if (isset($filters['search'])) {
            $substring = strtolower(trim($filters['search']));
            $filteredProducts = Arr::where($products, function (Array $product) use ($substring) {
                $containOnName = str_contains(strtolower($product['name']), $substring);
                $containOnDescription = str_contains(strtolower($product['description']), $substring);

                return $containOnName || $containOnDescription;
            });
        }

        foreach ($filters as $filter => $value) {
            switch ($filter) {
                case 'hasDiscount':
                    if (filter_var($value, FILTER_VALIDATE_BOOLEAN))
                        $result = Arr::where($products, function (Array $product) {
                            if (isset($product['hasDiscount'])) return $product['hasDiscount'] === true;
                        });

                        $filteredProducts = [ ...$filteredProducts, ...$result ];
                    break;
                case 'discountValue':
                    $discounts = explode(",", $value);

                    $result = Arr::where($products, function (Array $product) use ($discounts) {
                        if (isset($product['discountValue']) && $product['hasDiscount'] === true)
                            return in_array($product['discountValue'], $discounts);
                    });

                    $filteredProducts = [ ...$filteredProducts, ...$result ];

                    break;
                case 'adjective':
                    $adjectives = explode(",", strtolower($value));

                    $result = Arr::where($products, function (Array $product) use ($adjectives) {
                        if (isset($product['details']['adjective']))
                            return in_array(strtolower($product['details']['adjective']), $adjectives);
                    });

                    $filteredProducts = [ ...$filteredProducts, ...$result ];

                    break;
                case 'material':
                    $materials = explode(",", strtolower($value));

                    $result = Arr::where($products, function (Array $product) use ($materials) {
                        if (isset($product['details']['material'])) return in_array($product['details']['material'], $materials);
                    });

                    $filteredProducts = [ ...$filteredProducts, ...$result ];

                    break;
                case 'department':
                    $departments = explode(",", strtolower($value));

                    $result = Arr::where($products, function (Array $product) use ($departments) {
                        if (isset($product['department']))
                            return in_array(strtolower($product['department']), $departments);
                    });

                    $filteredProducts = [ ...$filteredProducts, ...$result ];

                    break;
                case 'category':
                    $categories = explode(",", strtolower($value));

                    $result = Arr::where($products, function (Array $product) use ($categories) {
                        if (isset($product['category']))
                            return in_array(strtolower($product['category']), $categories);
                    });

                    $filteredProducts = [ ...$filteredProducts, ...$result ];

                    break;

            }
        }

        // if no filter was specified, it will return all products
        $filteredProducts = count($filteredProducts) > 0 ? $filteredProducts : $products;

        if (isset($filters['priceMin']) || isset($filters['priceMax'])) {
            $priceMin = isset($filters['priceMin']) ? floatval($filters['priceMin']) : null;
            $priceMax = isset($filters['priceMax']) ? floatval($filters['priceMax']) : null;

            $filteredProducts = Arr::where($filteredProducts, function (Array $product) use ($priceMin, $priceMax) {
                $key = isset($product['price']) ? 'price' : 'preco';

                if (isset($product[$key])) {
                    if (isset($priceMin) && isset($priceMax))
                        return floatval($product[$key]) >= $priceMin && floatval($product[$key]) <= $priceMax;
                    else if (isset($priceMin))
                        return floatval($product[$key]) >= $priceMin;
                    else if (isset($priceMax))
                        return floatval($product[$key]) <= $priceMax;

                }
            });
        }

        return array_merge(array_unique($filteredProducts, SORT_REGULAR));
    }
}
