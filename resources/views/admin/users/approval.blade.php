<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Đang chờ phê duyệt
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-bold">
                            Quản lý phê duyệt người dùng
                            <span class="ml-2 text-sm font-normal text-gray-600">
                                ({{ $users->count() }} học viên đang chờ)
                            </span>
                        </h2>
                        <div class="flex space-x-2">
                            <form action="{{ route('admin.users.bulk-approve') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                    Phê duyệt tất cả
                                </button>
                            </form>
                            <form action="{{ route('admin.users.bulk-reject') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                                    Từ chối tất cả
                                </button>
                            </form>
                            <a href="{{ route('admin.users.logout-all') }}" 
                               class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600"
                               onclick="return confirm('Bạn có chắc chắn muốn đăng xuất tất cả học viên?')">
                                Đăng xuất tất cả học viên
                            </a>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày đăng ký</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($users as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->created_at->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex space-x-2">
                                                <form action="{{ route('admin.users.approve', $user->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-blue-600 hover:text-blue-900">
                                                        Phê duyệt
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.users.reject', $user->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                                        Từ chối
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center">Không có người dùng nào</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentCount = {{ $users->count() }};

    function checkPendingUsers() {
        const timestamp = new Date().getTime();
        fetch(`{{ route("admin.users.pending-count") }}?t=${timestamp}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Cache-Control': 'no-cache',
                'Pragma': 'no-cache'
            },
            cache: 'no-store'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Cập nhật số lượng trong menu
            const menuCount = document.querySelector('.bg-red-100');
            if (menuCount) {
                menuCount.textContent = data.count;
            }

            // Nếu số lượng học viên thay đổi, refresh trang
            if (data.count !== currentCount) {
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error checking pending users:', error);
        });
    }

    // Kiểm tra mỗi 1 giây
    setInterval(checkPendingUsers, 1000);
    // Kiểm tra ngay khi trang load
    checkPendingUsers();
});
</script>
@endpush 