<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchantCategory extends Model
{
    const HANDLE = 1;
    const NOT_HANDLE = 0;

    protected $fillable = [
        'merchant_id',
        'category'
    ];
}
