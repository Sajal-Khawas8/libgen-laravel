<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments=Payment::simplePaginate(10);
        // return $payments;
        return view("pages.admin.payments", compact("payments"));
    }
}
