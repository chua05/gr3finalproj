<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Http\Resources\AdminResource;

class AdminController extends Controller
{
    public function index()
    {
        // Fetch all admins from the database
        $admins = Admin::all();

        // Return a success response with the list of admins
        return response()->json([
            'message' => 'Admins retrieved successfully',
            'data' => AdminResource::collection($admins)
        ], 200);
    }

    public function show($id)
    {
        // Find the admin by ID
        $admin = Admin::find($id);

        if (!$admin) {
            return response()->json(['message' => 'Admin not found'], 404);
        }

        // Return a success response with the admin data
        return response()->json([
            'message' => 'Admin retrieved successfully',
            'data' => new AdminResource($admin)
        ], 200);
    }
    
}
