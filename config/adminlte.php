<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    */

    'title' => 'PachecoDiesel',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    */

    'use_ico_only' => false,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Google Fonts
    |--------------------------------------------------------------------------
    */

    'google_fonts' => [
        'allowed' => true,
        'fonts' => [
            'Poppins:300,400,600,700' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Logo
    |--------------------------------------------------------------------------
    */

    'logo' => '<b>Pacheco</b>Diesel',
    'logo_img' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'Admin Logo',

    /*
    |--------------------------------------------------------------------------
    | Preloader Animation
    |--------------------------------------------------------------------------
    */

    'preloader' => [
        'enabled' => true,
        'mode' => 'fullscreen',
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'AdminLTE Preloader Image',
            'effect' => 'animation__shake',
            'width' => 60,
            'height' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    */

    'usermenu_enabled' => true,
    'usermenu_header' => false,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => 'user/profile',

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    | Clases tomadas de tu segunda configuración para un mejor layout.
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => true,
    'layout_fixed_navbar' => true,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null, // Lo manejaremos con JS para guardar la preferencia.

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    */

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    | Clases tomadas de tu segunda configuración.
    */

    'classes_body' => 'sidebar-mini layout-fixed',
    'classes_brand' => 'navbar-primary navbar-dark',
    'classes_brand_text' => 'font-weight-bold',
    'classes_content_wrapper' => 'content-wrapper',
    'classes_content_header' => 'content-header',
    'classes_content' => 'content',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => 'nav-pills nav-sidebar flex-column',
    'classes_topnav' => 'navbar-white navbar-light border-bottom-0 text-sm',
    'classes_topnav_nav' => 'navbar-expand-lg',
    'classes_topnav_container' => 'container-fluid',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    */

    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => 768,
    'sidebar_collapse_remember' => true,
    'sidebar_collapse_remember_no_transition' => false,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    */

    'use_route_url' => false,
    'dashboard_url' => 'home',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => false,
    'disable_darkmode_routes' => false,

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    */

    'menu' => [
        // Navbar items...
        [
            'type'         => 'navbar-search',
            'text'         => 'search',
            'topnav_right' => true,
        ],
        [
            'type'         => 'fullscreen-widget',
            'topnav_right' => true,
        ],

        // Sidebar items...
        ['header' => 'Principal'],
        [
            'text' => 'Dashboard',
            'url' => 'dashboard',
            'icon' => 'dashboard',
            'icon_class' => 'material-icons-round',
            'active' => ['dashboard'],
            'can' => 'ver dashboard',
        ],

        ['header' => 'Inventario'],
        [
            'text' => 'Productos',
            'url' => 'inventario',
            'icon' => 'inventory_2',
            'icon_class' => 'material-icons-round',
            'can' => 'ver productos',
        ],
        [
            'text' => 'Reponer Stock',
            'url' => 'inventario/gestion-stock',
            'icon' => 'add_shopping_cart',
            'icon_class' => 'material-icons-round',
            'can' => 'reponer stock', // Asumiendo que necesitas un permiso para esto
        ],
        [
            'text' => 'Categorías',
            'url' => 'categorias',
            'icon' => 'category',
            'icon_class' => 'material-icons-round',
            'can' => 'ver categorias',
        ],
        [
            'text' => 'Proveedores',
            'url' => 'proveedores',
            'icon' => 'local_shipping',
            'icon_class' => 'material-icons-round',
            'can' => 'ver proveedores',
        ],

        ['header' => 'Historial'],
        [
            'text' => 'Ventas',
            'url' => 'ventas',
            'icon' => 'receipt_long',
            'icon_class' => 'material-icons-round',
            'can' => 'ver historial ventas',
        ],
        [
            'text' => 'Compras',
            'url' => 'compras',
            'icon' => 'shopping_cart',
            'icon_class' => 'material-icons-round',
            'can' => 'ver historial compras',
        ],

        ['header' => 'Administración'],
        [
            'text' => 'Clientes',
            'url' => 'clientes',
            'icon' => 'group',
            'icon_class' => 'material-icons-round',
            'can' => 'ver clientes',
        ],
        [
            'text' => 'Empleados',
            'url' => 'empleados',
            'icon' => 'people',
            'icon_class' => 'material-icons-round',
            'can' => 'ver empleados',
        ],
        [
            'text' => 'Usuarios',
            'url' => 'admin/users',
            'icon' => 'people',
            'icon_class' => 'material-icons-round',
            'can' => 'ver usuarios',
        ],
        [
            'text' => 'Roles',
            'url' => 'admin/roles',
            'icon' => 'vpn_key',
            'icon_class' => 'material-icons-round',
            'can' => 'ver roles',
        ],
        [
            'text' => 'Permisos',
            'url' => 'admin/permissions',
            'icon' => 'security',
            'icon_class' => 'material-icons-round',
            'can' => 'ver permisos',
        ],

        ['header' => 'Cuenta'],
        [
            'text' => 'Mi Perfil',
            'url' => 'user/profile',
            'icon' => 'account_circle',
            'icon_class' => 'material-icons-round',
        ],

        // NUEVO: Interruptor de modo oscuro en el pie del menú.
        [
            'text'  => 'Alternar Tema',
            'icon'  => 'contrast',
            'icon_class' => 'material-icons-round',
            'url'   => '#',
            'id'    => 'dark-mode-toggler',      // ID para el JavaScript
            'class' => 'dark-mode-footer-item',  // Clase para el CSS
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    */

    'plugins' => [
        // NUEVO: Plugin para cargar nuestros archivos personalizados.
        'CustomLayout' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'css/custom-layout.css',
                ],
            ],
        ],
        'CustomSidebar' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'css/custom-sidebar.css',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'js/custom-dark-mode.js',
                ],
            ],
        ],
        'Datatables' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => false,
            // ...
        ],
        'Chartjs' => [
            'active' => false,
            // ...
        ],
        'Pace' => [
            'active' => false,
            // ...
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    */

    'livewire' => true,
];
