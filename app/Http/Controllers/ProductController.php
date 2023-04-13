<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\ProductService;

class ProductController extends Controller
{
    private $productService;

    function __construct() {
        $this->productService = new ProductService();
    }

    public function listAllProducts()
    {
        return $this->productService->listAllProducts();
    }
}
