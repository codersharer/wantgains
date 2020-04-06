<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    const HANDLE = 1;
    const NOT_HANDLE = 0;

    protected $fillable = ['domain', 'updated_at'];
}
