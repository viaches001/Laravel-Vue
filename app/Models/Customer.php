<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Reading;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;

    const Status_None = 'none', Status_Await = 'await', Status_Overdue = 'overdue', Status_Submitted = 'submitted', Status_Approved = 'approved';
    const Status_Def = [
        Self::Status_None => ['value' => 0, 'label' => 'No Readings'],
        Self::Status_Await => ['value' => 1, 'label' => 'Await Reading'],
        Self::Status_Overdue => ['value' => 2, 'label' => 'Overdue'],
        Self::Status_Submitted => ['value' => 3, 'label' => 'Submitted'],
        Self::Status_Approved => ['value' => 4, 'label' => 'Approved'],
    ];

    public function supervisors () {
        return $this->hasMany('App\Models\CustomerSupervisor')->orderBy('name');
    }

    public function readings () {
        return $this->hasMany('App\Models\CustomerReading')->orderBy('type');
    }

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'address',
        'created_date',
        'status',
    ];

    public function getAddressAttribute() {
        $line1 = implode(', ', array_filter([$this->street1, $this->street2]));
        $line2 = implode(' ', array_filter([$this->city, $this->prov_state, $this->postal_zip]));

        $address = implode(', ', array_filter([$line1, $line2]));
        return $address;
    }

    public function getCreatedDateAttribute () {
        return $this->created_at == null ? null : $this->created_at->format('Y-m-d');
    }

    public function getStatusAttribute() {
        $readings = Reading::where('customer_id', $this->id)->where('date', date('Y-m-d'))->get();
        if (count($readings) > 0) {
            for ($i = 0; $i < count($readings); $i ++) {
                if (is_null($readings[$i]->verified_at))
                    break;
            }
            for ($i = 0; $i < count($readings); $i ++) {
                if (is_null($readings[$i]->submitted_at))
                    break;
            }
            return [
                'name' => Self::Status_Await,
                'notified_at' => date('Y-m-d H:i:s', strtotime($readings[0]->date . ' +' . $readings[0]->customer_reading->min_hours . ' hours')),
            ];
        }
        return [
            'name' => Self::Status_None,
        ];
    }

    protected $attributes = [
        'id' => null,
        'created_by_user_id' => null,
        'updated_by_user_id' => null,
        'deleted_by_user_id' => null,
        'name' => null,
        'email' => null,
        'phone' => null,
        'fax' => null,
        'street1' => null,
        'street2' => null,
        'city' => null,
        'prov_state' => null,
        'postal_zip' => null,
        'created_at' => null,
        'updated_at' => null,
        'deleted_at' => null
    ];
}
