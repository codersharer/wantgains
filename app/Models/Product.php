<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    CONST STATUS_ACTIVE = 1;
    CONST STATUS_INACTIVE = 0;

    protected $guarded = [];

}
