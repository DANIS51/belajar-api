<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $users = User::all();
         return response()->json([
            'number_of_users' => $users->count(),
            'data' => $users
         ], 200);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|max:50|unique:users',
            'password' => 'required|string|min:6'
        ]);
        if ($validator->fails()){
            return response()->json($validator->errors(),422);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password) ,
        ]);
        return response()->json([
            'message' => 'User berhasil di buat',
            'data' => $user
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $users = User::find($id);
        if ($users) {
            return response()->json($users,200);
        } else {
            return response()->json(['message' => 'User not founde'],404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:50',
            'email' => 'sometimes|required|string|email|max:50|unique:users,email,' . $id,
            'password' => 'sometimes|required|string|min:6'
        ]);
        if ($validator->fails()){
            return response()->json($validator->errors(),422);
        }

        $user = User::find($id);
        if ($user) {
            $user->update([
                'name' => $request->name ?? $user->name,
                'email' => $request->email ?? $user->email,
                'password' => $request->password ? bcrypt($request->password) : $user->password,
            ]);
            return response()->json([
            'message' => 'User berhasil di buat',
            'data' => $user
        ], 200);
        } else {
            return response()->json(['message' => 'user not found'], 400);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return response()->json(['message' => 'User berhasil di Hapus',], 200);
        }  else {
            return response()->json(['message' => 'user not found'], 400);

        }
    }
}
