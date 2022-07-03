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
            Product::select('id', 'product_name', 'color_id', 'material_id')
            ->with([
                'variations' => function (HasMany $query) {
                    $query->select('products.id', 'products.product_parent_id', 'color_id', 'material_id')
                        ->with([
                            'color' => function (BelongsTo $query) {
                                $query->select('colors.id', 'colors.color_name');
                            },
                            'material' => function (BelongsTo $query) {
                                $query->select('materials.id', 'materials.material_name');
                            }
                        ]);
                },
                'color' => function (BelongsTo $query) {
                    $query->select('colors.id', 'colors.color_name');
                },
                'material' => function (BelongsTo $query) {
                    $query->select('materials.id', 'materials.material_name');
                }
            ])
            ->where('product_parent_id', null)
            ->paginate(10);
    }
}
