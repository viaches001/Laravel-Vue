<?php

namespace App\Observers;

use App\Models\Workorder;

class WorkorderObserver
{
    /**
     * Handle the Workorder "creating" event.
     *
     * @param  \App\Models\Workorder  $workorder
     * @return void
     */
    public function creating(Workorder $workorder)
    {
        $workorder->created_by_user_id = auth()->user()->id;
        $workorder->updated_by_user_id = auth()->user()->id;
    }

    /**
     * Handle the Workorder "created" event.
     *
     * @param  \App\Models\Workorder  $workorder
     * @return void
     */
    public function created(Workorder $workorder)
    {
        //
    }

    /**
     * Handle the Workorder "updating" event.
     *
     * @param  \App\Models\Workorder  $workorder
     * @return void
     */
    public function updating(Workorder $workorder)
    {
        $workorder->updated_by_user_id = auth()->user()->id;
    }

    /**
     * Handle the Workorder "updated" event.
     *
     * @param  \App\Models\Workorder  $workorder
     * @return void
     */
    public function updated(Workorder $workorder)
    {
        //
    }

    /**
     * Handle the Workorder "deleted" event.
     *
     * @param  \App\Models\Workorder  $workorder
     * @return void
     */
    public function deleted(Workorder $workorder)
    {
        $workorder->deleted_by_user_id = auth()->user()->id;
        $workorder->save();
    }

    /**
     * Handle the Workorder "restored" event.
     *
     * @param  \App\Models\Workorder  $workorder
     * @return void
     */
    public function restored(Workorder $workorder)
    {
        //
    }

    /**
     * Handle the Workorder "force deleted" event.
     *
     * @param  \App\Models\Workorder  $workorder
     * @return void
     */
    public function forceDeleted(Workorder $workorder)
    {
        //
    }
}
