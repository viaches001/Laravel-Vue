<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    use HasFactory;

    public function permission () {
        return $this->belongsTo('App\Models\Permission', 'permission_id', 'id');
    }

    protected $attributes = [
        'id' => null,
        'user_id' => null,
        'permission_id' => null,
    ];
}