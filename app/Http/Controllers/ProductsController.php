<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return
            Product::select('id', 'product_image', 'product_id', 'product_type', 'product_name', 'product_stock', 'product_rating', 'product_sales', 'color_id', 'material_id')
            ->selectRaw('ROUND((product_price / 100.0), 2) as product_price')
            ->with([
                'variations' => function (HasMany $query) {
                    $query->select('id', 'product_parent_id', 'color_id', 'material_id')
                        ->with([
                            'color' => function (BelongsTo $query) {
                                $query->select('id', 'color_name');
                            },
                            'material' => function (BelongsTo $query) {
                                $query->select('id', 'material_name');
                            }
                        ]);
                },
                'color' => function (BelongsTo $query) {
                    $query->select('id', 'color_name');
                },
                'material' => function (BelongsTo $query) {
                    $query->select('id', 'material_name');
                }
            ])
            ->where('product_parent_id', null)
            ->paginate(10);
    }
}
