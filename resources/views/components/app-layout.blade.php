<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta description="Luyện thi IELTS chuyên nghiệp, đảm bảo đầu ra, đạt aim điểm theo mong muốn">
        <title>{{ config('app.name', 'Laravel') }}</title>
        {{-- Favicon --}}
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased min-h-screen bg-white flex flex-col">
        <nav class="bg-white border-b border-gray-100">
            <!-- Primary Navigation Menu -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center gap-2">
                            <img src="{{ asset('favicon-32x32.png') }}" alt="Logo" class="rounded-sm">
                            <a href="{{ route('home') }}" class="text-3xl font-bold">
                                {{ config('app.name', 'Laravel') }}
                            </a>
                        </div>

                        @include('layouts.navigation')
                    </div>

                    <!-- Settings Dropdown -->
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <div class="relative">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-md leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    Đăng xuất
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        @if (isset($header))
            {{ $header }}
        @endif

        <!-- Page Content -->
        <main class="flex-grow">
            {{ $slot }}
        </main>

        {{-- Web Footer --}}
        <footer class="bg-white text-black shadow-inner bottom-0 left-0 w-full border-t border-gray-300/20">
            <div class="container mx-auto px-4 py-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-3">
                        <h3 class="text-xl font-bold">{{ env('APP_NAME') }}</h3>
                        <p class="text-sm">Luyện thi IELTS chuyên nghiệp</p>
                        <p class="text-sm">Đảm bảo đầu ra, đạt aim điểm theo mong muốn</p>
                    </div>
                    <div class="text-left md:text-center">
                        <h3 class="text-xl font-bold mb-3">{{ env('APP_NAME') }}</h3>
                        <ul class="space-y-2 text-sm">
                            <li><a href="/home" class="hover:text-secondary-light transition duration-300">Trang chủ</a></li>
                            <li><a href="{{ env('FACEBOOK_URL') }}" target="_blank" class="hover:text-secondary-light transition duration-300">Facebook</a></li>
                            <li><a href="{{ env('ZALO_URL') }}" target="_blank" class="hover:text-secondary-light transition duration-300">Zalo</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html> 