<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = User::query()
            ->where('role', 3)
            ->select(['id', 'name', 'email', 'role', 'created_at', 'updated_at'])
            ->orderBy('name')
            ->get();

        return response()->json([
            'status' => true,
            'count' => $employees->count(),
            'employees' => $employees,
        ]);
    }
}
