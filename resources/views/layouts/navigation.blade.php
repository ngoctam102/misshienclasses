<!-- Navigation Links -->
<div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
        Home
    </x-nav-link>
    {{-- //Reading Test --}}
    <x-nav-link :href="route('reading-test')" :active="request()->routeIs('reading-test')">
        Reading Test
    </x-nav-link>

    {{-- //Listening Test --}}
    <x-nav-link :href="route('listening-test')" :active="request()->routeIs('listening-test')">
        Listening Test
    </x-nav-link>
    
    @role('super_admin')
        <x-nav-link :href="route('admin.users.approval')" :active="request()->routeIs('admin.users.approval')">
            Phê duyệt tài khoản
            @if(isset($pendingStudentsCount) && $pendingStudentsCount > 0)
                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    {{ $pendingStudentsCount }}
                </span>
            @endif
        </x-nav-link>
        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
            Dashboard
        </x-nav-link>
    @endrole
</div> 