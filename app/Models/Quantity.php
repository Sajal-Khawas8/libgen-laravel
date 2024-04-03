<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quantity extends Model
{
    use HasFactory;

    protected $table="quantity";
    protected $fillable = ['book', 'copies', 'available'];

    public function book()
    {
        return $this->hasOne(Book::class, 'uuid', 'book_id');
    }
}
