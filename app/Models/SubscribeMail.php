<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscribeMail extends Model
{
    protected $fillable = [
        'merchant_id',
        'source',
        'mail_id',
        'content',
        'created_at',
        'updated_at',
        'subject',
        'domain',
        'send_at',
    ];
}
