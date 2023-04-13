<?php

namespace App\Http\Services;

use App\Http\Rules\ListAllProductsRule;
use App\Http\Rules\ListProductsBySupplier;
use App\Models\Supplier;

class ProductService {
    public function listAllProducts($filters)
    {
        return (new ListAllProductsRule())->listAllProducts($filters);
    }

    public function listProductsBySupplier($suplierId, $filters)
    {
        return (new ListProductsBySupplier())->listProductsBySupplier($suplierId, $filters);
    }
}
