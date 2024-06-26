<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaidItem extends Model
{
    use HasFactory;
    protected $fillable = ['payment_id', 'book_id'];
    protected $with=['book'];
    public function book()
    {
        return $this->belongsTo(Book::class, "book_id", "uuid");
    }
}
