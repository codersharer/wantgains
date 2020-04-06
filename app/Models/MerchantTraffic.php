<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchantTraffic extends Model
{
    protected $fillable = [
        'merchant_id',
        'pv',
        'pv_date',
        'domain',
    ];
    protected $table = 'merchant_traffics';
}
