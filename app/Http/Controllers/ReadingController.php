<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;
use Inertia\Inertia;
use App\Models\Customer;
use App\Models\Reading;
use Illuminate\Support\Facades\Validator;

class ReadingController extends Controller
{
    public function index()
    {
        // dd(bin2hex(random_bytes(16)));
        $customers = Customer::all();
        $readings = Reading::all();

        return Inertia::render('Readings', ['customers' => $customers, 'readings' => $readings, 'customer_status_def' => Customer::Status_Def]);
    }

    public function edit($customer)
    {
        $customer = Customer::where('id', $customer)->with(['supervisors'])->firstOrFail();
        $date = date('Y-m-d');

        return Inertia::render('ReadingEdit', ['customer' => $customer, 'date' => $date]);
    }

    public function store(Customer $customer)
    {
        $mode = $customer->id === null ? 'add' : 'edit';

        // Validate the request
        $validate_rules = [
            'name' => ['required'],
        ];

        $messages = [
            'required' => 'This field is required.',
            'email' => 'This field must be a valid email.'
        ];

        Validator::make(request()->all(), $validate_rules, $messages)->validate();

        // Persist the customer information
        $customer->name = request('name');
        $customer->email = request('email');
        $customer->phone = request('phone');
        $customer->fax = request('fax');
        $customer->street1 = request('street1');
        $customer->street2 = request('street2');
        $customer->city = request('city');
        $customer->prov_state = request('prov_state');
        $customer->postal_zip = request('postal_zip');
        $customer->save();

        // Persist the customer supervisors
        $this->store_supervisors($user);

        // Detemine return message
        $customer_type = 'Customer' . (request('type') == 'location' ? ' Location' : '');
        if ($mode === 'add') $message = "$customer_type \"$customer->name\" Added Successfully.";
        else $message = "$customer_type \"$customer->name\" Updated Successfully.";

        return Redirect::route('customers')->with('message', $message);
    }

    private function store_supervisors(Customer $customer)
    {
        // Make an array of ids
        $supervisor_id_array = [];
        foreach (request('supervisors') as $request_supervisor)
        {
            if ($request_supervisor['id'] != null) $supervisor_id_array[] = $request_supervisor['id'];
        }

        // Delete items
        foreach ($customer->supervisors as $supervisor)
        {
            if (!in_array($supervisor->id, $supervisor_id_array)) $supervisor->delete();
        }

        // Add / Update items
        foreach (request('supervisors') as $request_supervisor)
        {
            if ($request_supervisor['id'] != null) $customer_supervisor = CustomerSupervisor::findOrFail($request_supervisor['id']);
            else $customer_supervisor = new CustomerSupervisor();
            $customer_supervisor->customer_id = $customer->id;
            $customer_supervisor->name = $request_supervisor->name;
            $customer_supervisor->method = $request_supervisor->name;
            $customer_supervisor->email = $request_supervisor->name;
            $customer_supervisor->phone = $request_supervisor->name;

            $customer_supervisor->save();
        }
    }

    public function delete(Customer $customer)
    {
        // Detemine return message
        $customer_type = 'Customer' . (request('type') == 'location' ? ' Location' : '');
        $message = "$customer_type \"$customer->name\" Deleted Successfully.";

        $customer->delete();

        return Redirect::route('customers')->with('message', $message);
    }

    public function get_all()
    {
        return Customer::orderBy('name')->get();
    }
}
