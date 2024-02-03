<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table="category";

    public function getRouteKeyName()
    {
        return 'name';
    }

    public function setNameAttribute(string $name)
    {
        $this->attributes["name"] = ucwords($name);
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
