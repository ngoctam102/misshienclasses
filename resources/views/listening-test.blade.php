<x-app-layout>
    <div class="container mx-auto px-6 py-10">
        <h1 class="text-4xl font-bold mb-4 text-gray-900 text-center">Đề thi listening test</h1>
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
</x-app-layout>

