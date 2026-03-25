<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Custom primary key
    protected $primaryKey = 'category_id';

    // Fillable fields for mass assignment
    protected $fillable = [
        'category_name',
        'description',
    ];

    /**
     * Relationship: Category has many products
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'category_id');
    }
}