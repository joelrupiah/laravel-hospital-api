<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Validator;
use Hash;

class UserController extends Controller
{
   
    public function index()
    {
        $user = Auth::user();

        return $user;
    }

    public function getDoctors()
    {
        // $doctors = Doctor::with('roles')->get();
        $doctors = Doctor::get();

        $doctors->transform(function($doctor){
            $doctor->role = $doctor->getRoleNames()->first();
            return $doctor;
        });

        return response()->json([
            'doctors' => $doctors
        ], 200);
    }

    public function getSpecificDoctor(Doctor $doctor, $id)
    {
        $doctor = Doctor::where('id', $id)->first();

        $doctor->role = $doctor->getRoleNames()->first();
        $doctor->permissions = $doctor->getAllPermissions()->first();
        return $doctor;
        
    }

    public function createDoctor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:doctors',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->all()], 401);
        }

        $doctor = new Doctor();

        $doctor->name = $request->name;
        $doctor->email = $request->email;
        $doctor->password = Hash::make($request->password);

        // return $doctor;
        $doctor->assignRole($request->role);
        if($request->has('permissions')){
            $doctor->givePermissionTo($request->permissions);
        }

        $doctor->save();

        return response()->json('success', 201);
    }

    public function store(Request $request)
    {
        //
    }

    public function show(User $user)
    {
        //
    }

    public function update(Request $request, User $user)
    {
        //
    }

    public function updateDoctor(Request $request, $id)
    {
        // return $request;
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:doctors,email,'.$id,
            'password' => 'min:6',
            'role' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->all()], 401);
        }

        $doctor = Doctor::findOrFail($id);

        $doctor->name = $request->name;
        $doctor->email = $request->email;

        if ($request->has('password')) {
            $doctor->password = Hash::make($request->password);
        }

        if ($request->has('role')) {
            $doctorRole = $doctor->getRoleNames();
            foreach ($doctorRole as $role) {
                $doctor->removeRole($role);
            }
            $doctor->assignRole($request->role);
        }

        if ($request->has('permissions')) {
            $doctorPermissions = $doctor->getPermissionNames();
            foreach ($doctorPermissions as $permission) {
                $doctor->revokePermissionTo($permission);
            }
            $doctor->givePermissionTo($request->permissions);
        }

        $doctor->save();

        return response()->json('success', 200);
    }

    public function destroy(User $user)
    {
        //
    }
}
