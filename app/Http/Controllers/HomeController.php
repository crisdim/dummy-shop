<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Services\ImportProducts;
use App\Jobs\ImportProductsJob;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(ImportProductsJob $importProductsJob)
    {
        $importProductsJob->dispatch();

        //(new ImportProducts())->importProducts();

        //$categories = Category::all();
        $categories = [];
        return view('home', compact('categories'));
    }
}
