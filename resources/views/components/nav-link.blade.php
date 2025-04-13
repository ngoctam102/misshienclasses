@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-orange-400 dark:border-orange-600 text-md font-medium leading-5 text-gray-900 dark:text-gray-100 focus:outline-none focus:border-orange-700 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-md font-medium leading-5 text-gray-500 dark:text-gray-400 hover:text-orange-500 dark:hover:text-orange-500 hover:border-orange-500 dark:hover:border-orange-500 focus:outline-none focus:text-orange-500 dark:focus:text-orange-500 focus:border-orange-500 dark:focus:border-orange-500 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a> 