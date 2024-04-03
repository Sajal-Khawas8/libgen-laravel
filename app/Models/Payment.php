<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Payment extends Model
{
    use HasFactory;
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $with = ['paymentType', 'user', 'paidItem'];
    protected $casts = [
        'amount' => 'decimal:2',
    ];
    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class, "payment_type");
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
