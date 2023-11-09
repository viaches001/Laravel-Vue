<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;
use Inertia\Inertia;
use App\Models\Customer;
use App\Models\User;
use App\Models\Workorder;
use App\Models\TimesheetEntry;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    public function index()
    {
        $customers = Customer::orderBy('name')->get();
        $users = User::orderBy('name')->get();

        // If given initial variables
        $initial_report_data = [
            'report' => request('report', 'customer_summary'),
            'customer_id' => request('customer_id', 'all') != 'all' ? floatval(request('customer_id')) : 'all',
            'workorder_id' => request('workorder_id', 'all') != 'all' ? floatval(request('workorder_id')) : 'all',
            'user_id' => request('user_id', 'all') != 'all' ? floatval(request('user_id')) : 'all',
            'from_date' => request('from_date', date('Y-m-d', strtotime('first day of this month'))),
            'to_date' => request('to_date', date('Y-m-d', strtotime('last day of this month'))),
            'onload' => request('onload', false),
        ];

        return Inertia::render('Reports', compact('customers', 'users', 'initial_report_data'));
    }

    public function customer_summary()
    {
        $customer_id = request('customer_id');
        $from_date = date('Y-m-d', strtotime(request('from_date')));
        $to_date = date('Y-m-d', strtotime(request('to_date')));

        $workorders = Workorder::when($customer_id !== 'all', function ($query) use ($customer_id) {
                                    $query->where('customer_id', $customer_id);
                                })
                                ->whereRaw("EXISTS(SELECT t.id FROM timesheet_entries t WHERE t.workorder_id = workorders.id AND t.date BETWEEN '$from_date' AND '$to_date' AND deleted_at IS NULL)")
                                ->orderBy('job_name')
                                ->without('timesheet_entries')
                                ->get();
        
        $customer_data = [];
        foreach ($workorders as $workorder)
        {
            if (!isset($customer_data[$workorder->customer_id])) $customer_data[$workorder->customer_id] = [];

            $customer_data[$workorder->customer_id]['timesheet_entries'] = TimesheetEntry::where('workorder_id', $workorder->id)
                                                            ->whereBetween('date', [$from_date, $to_date])
                                                            ->with('created_by')
                                                            ->orderBy('date')
                                                            ->orderBy('in')
                                                            ->orderBy('out')
                                                            ->get();
                                                            
            $timesheet_entry_items = [];
            foreach ($workorder->timesheet_entries as $timesheet_entry)
            {
                foreach ($timesheet_entry->items as $item)
                {
                    $timesheet_entry_items[] = $item;
                }
            }

            $customer_data[$workorder->customer_id]['timesheet_entry_items'] = $timesheet_entry_items;
        }

        return $customer_data;
    }

    public function job_summary()
    {
        $customer_id = request('customer_id');
        $workorder_id = request('workorder_id');
        $from_date = date('Y-m-d', strtotime(request('from_date')));
        $to_date = date('Y-m-d', strtotime(request('to_date')));

        $workorders = Workorder::when($customer_id !== 'all', function ($query) use ($customer_id) {
                                    $query->where('customer_id', $customer_id);
                                })
                                ->when($workorder_id !== 'all', function ($query) use ($workorder_id) {
                                    $query->where('id', $workorder_id);
                                })
                                ->whereRaw("EXISTS(SELECT t.id FROM timesheet_entries t WHERE t.workorder_id = workorders.id AND t.date BETWEEN '$from_date' AND '$to_date' AND deleted_at IS NULL)")
                                ->orderBy('job_name')
                                ->without('timesheet_entries')
                                ->get();
        
        $workorders_by_customer = [];
        foreach ($workorders as $workorder)
        {
            $workorder->timesheet_entries = TimesheetEntry::where('workorder_id', $workorder->id)
                                                            ->whereBetween('date', [$from_date, $to_date])
                                                            ->with('created_by')
                                                            ->orderBy('date')
                                                            ->orderBy('in')
                                                            ->orderBy('out')
                                                            ->get();
                                                            
            $timesheet_entry_items = [];
            foreach ($workorder->timesheet_entries as $timesheet_entry)
            {
                foreach ($timesheet_entry->items as $item)
                {
                    $timesheet_entry_items[] = $item;
                }
            }

            $workorder->timesheet_entry_items = $timesheet_entry_items;

            if (!isset($workorders_by_customer[$workorder->customer_id])) $workorders_by_customer[$workorder->customer_id] = [];
            $workorders_by_customer[$workorder->customer_id][] = $workorder;
        }

        return $workorders_by_customer;
    }

    public function payroll()
    {
        $user_id = request('user_id');
        $from_date = date('Y-m-d', strtotime(request('from_date')));
        $to_date = date('Y-m-d', strtotime(request('to_date')));

        $timesheet_entries = TimesheetEntry::whereBetween('date', [$from_date, $to_date])
                                            ->when($user_id !== 'all', function ($query) use ($user_id) {
                                                $query->where('created_by_user_id', $user_id);
                                            })
                                            ->with('workorder')
                                            ->orderBy('created_by_user_id')
                                            ->orderBy('date')
                                            ->orderBy('in')
                                            ->orderBy('out')
                                            ->get();
        
        $timesheet_entries_by_user = [];
        foreach ($timesheet_entries as $timesheet_entry)
        {
            if (!isset($timesheet_entries_by_user[$timesheet_entry->created_by_user_id])) $timesheet_entries_by_user[$timesheet_entry->created_by_user_id] = [];
            $timesheet_entries_by_user[$timesheet_entry->created_by_user_id][] = $timesheet_entry;
        }

        return $timesheet_entries_by_user;
    }
}
