<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutGoing extends Model
{
    CONST IS_HANDLE = 1;
    CONST NOT_HANDLE = 0;

    const OUTGOING_KEY = ':OUT_GOING:';

    protected $fillable = [
        'affiliate_id',
        'affiliate_name',
        'domain_id',
        'domain',
        'program_id',
        'track_link',
        'is_handle',
        'op_name',
        'updated_at',
    ];
    protected $table = 'outgoing';
}
