<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

    /**
     * Scope a query to fuzzy search products by title and description.
     */
    public function scopeFuzzySearch(Builder $query, ?string $searchTerm)
    {
        if (blank($searchTerm)) {
            return $query;
        }

        // This checks similarity and orders the best matches (closest to the typo) first
        return $query->whereRaw('product_name % ?', [$searchTerm])
            ->orWhereRaw('description % ?', [$searchTerm])
            ->orderByRaw('similarity(product_name, ?) DESC', [$searchTerm]);
    }
}
