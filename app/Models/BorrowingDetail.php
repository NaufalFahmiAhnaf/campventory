<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowingDetail extends Model
{
    protected $fillable = [
        'borrowing_id',
        'product_id',
        'quantity',
        'returned_at'
    ];

    protected $casts = [
        'returned_at' => 'datetime',
    ];

    /**
     * Get the borrowing parent transaction.
     */
    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class);
    }

    /**
     * Get the product that was borrowed.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
