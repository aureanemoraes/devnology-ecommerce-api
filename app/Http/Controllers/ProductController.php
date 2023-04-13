<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\ProductService;
use App\Models\Supplier;

class ProductController extends Controller
{
    private $productService;

    function __construct() {
        $this->productService = new ProductService();
    }

    public function listAllProducts(Request $request)
    {
        return $this->productService->listAllProducts($request->query());
    }

    public function listProductsBySupplier($suplierId, Request $request)
    {
        return $this->productService->listProductsBySupplier($suplierId, $request->query());
    }
}
