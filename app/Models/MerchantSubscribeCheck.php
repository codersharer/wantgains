<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchantSubscribeCheck extends Model
{
    protected $table = 'merchant_subscribe_checks';

    protected $fillable = [
        'domain',
        'domain_id',
        'merchant_id',
        'estimate_can_subscribe',
        'real_can_subscribe',
        'subscribe_keyword',
        'http_code',
        'created_at',
        'updated_at',
    ];
}
