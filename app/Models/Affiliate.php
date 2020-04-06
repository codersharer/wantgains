<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Affiliate extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    //兜底联盟id
    public static $worestIds = [
        2 //viglink
    ];
}
