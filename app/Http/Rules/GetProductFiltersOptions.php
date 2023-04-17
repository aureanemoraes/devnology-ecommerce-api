<?php

namespace App\Http\Rules;

use Illuminate\Support\Facades\Cache;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

class GetProductFiltersOptions {
    // list all products of both suppliers
    public function getProductFiltersOptions(): JsonResponse
    {
        $products = Cache::get('products', null);

        /**
         *
         * 'hasDiscount', // boolean
         *   'discountValue', // array
         *   'adjective', // array
         *   'material', // array
         *   'priceMin', // string
         *   'priceMax', // string
         *   'department', // string
         *   'category', // string
         *   'search'
         */


        // discountValue
        $discountsOptions = Arr::map($products, function($product) {
            return isset($product['discountValue']) ? [ 'value' => $product['discountValue'], 'label' => ($product['discountValue'] * 100) . '%' , 'filter' => 'discountValue' ] : null;
        });

        $discountsOptions = array_filter($discountsOptions, fn ($value) => !is_null($value));
        $discountsOptions = array_merge(array_unique($discountsOptions, SORT_REGULAR));

        // adjective
        $adjectiveOptions = Arr::map($products, function($product) {
            return isset($product['details']['adjective']) ? [ 'value' => $product['details']['adjective'], 'label' => strtoupper($product['details']['adjective']), 'filter' => 'adjective' ] : null;
        });

        $adjectiveOptions = array_filter($adjectiveOptions, fn ($value) => !is_null($value));
        $adjectiveOptions = array_merge(array_unique($adjectiveOptions, SORT_REGULAR));

        // material
        $materialOptions = Arr::map($products, function($product) {
            return isset($product['details']['material']) ? [ 'value' => $product['details']['material'], 'label' => strtoupper($product['details']['material']), 'filter' => 'material' ] : null;
        });

        $materialOptions = array_filter($materialOptions, fn ($value) => !is_null($value));
        $materialOptions= array_merge(array_unique($materialOptions, SORT_REGULAR));

        // price
        $priceOptions = Arr::map($products, function($product) {
            return isset($product['price']) ? floatval($product['price']) : null;
        });

        $priceOptions = array_filter($priceOptions, fn ($value) => !is_null($value));
        $priceOptions = array_merge(array_unique($priceOptions, SORT_REGULAR));
        $priceMin = min($priceOptions);
        $priceMax = max($priceOptions);

        $priceOptions = [ ['min' => $priceMin, 'filter' => 'price' ], [ 'max' => $priceMax, 'filter' => 'price' ]  ];

        // department
        $departmentOptions = Arr::map($products, function($product) {
            return isset($product['department']) ? [ 'value' => $product['department'], 'label' => strtoupper($product['department']), 'filter' => 'department' ] : null;
        });

        $departmentOptions = array_filter($departmentOptions, fn ($value) => !is_null($value));
        $departmentOptions = array_merge(array_unique($departmentOptions, SORT_REGULAR));

        // category
        $categoryOptions = Arr::map($products, function($product) {
            return isset($product['category']) ? [ 'value' => $product['category'], 'label' => strtoupper($product['category']), 'filter' => 'category' ] : null;
        });

        $categoryOptions = array_filter($categoryOptions, fn ($value) => !is_null($value));
        $categoryOptions = array_merge(array_unique($categoryOptions, SORT_REGULAR));


        $filtersOptions = [
            $discountsOptions,
            $adjectiveOptions,
            $materialOptions,
            $priceOptions,
            $departmentOptions,
            $categoryOptions,
        ];

        // dd($filtersOptions);

        return response()->success([ 'data' => $filtersOptions ]);
    }
}
