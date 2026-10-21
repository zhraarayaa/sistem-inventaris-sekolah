<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'item_id',
        'quantity',
        'loan_date',
        'due_date',
        'status',
    ];

    // Relasi: Loan dilakukan oleh satu User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Loan meminjam satu Item
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    // Relasi: Satu Loan memiliki satu ReturnLog (Pengembalian)
    public function returnLog(): HasOne
    {
        return $this->hasOne(ReturnLog::class);
    }
}