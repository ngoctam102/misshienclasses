<?php

return [
    'auth' => [
        'guard' => env('FILAMENT_AUTH_GUARD', 'web'),
        'pages' => [
            'login' => \Filament\Pages\Auth\Login::class,
        ],
    ],

    'middleware' => [
        'base' => [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
        'auth' => [
            \Filament\Http\Middleware\Authenticate::class,
        ],
    ],

    'pages' => [
        'namespace' => 'App\\Filament\\Pages',
        'path' => app_path('Filament/Pages'),
        'register' => [
            \Filament\Pages\Dashboard::class,
        ],
    ],

    'resources' => [
        'namespace' => 'App\\Filament\\Resources',
        'path' => app_path('Filament/Resources'),
        'register' => [
            \App\Filament\Resources\UserResource::class,
            \App\Filament\Resources\TestResource::class,
            \App\Filament\Resources\TestAttemptResource::class,
            \App\Filament\Resources\QuestionResource::class,
            \App\Filament\Resources\PassageResource::class,
            \App\Filament\Resources\AudioFileResource::class,
            \App\Filament\Resources\HighlightResource::class,
        ],
    ],

    'widgets' => [
        'namespace' => 'App\\Filament\\Widgets',
        'path' => app_path('Filament/Widgets'),
        'register' => [],
    ],

    'livewire' => [
        'namespace' => 'App\\Filament',
        'path' => app_path('Filament'),
    ],

    'vite' => [
        'config' => base_path('vite.config.js'),
        'directory' => 'resources',
    ],

    'layout' => [
        'actions' => [
            'modal' => [
                'actions' => [
                    'alignment' => 'left',
                    'are_sticky' => false,
                ],
            ],
        ],
        'forms' => [
            'actions' => [
                'alignment' => 'left',
                'are_sticky' => false,
            ],
            'have_inline_labels' => false,
        ],
        'footer' => [
            'should_show_logo' => true,
        ],
        'max_content_width' => null,
        'notifications' => [
            'vertical_alignment' => 'top',
            'alignment' => 'right',
        ],
        'sidebar' => [
            'is_collapsible_on_desktop' => true,
            'groups' => [
                'are_collapsible' => true,
            ],
            'width' => null,
        ],
    ],

    'default_filesystem_disk' => env('FILAMENT_FILESYSTEM_DISK', 'public'),

    'default_theme_mode' => 'light',

    'global_search' => [
        'case_insensitive' => true,
        'is_enabled' => true,
    ],

    'navigation' => [
        'is_collapsible_on_desktop' => true,
        'groups' => [
            'are_collapsible' => true,
        ],
    ],

    'render_hooks' => [
        'panels::body.end' => [],
        'panels::content.end' => [],
        'panels::content.start' => [],
        'panels::footer.before' => [],
        'panels::global-search.after' => [],
        'panels::global-search.before' => [],
        'panels::scripts.end' => [],
        'panels::sidebar.collapsible.end' => [],
        'panels::sidebar.collapsible.start' => [],
        'panels::sidebar.end' => [],
        'panels::sidebar.start' => [],
        'panels::styles.end' => [],
        'panels::topbar.end' => [],
        'panels::topbar.start' => [],
    ],

    'routes' => [
        'domain' => null,
        'home_url' => '/',
        'login' => 'login',
        'login_redirect' => null,
        'logout' => 'logout',
        'middleware' => [
            'base' => [
                \Illuminate\Cookie\Middleware\EncryptCookies::class,
                \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
                \Illuminate\Session\Middleware\StartSession::class,
                \Illuminate\View\Middleware\ShareErrorsFromSession::class,
                \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
                \Illuminate\Routing\Middleware\SubstituteBindings::class,
            ],
            'auth' => [
                \Filament\Http\Middleware\Authenticate::class,
            ],
        ],
        'prefix' => 'admin',
        'slug' => 'admin',
    ],
];
