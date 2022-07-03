<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
            //response()
            //->json(
            Product::select('id', 'product_name', 'color_id', 'material_id')
            ->with([
                'variations' => function (HasMany $query) {
                    $query->select('products.id', 'products.product_parent_id', 'color_id', 'material_id')
                        ->with([
                            'color' => function ($query) {
                                $query->select('colors.id', 'colors.color_name');
                            },
                            'material' => function ($query) {
                                $query->select('materials.id', 'materials.material_name');
                            }
                        ]);
                },
                'color' => function ($query) {
                    $query->select('colors.id', 'colors.color_name');
                },
                'material' => function ($query) {
                    $query->select('materials.id', 'materials.material_name');
                }
            ])
            ->where('product_parent_id', null)
            ->paginate(10);
        //);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
