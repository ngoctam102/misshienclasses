<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserApprovalController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:super_admin']);
    }

    public function index()
    {
        // Lấy danh sách user đang online và chờ phê duyệt
        $users = User::role('student')
            ->where('is_approved', false)
            ->where('is_online', true)
            ->get();

        return view('admin.users.approval', compact('users'));
    }

    public function approve(User $user)
    {
        if ($user->isApproved()) {
            return back()->with('error', 'Người dùng này đã được phê duyệt trước đó.');
        }

        $user->approve(auth()->user());

        // Gửi email thông báo cho user
        // TODO: Implement email notification

        return redirect()->route('admin.users.approval')
            ->with('success', 'Đã phê duyệt người dùng thành công.');
    }

    public function reject(User $user)
    {
        // Set user offline và logout
        $user->update(['is_online' => false]);

        // Nếu user bị từ chối là user đang đăng nhập
        if ($user->id === auth()->id()) {
            Auth::guard('web')->logout();
            session()->invalidate();
            session()->regenerateToken();
            return redirect()->route('login')
                ->with('success', 'Đã từ chối người dùng thành công.');
        }

        return redirect()->route('admin.users.approval')
            ->with('success', 'Đã từ chối người dùng thành công.');
    }

    public function bulkApprove(Request $request)
    {
        // Lấy danh sách user đang online và chờ phê duyệt
        $users = User::role('student')
            ->where('is_approved', false)
            ->where('is_online', true)
            ->get();

        foreach ($users as $user) {
            $user->approve(auth()->user());
        }

        return redirect()->route('admin.users.approval')
            ->with('success', 'Đã phê duyệt tất cả học viên thành công.');
    }

    public function bulkReject(Request $request)
    {
        // Lấy danh sách user đang online và chờ phê duyệt
        $users = User::role('student')
            ->where('is_approved', false)
            ->where('is_online', true)
            ->get();

        foreach ($users as $user) {
            // Từ chối và set offline
            $user->reject();
            $user->update(['is_online' => false]);
        }

        return redirect()->route('admin.users.approval')
            ->with('success', 'Đã từ chối tất cả học viên thành công.');
    }

    public function logoutAll()
    {
        // Chỉ lấy các học viên đang online
        $students = User::role('student')
            ->where('is_online', true)
            ->get();

        $currentUserId = auth()->id();

        foreach ($students as $student) {
            // Reset trạng thái phê duyệt và set offline
            $student->update([
                'is_approved' => false,
                'approved_at' => null,
                'approved_by' => null,
                'is_online' => false
            ]);

            // Nếu student đang đăng nhập, logout họ
            if ($student->id === $currentUserId) {
                Auth::guard('web')->logout();
                session()->invalidate();
                session()->regenerateToken();
            }
        }

        // Chuyển hướng về trang login
        return redirect()->route('login')
            ->with('success', 'Đã đăng xuất và reset trạng thái phê duyệt của tất cả học viên.');
    }

    public function checkPendingCount()
    {
        $count = User::role('student')
            ->where('is_approved', false)
            ->where('is_online', true)
            ->count();

        return response()->json([
            'count' => $count
        ])->header('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}
