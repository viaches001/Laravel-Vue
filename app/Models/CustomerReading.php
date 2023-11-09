<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerReading extends Model
{
    use HasFactory;

    protected $attributes = [
        'id' => null,
        'customer_id' => null,
        'type' => null,
        'location' => null,
        'description' => null,
        'frequency_type' => null,
        'frequency_days' => null,
        'frequency' => null,
        'frequency_units' => null,
        'frequency_next_date' => null,
        'min_hours' => null,
        'max_hours' => null,
        'for' => null,
        'units' => null,
        'created_at' => null,
        'updated_at' => null,
    ];
}
