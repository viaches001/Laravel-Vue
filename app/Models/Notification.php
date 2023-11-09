<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory;

    protected $attributes = [
        'id' => null,
        'model' => null,
        'model_id' => null,
        'failed_at_notification_id' => null,
        'attempt_at' => null,
        'sent_at' => null,
        'failed_at' => null,
    ];
}
