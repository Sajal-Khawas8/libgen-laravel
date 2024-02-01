<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentHistory extends Model
{
    use HasFactory;
    protected $table="rent_history";

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function book()
    {
        return $this->belongsTo(Book::class, "book_id", "uuid");
    }
}