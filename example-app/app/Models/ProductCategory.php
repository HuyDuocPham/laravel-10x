<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;
    protected $table = 'product_category';

    public function products() // muốn dùng products phải dùng Query Builder
    {   
        return $this->hasMany(Product::class, 'product_category_id');
    }
}
