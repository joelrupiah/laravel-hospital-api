<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Validator;

class RoleController extends Controller
{

    public function __contruct(Role $role)
    {
        $this->role = $role;
        // $this->middleware('auth:admin-api');
    }
   
    public function index()
    {
        $roles = Role::with('permissions')->get();

        return response()->json([
            'roles' => $roles
        ]);
    }

    public function store(Request $request)
    {
        // return $request;

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles',
            'permissions' => 'nullable'
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->all()], 401);
        }

        $role = Role::create([
            'name' => $request->name
        ]);

        if ($request->has('permissions')) {
            $role->givePermissionTo($request->permissions);
        }

        return response()->json('Role created', 200);

    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
