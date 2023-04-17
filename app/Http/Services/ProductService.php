<?php

namespace App\Http\Services;

use App\Http\Rules\ListAllProductsRule;
use App\Http\Rules\ListProductsBySupplier;
use Illuminate\Http\JsonResponse;
use App\Http\Rules\GetProduct;
use App\Http\Rules\GetProductFiltersOptions;

class ProductService {
    public function listAllProducts($filters): JsonResponse
    {
        return (new ListAllProductsRule())->listAllProducts($filters);
    }

    public function listProductsBySupplier($suplierId, $filters): JsonResponse
    {
        return (new ListProductsBySupplier())->listProductsBySupplier($suplierId, $filters);
    }

    public function getProduct($supplierName, $productId): JsonResponse
    {
        return (new GetProduct())->getProduct($supplierName, $productId);
    }

    public function getProductFiltersOptions(): JsonResponse
    {
        return (new GetProductFiltersOptions())->getProductFiltersOptions();
    }
}
