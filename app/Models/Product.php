<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'code',
        'name',
        'category_id',
        'stock',
        'storage_location',
        'condition',
        'image_path',
        'description'
    ];

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the borrowing details for this product.
     */
    public function borrowingDetails()
    {
        return $this->hasMany(BorrowingDetail::class);
    }
}
