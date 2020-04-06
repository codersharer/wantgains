<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutGoingTracking extends Model
{
    protected $table = 'outgoing_tracking';

    protected $fillable = [
        'domain_id',
        'program_id',
        'affiliate_id',
        'ip',
        'user_agent',
        'track_link',
        'sid',
        'type',
        'product_id',
        'merchant_id',
    ];
}
