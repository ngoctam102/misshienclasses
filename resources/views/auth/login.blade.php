@extends('layouts.app')

@section('content')
<div class="h-screen flex flex-col">
    <!-- Background section -->
    <div class="h-[55vh] w-full relative">
        <img src="{{ asset('images/bg_hero1.png') }}" alt="Hero Background" class="w-full h-full object-fill">
        <div class="absolute inset-0 bg-black/30"></div>
    </div>

    <!-- Form section -->
    <div class="h-[45vh] flex flex-col items-center p-6">
        <div>
            <a href="/" class="text-4xl font-bold text-black">
                {{ config('app.name', 'Laravel') }}
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md sm:rounded-lg">
            <div class="mb-4 text-sm text-gray-600">
                Vui lòng đăng nhập để tiếp tục
            </div>
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" 
                        class="block mt-1 w-full px-2 py-1 bg-gray-100 text-black border-0 rounded-md focus:ring-0 focus:outline-none">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="block mt-1 w-full px-2 py-1 bg-gray-100 text-black border-0 rounded-md focus:ring-0 focus:outline-none">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                        <span class="ms-2 text-sm text-gray-600">Remember me</span>
                    </label>
                </div>
                <div class="flex items-center justify-center mt-4">
                    <x-primary-button class="w-auto">
                        Login
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 