<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrawleLog extends Model
{
    const TYPE_PROGRAMS = 1;
    const TYPE_LINKFEEDS = 2;
    const TYPE_PRODUCTS = 3;

    const FINISH = 1;
    const NOT_FINISH = 0;

    protected $fillable = [
        'affiliate_id',
        'is_finish',
        'started_at',
        'updated_at',
        'finished_at',
        'current_page',
        'current_program_id',
        'type',
    ];
}
