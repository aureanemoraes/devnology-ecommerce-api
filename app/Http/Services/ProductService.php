<?php

namespace App\Http\Services;

use App\Http\Rules\ListAllProductsRule;

class ProductService {
    public function listAllProducts()
    {
        return (new ListAllProductsRule())->listAllProducts();
    }
}
