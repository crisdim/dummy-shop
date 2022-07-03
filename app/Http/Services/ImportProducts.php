<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Models\Category;
use App\Models\Color;
use App\Models\Material;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\Http;

class ImportProducts
{
    const EVERY_N_PRODUCT_IS_MAIN = 3;
    const API_URL = 'https://dummy-shop-api.keydev.eu/api/v1/products';

    public function importProducts()
    {
        $categories_flat = [];
        $categories = [];
        $products = [];
        $colors_flat = [];
        $colors = [];
        $materials_flat = [];
        $materials = [];

        $products_data = $this->getFromJson(self::API_URL);
        if ($products_data) {
            foreach ($products_data as $key => $item) {
                $products[] = [
                    'product_image' => $item->product_image_lg,
                    'product_id' => $item->_id,
                    'product_type' => $item->product_type,
                    'product_name' => $item->product_name,
                    'product_stock' => $item->product_stock,
                    'product_price' => $item->product_price,
                    'product_ratings' => $item->product_ratings,
                    'product_sales' => $item->product_sales,
                    'product_color' => $item->product_color,
                    'product_material' => $item->product_material,
                    'product_category' => $item->product_department,
                ];

                if (!in_array($item->product_department, $categories_flat)) {
                    $categories_flat[] = $item->product_department;
                    $categories[] = ['category_name' => $item->product_department];
                }

                if (!in_array($item->product_material, $materials_flat)) {
                    $materials_flat[] = $item->product_material;
                    $materials[] = ['material_name' => $item->product_material];
                }

                if (!in_array($item->product_color, $colors_flat)) {
                    $colors_flat[] = $item->product_color;
                    $colors[] = ['color_name' => $item->product_color];
                }
            }

            Category::upsert(
                $categories,
                ['category_name'],
                ['category_name']
            );

            Material::upsert(
                $materials,
                ['material_name'],
                ['material_name']
            );

            Color::upsert(
                $colors,
                ['color_name'],
                ['color_name']
            );

            $product_counter = 0;
            $parent_id = 0;
            foreach ($products as $product) {

                $prod = Product::updateOrCreate(
                    [
                        'product_id' => $product['product_id'],
                    ],
                    [
                        'product_image' => $product['product_image'],
                        'product_id' => $product['product_id'],
                        'product_type' => $product['product_type'],
                        'product_name' => $product['product_name'],
                        'product_stock' => $product['product_stock'],
                        'product_price' => (float) $product['product_price'] * 100,
                        'product_rating' => $product['product_ratings'],
                        'product_sales' => $product['product_sales'],
                        'product_parent_id' => ($product_counter % self::EVERY_N_PRODUCT_IS_MAIN === 0) ? null : $parent_id,
                        'category_id' => $this->getCategoryByName($product['product_category'])->id,
                        'material_id' => $this->getMaterialByName($product['product_material'])->id,
                        'color_id' => $this->getColorByName($product['product_color'])->id,
                    ]
                );

                if ($product_counter % self::EVERY_N_PRODUCT_IS_MAIN === 0) {
                    $parent_id = $prod->id;
                }

                /*
                $prod->colors_products()->updateOrCreate([
                    'color_id' => $this->getColorByName($product['product_color'])->id,
                ]);

                $prod->materials_products()->updateOrCreate([
                    'material_id' => $this->getMaterialByName($product['product_material'])->id,
                ]);
                */
                $product_counter++;
            }
        }
    }

    private function getCategoryByName(string $cat_name): ?Category
    {
        try {
            return Category::where('category_name', 'LIKE', '%' . $cat_name . '%')->select('id')->first();
        } catch (\Exception $ex) {
            return response()->view('home', ['message' => $ex->getMessage()], 500);
        }
    }

    private function getColorByName(string $product_color): ?Color
    {
        return Color::where('color_name', 'LIKE', '%' . $product_color . '%')->select('id')->first();
    }

    private function getMaterialByName(string $product_material): ?Material
    {
        return Material::where('material_name', 'LIKE', '%' . $product_material . '%')->select('id')->first();
    }

    private function getFromJson(string $url): ?array
    {
        $products = [];
        $page = 0;

        try {
            do {
                $page++;
                $response = Http::accept('application/json')->get($url, ['page' => $page]);

                if ($response->status() === 200) {
                    $json = $response->object();

                    if (isset($json) && is_array($json->data)) {
                        $products = array_merge($products, $json->data);
                    }
                }
            } while ($response->status() === 200);
        } catch (\Exception $ex) {
        }

        return $products;
    }
}
