<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Custom primary key
    protected $primaryKey = 'product_id';

    // Fillable fields for mass assignment
    protected $fillable = [
        'product_name',
        'description',
        'price',
        'stock_quantity',
        'category_id',
        'product_image',
    ];

    /**
     * Relationship: Product belongs to a category
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    /**
     * Relationship: Product has many order items
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id', 'product_id');
    }
}