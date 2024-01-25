<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Book extends Model
{
    use HasFactory;

    protected $primary="uuid";
    protected $with=["category"];
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function quantity(): BelongsTo
    {
        return $this->belongsTo(Quantity::class, "uuid", "book_id");
    }
}
