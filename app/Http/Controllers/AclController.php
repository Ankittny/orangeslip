<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Silber\Bouncer\Database\Role;
use Silber\Bouncer\Database\Ability;
use Illuminate\Support\Facades\Log;
use Bouncer;
use DB;

class AclController extends Controller
{
    public function indexRole()
    {
        /**
         * Role List
         * output:roles
         */
        $this->authorize("access-manage-role");
        $roles = Role::all();
        return view('admin.acl.role.index', compact('roles'));
    }

    public function createRole()
    {
        /**
         * Create Role Page View
         */
        $this->authorize("access-create-role");
        return view('admin.acl.role.create');
    }

    public function storeRole(Request $request)
    {
        /**
         * Store Role
         * input:role_name,role_title
         */
        $this->authorize("access-create-role");
        $this->validate($request, [
            'role_name' => 'required',
            'role_title' => 'required',
        ]);
        try {

            $title = $request->input('role_title');
            $name = $request->input('role_name');

            Role::create([
                'name' => $name,
                'title' => $title,
            ]);

            //flash()->success('Success! Role created.');

        } catch (\Exception $exception) {
            //flash()->error('Error! Role creation failed. '.$exception->getMessage());
        }

        return redirect()->back();
    }

    public function managePermission($role)
    {
        /**
         * Manage Permission
         * input:role
         * output:permissions,selected,role
         */
        
        if(!request()->ajax()) {
            throw new \Exception('Error! You are not allowed to access this page.');
        }
        $role = Role::find($role);
        $permissions = Ability::all();
        $selected = $role->abilities()->pluck('name')->toArray();

        return view('admin.acl.role.manage-permission', compact('permissions','selected', 'role'));
    }

    public function managePermissionSet(Request $request, $role)
    {
        /**
         * Set Permission
         * input:abilities.
         * output:status.
         */
        $this->authorize("access-manage-permission-set");
        $this->validate($request, [
            'abilities.*' => 'required|exists:abilities,name',
        ]);

        try {

            if(!request()->ajax()) {
                throw new \Exception('Error! You are not allowed to access this page.');
            }

            $role = Role::find($role);

            $role->abilities()->detach();

            $abilities = $request->input('abilities');

            $role->allow($abilities);

            return json_encode(['status' => 'true', 'message' => 'Success! Permission has been set.']);
        } catch (\Exception $exception) {
            Log::error($exception);
            return json_encode(['status' => 'false', 'message' => $exception->getMessage()]);
        }
    }

    public function indexPermission()
    {
        
        $abilities = Ability::paginate(10);
        return view('admin.acl.permission.index', compact('abilities'));
    }

    public function assignRole()
    {
        /**
         * Assign Role Page View
         * output:users,roles
         */
        $users = User::where('status',1)->get();
        $roles = DB::table('roles')->get();
        return view('admin.acl.role.assign_role', compact('users','roles'));
    }

    public function saveAssignedRole(Request $request)
    {
        /**
         * Assign Role Store
         * input:user_id,type
         *  
         */
        $this->authorize("access-manage-member-role");
		
		$this->validate($request, [
            'user_id' => 'required|exists:users,id',
            'type' => 'required'
        ]);
		
		$id = $request->input('user_id');
		
		$user = User::where('id', $id)->firstOrFail();
		
		Bouncer::retract($user->account_type)->from($user);
		
		$user->account_type = $request->input('type');
		$user->save();		

		if($request->input('type') == 'superadmin'){
            //Bouncer::assign($request->input('type'))->to($user);			
			$user->administrator = 1;			
		}else{
            Bouncer::assign($request->input('type'))->to($user);
			$user->administrator = 0;
		}
		
		$user->save();
		
		//flash()->success("Member Role has beeen successfully changed");	
				
		//return redirect(route('profile.display',$user->username));
       //return redirect()->back();
    }
}
