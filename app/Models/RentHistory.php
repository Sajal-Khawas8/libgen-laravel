<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentHistory extends Model
{
    use HasFactory;
    protected $table = "rent_history";

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
        return Carbon::parse($this->issue_date)->diffInDays($this->return_date);
    }

    public function getOverdueDaysAttribute()
    {
        return Carbon::parse($this->return_date)->isBefore($this->due_date)
            ? 0 : Carbon::parse($this->due_date)->diffInDays($this->return_date);
    }

}
