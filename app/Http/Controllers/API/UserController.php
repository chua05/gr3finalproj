<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;    
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::get();
        if($users->count() > 0) {
            return UserResource::collection($users);
        } else {
            return response()->json(['message' => 'No user available'], 200);
        }
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
                    'name' => 'required|string|max:255',
                    'email' => 'required|string|max:255',
                    'password' => 'required|string|max:255',
        
                ]);
        if($validator->fails())
        {
            return response()->json([
                'message' => 'All fields are mandatory',
                'error' => $validator->messages(),
            ], 422);
        }

        //create a new user record
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        // Return a success response with the created user data
        return response()->json([
            'message' => 'Created Successfully',
            'data' => new UserResource ($user)
        ],201);
    }
    public function show(User $user)
    {
     return new UserResource($user);
    }

    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'password' => 'required|string|max:255',
        ]);
        if($validator->fails())
        {
            return response()->json([
                'message' => 'All fields are mandatory',
                'error' => $validator->messages(),
            ], 422);
        }

        //update user record
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        // Return a success response with the updated user data
        return response()->json([
            'message' => 'Updated Successfully',
            'data' => new UserResource ($user)
        ],200);

    }
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([
            'message' => 'User Deleted Successfully',
        ],200);
    }

}

