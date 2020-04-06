<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Program extends Model
{
    use SoftDeletes;

    CONST SUPPORT_DEEP = 1;
    CONST NOT_SUPPORT_DEEP = 0;

    CONST STATUS_ACTIVE_IN_AFF = 1;
    CONST STATUS_INACTIVE_IN_AFF = 0;

    CONST STATUS_ACTIVE_DASHBOARD = 1;
    CONST STATUS_INACTIVE_DASHBOARD = 0;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'affiliate_id',
        'name',
        'category',
        'country',
        'advertise_type',
        'homepage',
        'domain',
        'domain_id',
        'description',
        'id_in_aff',
        'commission_rate',
        'status_in_aff',
        'status_in_dashboard',
        'support_deep',
        'default_track_link',
        'real_track_link',
        'updated_at',
        'seven_day_epc',
        'three_month_epc',
        'merchant_name',
        'merchant_id',
    ];

    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class);
    }

}
