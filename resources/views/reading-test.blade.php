<x-app-layout>
    <x-two-cols>
        <div class="container mx-auto px-6 py-10">
            <h1 class="text-4xl font-bold mb-4 text-gray-900 text-center">Đề thi reading test</h1>
        </div>
        <div class="container mx-auto px-6 py-8">
            <form action="/search" method="get">
                <div class="flex gap-4">
                    <input
                        type="text"
                        name="find"
                        placeholder="Nhập từ khoá bạn muốn tìm..."
                        class="flex-1 p-4 rounded-lg border border-gray-300
                               bg-white
                               text-gray-900
                               placeholder-gray-500
                               focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                               shadow-sm
                               transition duration-150 ease-in-out"
                    >
                    <button
                        type="submit"
                        class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-lg
                               font-medium
                               transition duration-150 ease-in-out
                               shadow-md hover:shadow-lg
                               whitespace-nowrap"
                    >
                        Tìm kiếm
                    </button>
                </div>
            </form>
        </div>
        <div class="container mx-auto px-6 py-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($tests as $test)
                    <div class="bg-white rounded-lg p-4 shadow-md flex flex-col gap-2 justify-center items-center">
                        <h2 class="text-lg font-bold mb-2 text-gray-900">{{ $test->title }}</h2>
                        <p class="text-gray-600">{{ $test->duration . ' phút' }}</p>
                        <p class="text-gray-600">{{ $test->total_questions . ' câu hỏi' }}</p>
                        <a href="{{ route('reading-test-handle', $test->slug) }}" class="mt-4 inline-block px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition duration-150 ease-in-out">
                            Làm bài
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </x-two-cols>
</x-app-layout>

