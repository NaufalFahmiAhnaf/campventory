<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    protected $fillable = [
        'borrower_name',
        'borrow_date',
        'expected_return_date',
        'status',
        'processed_by'
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'expected_return_date' => 'date',
    ];

    protected $appends = [
        'display_status',
        'is_overdue',
    ];

    /**
     * Status "Terlambat" dihitung dari status aktif dan tanggal batas kembali.
     */
    public function getIsOverdueAttribute(): bool
    {
        return in_array($this->status, ['Dipinjam', 'Terlambat'], true)
            && $this->expected_return_date
            && $this->expected_return_date->lt(Carbon::today());
    }

    public function getDisplayStatusAttribute(): string
    {
        if ($this->status === 'Dikembalikan') {
            return 'Dikembalikan';
        }

        return $this->is_overdue ? 'Terlambat' : 'Dipinjam';
    }

    public function scopeFilterByDisplayStatus($query, ?string $status)
    {
        if (!$status) {
            return $query;
        }

        return match ($status) {
            'Terlambat' => $query->where(function ($q) {
                $q->where('status', 'Terlambat')
                    ->orWhere(function ($nested) {
                        $nested->where('status', 'Dipinjam')
                            ->whereDate('expected_return_date', '<', Carbon::today());
                    });
            }),
            'Dipinjam' => $query->where('status', 'Dipinjam')
                ->whereDate('expected_return_date', '>=', Carbon::today()),
            'Dikembalikan' => $query->where('status', 'Dikembalikan'),
            default => $query,
        };
    }

    /**
     * Get the user who processed the borrowing.
     */
    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Get the details of the borrowing.
     */
    public function details()
    {
        return $this->hasMany(BorrowingDetail::class);
    }
}
