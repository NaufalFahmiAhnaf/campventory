<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'description'];

    /**
     * Get the products associated with this category.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
