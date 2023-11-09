<?php

namespace App\Observers;

use App\Models\Customer;

class CustomerObserver
{
    /**
     * Handle the Customer "creating" event.
     *
     * @param  \App\Models\Customer  $customer
     * @return void
     */
    public function creating(Customer $customer)
    {
        $customer->created_by_user_id = auth()->user()->id;
        $customer->updated_by_user_id = auth()->user()->id;
    }

    /**
     * Handle the Customer "created" event.
     *
     * @param  \App\Models\Customer  $customer
     * @return void
     */
    public function created(Customer $customer)
    {
        //
    }

    /**
     * Handle the Customer "updating" event.
     *
     * @param  \App\Models\Customer  $customer
     * @return void
     */
    public function updating(Customer $customer)
    {
        $customer->updated_by_user_id = auth()->user()->id;
    }

    /**
     * Handle the Customer "updated" event.
     *
     * @param  \App\Models\Customer  $customer
     * @return void
     */
    public function updated(Customer $customer)
    {
        //
    }

    /**
     * Handle the Customer "deleted" event.
     *
     * @param  \App\Models\Customer  $customer
     * @return void
     */
    public function deleted(Customer $customer)
    {
        $customer->deleted_by_user_id = auth()->user()->id;
        $customer->save();
    }

    /**
     * Handle the Customer "restored" event.
     *
     * @param  \App\Models\Customer  $customer
     * @return void
     */
    public function restored(Customer $customer)
    {
        //
    }

    /**
     * Handle the Customer "force deleted" event.
     *
     * @param  \App\Models\Customer  $customer
     * @return void
     */
    public function forceDeleted(Customer $customer)
    {
        //
    }
}
