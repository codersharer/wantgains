<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    CONST STATUS_ACTIVE = 1;
    CONST STATUS_INACTIVE = 0;

    protected $fillable = [
        'product_id_in_aff',
        'affiliate_id',
        'id_in_aff',
        'domain_id',
        'domain',
        'name',
        'merchant_id',
        'category',
        'promotion_start_at',
        'promotion_end_at',
        'is_promotion',
        'status',
        'image_url',
        'price',
        'real_price',
        'description',
        'sku',
        'track_link',
        'destination_url',
        'updated_at',
    ];
}
