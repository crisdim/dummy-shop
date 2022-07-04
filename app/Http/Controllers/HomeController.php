<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Services\ImportProducts;
use App\Jobs\ImportProductsJob;

class HomeController extends Controller
{
    public function index(ImportProductsJob $importProductsJob)
    {
        $importProductsJob->dispatch();

        //(new ImportProducts())->importProducts();

        return view('home');
    }
}
