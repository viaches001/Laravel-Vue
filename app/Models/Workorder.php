<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workorder extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function customer () {
        return $this->belongsTo('App\Models\Customer')->withTrashed();
    }

    public function customer_issue () {
        return $this->belongsTo('App\Models\CustomerIssue');
    }

    public function reading () {
        return $this->belongsTo('App\Models\Reading');
    }

    public function created_by () {
        return $this->belongsTo('App\Models\User', 'created_by_user_id', 'id')->withTrashed();
    }

    public function updated_by () {
        return $this->belongsTo('App\Models\User', 'updated_by_user_id', 'id')->withTrashed();
    }

    public function exported_by () {
        return $this->belongsTo('App\Models\User', 'exported_by_user_id', 'id')->withTrashed();
    }

    public function deleted_by () {
        return $this->belongsTo('App\Models\User', 'deleted_by_user_id', 'id')->withTrashed();
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
        'customer_issue_id' => null,
        'reading_id' => null,
        'created_by_user_id' => null,
        'updated_by_user_id' => null,
        'exported_by_user_id' => null,
        'deleted_by_user_id' => null,
        'status' => 0,
        'date' => null,
        'description' => null,
        'notes' => null,
        'completed_at' => null,
        'exported_at' => null,
        'created_at' => null,
        'updated_at' => null,
        'deleted_at' => null
    ];
}
