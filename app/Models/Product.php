<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'product_image', 'product_id', 'product_type', 'product_name', 'product_stock', 'product_price', 'product_rating', 'product_sales', 'product_parent_id', 'category_id', 'color_id', 'material_id', 'created_at', 'updated_at'];
    //protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id', 'id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id', 'id');
    }

    public function variations()
    {
        return $this->hasMany(Product::class, 'product_parent_id', 'id');
    }
}
