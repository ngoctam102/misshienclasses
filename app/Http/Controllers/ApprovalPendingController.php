<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ApprovalPendingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        // Log thông tin để debug
        Log::info('ApprovalPendingController - User Status', [
            'user_id' => $user->id,
            'email' => $user->email,
            'is_approved' => $user->is_approved,
            'approved_at' => $user->approved_at,
            'is_approved_method' => $user->isApproved(),
            'is_pending_method' => $user->isPendingApproval(),
            'has_role_student' => $user->hasRole('student')
        ]);

        if ($user->isApproved()) {
            return redirect()->route('home');
        }

        return view('auth.approval-pending');
    }

    public function check()
    {
        if (!Auth::check()) {
            return response()->json([
                'is_approved' => false,
                'is_online' => false,
                'session_expired' => true
            ])->header('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
        }

        $user = Auth::user();

        // Log thông tin để debug
        Log::info('ApprovalPendingController - Check Status', [
            'user_id' => $user->id,
            'email' => $user->email,
            'is_approved' => $user->is_approved,
            'approved_at' => $user->approved_at,
            'is_approved_method' => $user->isApproved(),
            'is_pending_method' => $user->isPendingApproval(),
            'has_role_student' => $user->hasRole('student'),
            'is_online' => $user->is_online
        ]);

        return response()->json([
            'is_approved' => $user->isApproved(),
            'is_online' => $user->is_online,
            'session_expired' => false
        ])->header('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}
