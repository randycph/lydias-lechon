@php
    $page_routes = \App\Permission::where('module', 'pages')->pluck('name');
    $show_page = false;
    foreach($page_routes as $route) {
        if (\App\ViewPermissions::check_permission(Auth::user()->role_id,$route) == 1) {
            $show_page = true;
            break;
        }
    }
@endphp

<ul class="nav nav-aside">
    <li class="nav-label mg-t-25">Menu</li>
    <li class="nav-item">
        <a href="/" target="_blank" class="nav-link">
            <i data-feather="external-link"></i>
            <span>View Website</span>
        </a>
    </li>
    @if (auth()->user()->has_access_to_dashboard_module())
        <li class="nav-item @if (url()->current() == route('dashboard')) active @endif">
            <a href="{{ route('dashboard') }}" class="nav-link">
                <i data-feather="home"></i>
                <span>Dashboard</span>
            </a>
        </li>
    @endif
{{--    request()->is('admin/pages/*')--}}
    <li class="nav-item with-sub @if (request()->routeIs('pages*') || request()->routeIs('albums*')) active show @endif">
        <a href="" class="nav-link"><i data-feather="layers"></i> <span>Pages</span></a>
        <ul>
            <li @if (\Route::current()->getName() == 'pages.edit' || \Route::current()->getName() == 'pages.index' || \Route::current()->getName() == 'pages.create') class="active" @endif><a href="{{route('pages.index')}}">Manage Pages</a></li>
            <li @if (\Route::current()->getName() == 'albums.index' || \Route::current()->getName() == 'albums.edit' || \Route::current()->getName() == route('albums.create')) class="active" @endif><a href="{{ route('albums.index') }}">Manage Album</a></li>
        </ul>
    </li>
    @if (auth()->user()->has_access_to_file_manager_module())
        <li class="nav-item @if (\Route::current()->getName() == 'file-manager.index') active @endif">
            <a href="{{ route('file-manager.index') }}" class="nav-link"><i data-feather="folder"></i> <span>Files</span></a>
        </li>
    @endif
    <li class="nav-item with-sub">
        <a href="#" class="nav-link"><i data-feather="edit"></i> <span>News</span></a>
        <ul>
            <li><a href="{{ route('news.index') }}"><span>Manage News</span></a></li>
            <li><a href="{{ route('news-categories.index') }}"><span>News Category</span></a></li>
        </ul>
    </li>
    <li class="nav-item with-sub">
        <a href="#" class="nav-link"><i data-feather="edit"></i> <span>Products</span></a>
        <ul>
            <li><a href="{{ route('products.index') }}"><span>Manage Products</span></a></li>
            <li><a href="{{ route('products.category.index') }}"><span>Product Category</span></a></li>
        </ul>
    </li>

    <li class="nav-item with-sub">
        <a href="#" class="nav-link"><i data-feather="video"></i> <span>Videos</span></a>
        <ul>
            <li><a href="{{ route('videos.index') }}"><span>Manage Videos</span></a></li>
            <li><a href="{{ route('video-categories.index') }}"><span>Video Category</span></a></li>
        </ul>
    </li>

    <li class="nav-item with-sub @if (request()->routeIs('account*') || request()->routeIs('settings*') || request()->routeIs('audit*') || request()->routeIs('menus*')) active show @endif">
        <a href="" class="nav-link"><i data-feather="settings"></i> <span>Settings</span></a>
        <ul>
            <li @if (\Route::current()->getName() == 'account.edit') class="active" @endif><a href="{{ route('account.edit', Auth::user()->id ) }}">Account Settings</a></li>
            @if (auth()->user()->has_access_to_cms_settings_module())
                <li @if (\Route::current()->getName() == 'settings.cms') class="active" @endif><a href="{{ route('settings.cms') }}">CMS Settings</a></li>
            @endif
            @if (auth()->user()->has_access_to_website_settings_module())
                <li @if (\Route::current()->getName() == 'settings.edit') class="active" @endif><a href="{{ route('settings.edit', 1 ) }}">Website Settings</a></li>
            @endif
            @if (auth()->user()->has_access_to_menu_module())
                <li @if (\Route::current()->getName() == 'menus.edit' || \Route::current()->getName() == 'menus.index'|| \Route::current()->getName() == 'menus.create') class="active" @endif><a href="{{ route('menus.index') }}">Website Menu</a></li>
            @endif
            @if (auth()->user()->has_access_to_audit_logs_module())
                <li @if (\Route::current()->getName() == 'settings.audit') class="active" @endif><a href="{{ route('settings.audit') }}">Audit Trail</a></li>
            @endif
        </ul>
    </li>

    @if (auth()->user()->has_access_to_user_module())
        <li class="nav-item @if (request()->routeIs('users*')) active show @endif">
            <a href="{{ route('users.index') }}" class="nav-link"><i data-feather="users"></i> <span>Users</span></a>
        </li>
    @endif

    @if (Auth::user()->role_id == 1 || auth()->user()->has_access_to_roles_module() || auth()->user()->has_access_to_permissions_module())
        <li class="nav-item with-sub @if (request()->routeIs('role*') || request()->routeIs('access*') || request()->routeIs('permission*')) active show @endif">
            <a href="#" class="nav-link"><i data-feather="user"></i> <span>Account Management</span></a>
            <ul>
                @if(Auth::user()->role_id == 1)
                    <li @if (request()->routeIs('access*')) class="active" @endif><a href="{{ route('access.index') }}"><span>Access Rights</span></a></li>
                @endif
                @if (auth()->user()->has_access_to_roles_module())
                    <li @if (request()->routeIs('role*')) class="active" @endif><a href="{{ route('role.index') }}">Roles</a></li>
                @endif
                @if (auth()->user()->has_access_to_permissions_module())
                    <li @if (request()->routeIs('permission*')) class="active" @endif><a href="{{ route('permission.index') }}">Permissions</a></li>
                @endif
            </ul>
        </li>
    @endif
</ul>
