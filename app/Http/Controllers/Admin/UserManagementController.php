<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:super_admin']);
    }

    public function logoutAllStudents()
    {
        try {
            // Lấy số lượng học viên trước khi reset
            $studentCount = User::role('student')->count();

            // Reset trạng thái phê duyệt của tất cả học viên
            User::role('student')->update([
                'is_approved' => false,
                'approved_at' => null,
                'approved_by' => null
            ]);

            Log::info('Super admin đã đăng xuất tất cả học viên', [
                'admin_id' => auth()->id(),
                'admin_email' => auth()->user()->email,
                'student_count' => $studentCount
            ]);

            return redirect()->route('admin.users.approval')
                ->with('success', "Đã đăng xuất {$studentCount} học viên thành công.");
        } catch (\Exception $e) {
            Log::error('Lỗi khi đăng xuất tất cả học viên', [
                'error' => $e->getMessage(),
                'admin_id' => auth()->id(),
                'admin_email' => auth()->user()->email
            ]);

            return redirect()->route('admin.users.approval')
                ->with('error', 'Có lỗi xảy ra khi đăng xuất học viên.');
        }
    }
}
