<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailCampaign extends Model
{
    protected $fillable = [
        'created_by',
        'subject',
        'body',
        'recipient_type',
        'recipients',
        'attachments',
        'sent_count',
        'failed_count',
    ];

    protected $casts = [
        'recipients' => 'array',
        'attachments' => 'array',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}