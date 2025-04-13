<x-app-layout>
<div class="relative w-full h-[70vh]">
    {{-- Preload hero image --}}
    <link rel="preload" as="image" href="{{ asset('images/bg_hero.jpg') }}">
    <img 
        loading="eager" 
        class="w-full h-full object-cover" 
        src="{{ asset('images/bg_hero.jpg') }}" 
        alt="hero-background-image"
        decoding="async"
        fetchpriority="high"
    >
    <div class="absolute inset-0 w-full h-full bg-black/70"></div>
    <div class="absolute inset-0 flex flex-col justify-center text-center">
        <div class="">
            <h1 class="text-orange-500 mb-4 text-4xl font-extrabold tracking-tight md:text-5xl lg:text-6xl">Luyện thi IELTS</h1>
            <h2 class="mb-6 text-lg font-normal lg:text-xl sm:px-16 xl:px-48 text-white">Nơi giúp bạn đạt được điểm số mong muốn</h2>
        </div>
    </div>
</div>
<div class="p-10">
    <div class="text-gray-900 font-bold text-xl mb-6">Xin chào, {{ Auth::user()->name }}!</div>
    <div>
        <div class="text-gray-900 text-lg mb-4">Kết quả luyện thi mới nhất của bạn</div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
        </div>
        
        <div class="mt-6 text-center">
            <a href="" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Xem tất cả bài thi
            </a>
        </div>
    </div>
</div>

</x-app-layout> 