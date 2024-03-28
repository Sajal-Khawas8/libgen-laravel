<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cart extends Model
{
    use HasFactory;
    
    protected $table="cart";
    protected $fillable=["user_id", "book_id"];

    public function Book(): HasOne
    {
        return $this->hasOne(Book::class, "uuid", "book_id");
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id", "uuid");
    }
}
