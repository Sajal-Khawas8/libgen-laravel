<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = ["name", "email", "password", "address", "image"];

    public function role()
    {
        return $this->hasOne(Role::class, "id", "role");
    }

    public function setNameAttribute(string $name)
    {
        $this->attributes["name"] = ucwords($name);
    }
    public function setEmailAttribute(string $email)
    {
        $this->attributes["email"] = strtolower($email);
    }
    public function setAddressAttribute(string $address)
    {
        $this->attributes["address"] = ucwords($address);
    }
    public function setPasswordAttribute(string $password)
    {
        $this->attributes["password"] = bcrypt($password);
    }
    public function setImageAttribute(string $path)
    {
        $this->attributes["image"] = Storage::disk("uploads")->url("users/" . $path);
    }
}
