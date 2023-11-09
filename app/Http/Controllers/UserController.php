<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;
use Inertia\Inertia;
use App\Models\User;
use App\Models\UserPermission;
use App\Models\Permission;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Auth;

class UserController extends Controller
{
    protected $pages = [];
    protected $accesses = [];

    public function __construct()
    {
        $this->pages = [
            ['value' => '/users', 'label' => 'Users'],
            ['value' => '/customers', 'label' => 'Customers'],
            ['value' => '/readings', 'label' => 'Readings'],
            ['value' => '/workorders', 'label' => 'Workorders'],
            ['value' => '/reported_issues', 'label' => 'Reported Issues'],
            ['value' => '/notifications', 'label' => 'Notifications'],
        ];

        $this->accesses = [
            ['value' => 'view', 'label' => 'View'],
            ['value' => 'edit', 'label' => 'Edit'],
            ['value' => 'admin', 'label' => 'Admin'],
        ];
    }

    public function initial_page()
    {
        $user_role = Auth::user()->role;
        if ($user_role == 'installer' && Auth::user()->service_calls) $user_role = 'service_call_installer';

        if ($user_role == 'service_call_installer' || $user_role == 'installer') return Redirect::route('customers.customers');
        return Redirect::route('customers.customers');
    }

    public function index()
    {
        $users = User::all();
        return Inertia::render('Users', ['users' => $users]);
    }

    public function edit(User $user)
    {
        $user->permissions;
        $user_permissions = [];
        foreach ($user->permissions as $user_permission)
        {
            array_push($user_permissions, [
                'user_permission_id' => $user_permission->id,
                'page' => $user_permission->permission->page,
                'access' => $user_permission->permission->access,
            ]);
        }
        $user->permissions_ = $user_permissions;
        // dd($user->permissions_);

        $customers = Customer::all();
        return Inertia::render('UserEdit', ['user' => $user, 'customers' => $customers, 'pages' => $this->pages, 'accesses' => $this->accesses]);
    }

    public function store(User $user)
    {
        $mode = $user->id === null ? 'add' : 'edit';

        if (request('profile_update'))
        {
            $user = auth()->user();
        }

        // Validate the request
        $validate_rules = [
            'name' => ['required'],
            'username' => [
                'required',
                Rule::unique('users')->ignore($user->id),
            ],
            'role' => ['required'],
        ];
        if ($mode === 'add') $validate_rules['password'] = ['required', 'confirmed'];
        if ($mode === 'edit' && request('password') !== null) $validate_rules['password'] = ['confirmed'];

        $messages = [
            'required' => 'This field is required.',
            'email' => 'This field must be a valid email.'
        ];

        Validator::make(request()->all(), $validate_rules, $messages)->validate();

        // Persist the user information
        $user->customer_id = request('customer_id');
        $user->name = request('name');
        $user->email = request('email');
        $user->phone = request('phone');
        $user->username = request('username');
        if (request('password') != null)
        {
            $user->password = bcrypt(request('password'));
        }
        $user->role = request('role');
        $user->service_calls = $user->role == 'installer' ? request('service_calls') : 0;
        $user->save();

        // Persist the user permissions
        $message = $this->store_permissions($user);

        // Detemine return message
        if (request('profile_update'))
        {
            if ($message == '')
                $message = "Profile Updated Successfully.";

            return Redirect::route('profile.show')->with('message', $message);
        }
        else
        {
            if ($message == '')
            {
                if ($mode === 'add') $message = "User \"$user->name\" Added Successfully.";
                else $message = "User \"$user->name\" Updated Successfully.";
            }

            return Redirect::route('users')->with('message', $message);
        }
    }

    private function store_permissions(User $user)
    {
        // Make an array of ids
        $user_permission_id_array = [];
        foreach (request('permissions') as $request_permission)
        {
            if ($request_permission['user_permission_id'] != null) $user_permission_id_array[] = $request_permission['user_permission_id'];
        }

        // Delete items
        foreach ($user->permissions as $user_permission)
        {
            if (!in_array($user_permission->id, $user_permission_id_array)) $user_permission->delete();
        }

        // Add / Update items
        foreach (request('permissions') as $request_permission)
        {
            $permission = Permission::where([
                'page' => $request_permission['page'],
                'access' => $request_permission['access'],
            ])->first();
            if (is_null($permission))
                return sprintf("Cannot find permission with Page:%s and Access:%s", $request_permission['page'], $request_permission['access']);

            if ($request_permission['user_permission_id'] != null) $user_permission = UserPermission::findOrFail($request_permission['user_permission_id']);
            else $user_permission = new UserPermission();
            $user_permission->user_id = $user->id;
            $user_permission->permission_id = $permission->id;

            $user_permission->save();
        }

        return '';
    }

    public function delete(User $user)
    {
        // Detemine return message
        $message = "User \"$user->name\" Deleted Successfully.";

        $user->delete();

        return Redirect::route('settings.users')->with('message', $message);
    }

    public function get_all()
    {
        return User::orderBy('name')->get();
    }
}
