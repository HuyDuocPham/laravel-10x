<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product'; // Chỉ định class Product  tương tác với table tên 'product'
    protected $primaryKey = 'id'; //default

    public $fillable = [
        'id',
        'name',
        'slug',
        'price',
        'discount_price',
        'description',
        'short_description',
        'information',
        'qty',
        'shipping',
        'weight',
        'status',
        'image_url',
        'product_category_id',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }
}
