<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckUserApproval
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Log thông tin để debug
            Log::info('CheckUserApproval Middleware', [
                'user_id' => $user->id,
                'email' => $user->email,
                'is_approved' => $user->is_approved,
                'approved_at' => $user->approved_at,
                'is_approved_method' => $user->isApproved(),
                'is_pending_method' => $user->isPendingApproval(),
                'has_role_student' => $user->hasRole('student'),
                'request_path' => $request->path()
            ]);

            // Nếu user có role student và chưa được phê duyệt
            if ($user->hasRole('student') && !$user->isApproved()) {
                // Cho phép truy cập các route liên quan đến đăng xuất và đăng nhập
                if ($request->is('logout', 'login')) {
                    return $next($request);
                }

                // Nếu user không online, chuyển về login
                if (!$user->is_online) {
                    Auth::logout();
                    return redirect()->route('login');
                }

                // Nếu user online, cho phép truy cập approval-pending
                if ($request->is('approval-pending', 'approval-check', 'admin/*')) {
                    return $next($request);
                }

                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Tài khoản của bạn đang chờ được phê duyệt.',
                        'status' => 'pending_approval'
                    ], 403);
                }

                // Chuyển về approval-pending nếu online
                return redirect()->route('approval.pending');
            }
        }

        return $next($request);
    }
}
