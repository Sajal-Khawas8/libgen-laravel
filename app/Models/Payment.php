<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Payment extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    public $incrementing = false;
    protected $with = ['paymentType', 'user', 'paidItem'];
    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class, "type");
    }
    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }
    public function paidItem()
    {
        return $this->hasMany(PaidItem::class, "payment_id");
    }
}
