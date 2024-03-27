<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $primary="uuid";
    protected $fillable=['title', 'author', 'description', 'cover', 'category_id', 'rent', 'fine'];
    protected $casts = [
        'rent' => 'decimal:2',
        'fine' => 'decimal:2',
    ];
    protected $with=["category"];
    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($book) {
            $book->active=false;
            $book->save();
        });
    }
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, fn($query, $search) =>
            $query->where(fn($query) =>
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('author', 'like', '%' . $search . '%')
            )
        );

        $query->when($filters['category'] ?? false, fn($query, $category) =>
            $query->whereHas('category', fn ($query) =>
                $query->where('name', $category)
            )
        );
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function quantity(): BelongsTo
    {
        return $this->belongsTo(Quantity::class, "title", "book");
    }

    public function orders(){
        return $this->hasMany(Order::class,"book_id", "uuid");
    }
}
