<x-app-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            @if (session('warning'))
                <div class="mb-4 p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 dark:bg-yellow-800 dark:border-yellow-700 dark:text-yellow-300 rounded">
                    {{ session('warning') }}
                </div>
            @endif

            <div class="mb-4 text-center">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                    Tài khoản đang chờ phê duyệt
                </h2>
                <p class="text-gray-600 dark:text-gray-400">
                    Tài khoản của bạn đang chờ được phê duyệt từ Super Admin. 
                    Vui lòng chờ trong giây lát.
                </p>
            </div>

            <div class="flex justify-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900 dark:border-gray-100"></div>
            </div>

            <div class="mt-4 text-center">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-red-600 dark:text-red-400 hover:underline">
                        Đăng xuất
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function checkApprovalStatus() {
            const timestamp = new Date().getTime();
            fetch(`{{ route("approval.check") }}?t=${timestamp}`, {
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
                // Nếu session hết hạn hoặc user không online, chuyển về trang login
                if (data.session_expired || !data.is_online) {
                    window.location.href = '{{ route("login") }}';
                    return;
                }

                if (data.is_approved) {
                    window.location.href = '{{ route("home") }}';
                }
            })
            .catch(error => {
                // Nếu có lỗi (ví dụ: session hết hạn), chuyển về trang login
                window.location.href = '{{ route("login") }}';
            });
        }

        // Kiểm tra mỗi 1 giây
        setInterval(checkApprovalStatus, 1000);
        // Kiểm tra ngay khi trang load
        checkApprovalStatus();
    </script>
</x-app-layout> 