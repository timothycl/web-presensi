<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $leaves = $request->user()->leaves()->orderBy('created_at', 'desc')->get();
        return response()->json([
            'success' => true,
            'message' => 'Leaves retrieved successfully',
            'data' => $leaves
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:cuti,izin,sakit',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $path = null;
        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('leaves', 'public');
        }

        $leave = $request->user()->leaves()->create([
            'type' => $request->type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'document' => $path,
            'status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Leave requested successfully',
            'data' => $leave
        ], 201);
    }
}
