<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    protected $fillable = [
        'name',
        'domain_id',
        'slug',
        'domain',
        'logo',
        'updated_at',
    ];
}
