<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerSupervisor extends Model
{
    use HasFactory;

    protected $attributes = [
        'id' => null,
        'customer_id' => null,
        'name' => null,
        'method' => null,
        'email' => null,
        'created_at' => null,
        'updated_at' => null,
    ];
}
