<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // Relasi: Satu Category memiliki banyak Items (Barang)
    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
}