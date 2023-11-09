<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reading extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function created_by () {
        return $this->belongsTo('App\Models\User', 'created_by_user_id', 'id');
    }

    public function updated_by () {
        return $this->belongsTo('App\Models\User', 'updated_by_user_id', 'id');
    }

    public function customer_reading () {
        return $this->belongsTo('App\Models\CustomerReading', 'customer_reading_id', 'id');
    }

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'created_date'
    ];

    public function getCreatedDateAttribute () {
        return $this->created_at == null ? null : $this->created_at->format('Y-m-d');
    }

    protected $attributes = [
        'id' => null,
        'customer_id' => null,
        'customer_reading_id' => null,
        'token' => null,
        'submitted_by_supervisor_id' => null,
        'submitted_by_user_id' => null,
        'verified_by_user_id' => null,
        'date' => null,
        'type' => null,
        'description' => null,
        'location' => null,
        'distribution_location' => null,
        'reading' => null,
        'units' => null,
        'submitted_at' => null,
        'verified_at' => null,
        'created_at' => null,
        'updated_at' => null,
        'deleted_at' => null
    ];
}
