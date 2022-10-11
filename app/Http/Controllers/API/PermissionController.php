<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Validator;

class PermissionController extends Controller
{
   
    public function __construct(Permission $permission)
    {
        $this->permission = $permission;
        // $this->middleware('auth:admin-api');
    }

    public function index()
    {
        $permissions = $this->permission::all();

        return response()->json([
            'permissions' => $permissions
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->all()], 401);
        }

        $this->permission->create([
            'guard_name' => 'doctor',
            'name' => $request->name
        ]);

        return response()->json('permission created', 201);

    }

    public function show($id)
    {
        
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
