<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function colors_products()
    {
        return $this->hasMany(ColorProduct::class);
    }

    public function colors()
    {
        return $this->hasManyThrough(Color::class, ColorProduct::class, 'product_id', 'id');
    }

    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id', 'id');
    }

    public function materials_products()
    {
        return $this->hasMany(MaterialProduct::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id', 'id');
    }

    public function materials()
    {
        return $this->hasManyThrough(Material::class, MaterialProduct::class, 'product_id', 'id');
    }

    public function variations()
    {
        return $this->hasMany(Product::class, 'product_parent_id', 'id');
    }

    public function product_parent()
    {
        return $this->belongsTo(Product::class, 'product_parent_id', 'id');
    }
}
