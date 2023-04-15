<?php

namespace App\Http\Services;

use App\Http\Rules\ListAllProductsRule;
use App\Http\Rules\ListProductsBySupplier;
use Illuminate\Http\JsonResponse;

class ProductService {
    public function listAllProducts($filters): JsonResponse
    {
        return (new ListAllProductsRule())->listAllProducts($filters);
    }

    public function listProductsBySupplier($suplierId, $filters): JsonResponse
    {
        return (new ListProductsBySupplier())->listProductsBySupplier($suplierId, $filters);
    }
}
