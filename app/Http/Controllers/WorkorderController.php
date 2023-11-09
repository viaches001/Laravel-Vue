<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;
use Inertia\Inertia;
use App\Models\Workorder;
use App\Models\Customer;
use Storage;
use Illuminate\Support\Facades\Validator;

class WorkorderController extends Controller
{
    public function index()
    {
        if (session('workorder_filter_status') === null || request('workorder_filter_status') !== null) session(['workorder_filter_status' => request('workorder_filter_status', 'incomplete')]);
        if (session('workorder_filter_status') === 'null' || session('workorder_filter_status') === null || session('workorder_filter_status') === 0) session(['workorder_filter_status' => 'incomplete']);

        $workorders = Workorder::when(session('workorder_filter_status') == 'incomplete', function ($query) {
                                    $query->where('status', '<', 2);
                                })
                                ->when(session('workorder_filter_status') == 'completed', function ($query) {
                                    $query->where('status', 2);
                                })
                                ->with('customer')
                                ->get();

        return Inertia::render('Workorders', [
            'workorders' => $workorders,
            'period' => 'custom',
            'from_date' => date('Y-m-d', strtotime('first day of this month')),
            'to_date' => date('Y-m-d', strtotime('last day of this month')),
            // 'from_date' => request('from_date', date('Y-m-d', strtotime('first day of this month'))),
            // 'to_date' => request('to_date', date('Y-m-d', strtotime('last day of this month'))),
            'status' => session('workorder_filter_status'),
        ]);
    }

    public function edit($workorder = null)
    {
        if ($workorder == null) $workorder = new Workorder ();
        else $workorder = Workorder::where('id', $workorder)->with('customer')->first();

        $customers = Customer::orderBy('name')->get();
        return Inertia::render('WorkorderEdit', ['workorder' => $workorder, 'customers' => $customers]);
    }

    public function store(Workorder $workorder)
    {
        $mode = $workorder->id === null ? 'add' : 'edit';

        // Validate the request
        $validate_rules = [
            'customer_id' => ['required'],
            'job_name' => ['required'],
            'quoted_by' => ['required'],
        ];

        $messages = [
            'required' => 'This field is required.',
            'email' => 'This field must be a valid email.'
        ];

        Validator::make(request()->all(), $validate_rules, $messages)->validate();

        // Persist the workorder information
        $workorder->customer_id = request('customer_id');
        $workorder->job_type_id = request('job_type_id');
        $workorder->status = request('status');
        $workorder->job_name = request('job_name');
        $workorder->job_address = request('job_address');
        $workorder->quoted_by = request('quoted_by');
        $workorder->po = request('po');
        $workorder->order_date = request('order_date');
        $workorder->job_notes = request('job_notes');
        $workorder->admin_notes = request('admin_notes');
        $workorder->save();

        // Persist the workorder documents
        $this->store_workorder_documents($workorder);

        // Persist the workorder items
        $this->store_workorder_items($workorder);

        // Persist the workorder images
        $this->store_workorder_images($workorder);

        // Detemine return message
        if ($mode === 'add') $message = "Workorder For \"" . $workorder->customer->name . "\" Added Successfully.";
        else $message = "Workorder For \"" . $workorder->customer->name . "\" Updated Successfully.";

        return Redirect::route('workorders.workorders')->with('message', $message);
    }

    public function delete(Workorder $workorder)
    {
        // Detemine return message
        $workorder_type = 'Workorder' . (request('type') == 'location' ? ' Location' : '');
        $customer_name = $workorder->customer->name;
        $message = "$workorder_type \"$customer_name\" Deleted Successfully.";

        $workorder->delete();

        return Redirect::route('workorders.workorders')->with('message', $message);
    }

    public function get($workorder)
    {
        return Workorder::where('id', $workorder)
                        ->first();
    }

    public function get_all()
    {
        return Workorder::when(request('customer_id') !== null, function ($query) {
                            $query->where('customer_id', request('customer_id'));
                        })
                        ->when(request('workorder_id') !== null, function ($query) {
                            $query->where('id', request('workorder_id'));
                        })
                        ->orderBy('job_name')
                        ->get();
    }

    public function get_last_time_data (Workorder $workorder)
    {
        $timesheet_entry_date = request('timesheet_entry_date', date('Y-m-d'));

        $work_from_last_time = null;
        $items_from_last_time = [];
        $prev_timesheet_entry = TimesheetEntry::where('workorder_id', $workorder->id)->where('date', '<', $timesheet_entry_date)->orderByDesc('date')->first();
        if ($prev_timesheet_entry != null)
        {
            $prev_timesheet_entries = TimesheetEntry::where('workorder_id', $workorder->id)->where('date', $prev_timesheet_entry->date)->with('next_time_items')->with('next_time_items.item')->get();

            foreach ($prev_timesheet_entries as $prev_entry)
            {

                if ($prev_entry->work_for_next_time != null) $work_from_last_time .= ($work_from_last_time != null ? '<br/><br/>' : '') . $prev_entry->work_for_next_time;
                foreach ($prev_entry->next_time_items as $next_time_item)
                {
                    $items_from_last_time[] = $next_time_item;
                }
            }
        }

        $data = [
            'work_from_last_time' => $work_from_last_time,
            'items_from_last_time' => $items_from_last_time,
        ];

        return $data;
    }

    ////////////////////
    // Helper Methods //
    ////////////////////

    private function store_workorder_documents(Workorder $workorder) {
        // Make an array of ids
        $document_id_array = [];
        foreach (request('documents') as $request_document)
        {
            if ($request_document['title'] != null && $request_document['filename'] != null) $document_id_array[] = $request_document['id'];
        }

        // Delete documents
        foreach ($workorder->documents as $document)
        {
            if (!in_array($document->id, $document_id_array))
            {
                $document->delete();

                // Remove permanent file
                $part_file_path = 'workorder_documents/' . $document->filename;
                if (file_exists(storage_path('app/' . $part_file_path))) Storage::delete($part_file_path);
            }
        }

        // Add / Update documents
        foreach (request('documents') as $request_document) {
            if ($request_document['title'] != null && $request_document['filename'] != null)
            {
                if ($request_document['id'] != null) $document = WorkorderDocument::findOrFail($request_document['id']);
                else $document = new WorkorderDocument();
                $document->workorder_id = $workorder->id;
                $document->title = $request_document['title'];
                $document->description = $request_document['description'];
                $document->filename = $request_document['filename'];
                $document->save();

                // Move temp file to permanent files
                $part_temp_file_path = 'temp_workorder_documents/' . $document->filename;
                $part_file_path = 'workorder_documents/' . $document->filename;
                if (file_exists(storage_path('app/' . $part_temp_file_path))) Storage::move($part_temp_file_path, $part_file_path);
            }
        }
    }

    private function store_workorder_items(Workorder $workorder) {
        // Make an array of ids
        $item_id_array = [];
        foreach (request('items') as $request_item)
        {
            if ($request_item['item_id'] != null) $item_id_array[] = $request_item['id'];
        }

        // Delete items
        foreach ($workorder->items as $item)
        {
            if (!in_array($item->id, $item_id_array)) $item->delete();
        }

        // Add / Update items
        foreach (request('items') as $request_item) {
            if ($request_item['item_id'] != null)
            {
                if ($request_item['id'] != null) $item = WorkorderItem::findOrFail($request_item['id']);
                else $item = new WorkorderItem();
                $item->workorder_id = $workorder->id;
                $item->item_id = $request_item['item_id'];
                $item->quantity = $request_item['quantity'];
                $item->save();
            }
        }
    }

    private function store_workorder_images(Workorder $workorder) {
        // Make an array of ids
        $image_id_array = [];
        foreach (request('images') as $request_image)
        {
            if ($request_image['filename'] != null) $image_id_array[] = $request_image['id'];
        }

        // Delete images
        foreach ($workorder->images as $image)
        {
            if (!in_array($image->id, $image_id_array))
            {
                $image->delete();

                // Remove permanent file
                $part_file_path = 'workorder_images/' . $image->filename;
                if (file_exists(storage_path('app/' . $part_file_path))) Storage::delete($part_file_path);
            }
        }

        // Add / Update images
        foreach (request('images') as $request_image) {
            if ($request_image['filename'] != null)
            {
                if ($request_image['id'] != null) $image = WorkorderImage::findOrFail($request_image['id']);
                else $image = new WorkorderImage();
                $image->workorder_id = $workorder->id;
                $image->description = $request_image['description'];
                $image->type = $request_image['type'];
                $image->filename = $request_image['filename'];
                $image->save();

                // Move temp file to permanent files
                $part_temp_file_path = 'temp_workorder_images/' . $image->filename;
                $part_file_path = 'workorder_images/' . $image->filename;
                if (file_exists(storage_path('app/' . $part_temp_file_path))) Storage::move($part_temp_file_path, $part_file_path);
            }
        }
    }
}
