<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerSupervisor extends Model
{
    use HasFactory;

    public function customer () {
        return $this->belongsTo('App\Models\Customer', 'customer_id', 'id')->withTrashed();
    }

    public function resolved_by () {
        return $this->belongsTo('App\Models\User', 'resolved_by_user_id', 'id')->withTrashed();
    }

    public function reported_by () {
        return $this->belongsTo('App\Models\CustomerSupervisor', 'reported_by_supervisor_id', 'id');
    }

    protected $attributes = [
        'id' => null,
        'customer_id' => null,
        'resolved_by_user_id' => null,
        'reported_by_supervisor_id' => null,
        'issue' => null,
        'resolved_at' => null,
        'created_at' => null,
        'updated_at' => null,
    ];
}
