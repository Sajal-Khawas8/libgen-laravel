<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $primaryKey = "uuid";
    protected $fillable = ["name", "email", "password", "address", "image"];

    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($user) {
            $user->active=false;
            $user->save();
        });
    }

    public function scopeFilter($query)
    {
        if (request('search')) {
            $query->where('name', 'like', '%' . request('search') . '%')
            ->orWhere('email', 'like', '%' . request('search') . '%');
        }
    }

    public function roles()
    {
        return $this->hasOne(Role::class, "id", "role");
    }

    public function orders(){
        return $this->hasMany(Order::class,"user_id","uuid");
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
}
