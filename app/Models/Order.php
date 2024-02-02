<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function book()
    {
        return $this->belongsTo(Book::class, "book_id", "uuid");
    }

    public function getDurationAttribute()
    {
        return Carbon::parse($this->issue_date)->diffInDays($this->due_date);
    }

    public function getOverdueDaysAttribute()
    {
        return Carbon::now()->isBefore($this->due_date)
            ? 0
            : Carbon::parse($this->due_date)->diffInDays();
    }

    public function getRentAttribute(){
        return $this->duration * $this->book->rent;
    }

    public function getFineAttribute(){
        return $this->overdueDays * $this->book->fine;
    }
}
