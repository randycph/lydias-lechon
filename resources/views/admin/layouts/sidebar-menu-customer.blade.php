<ul class="nav nav-aside">
    <li class="nav-item">
        <a href="{{route('home')}}" target="_blank" class="nav-link">
            <i data-feather="external-link"></i>
            <span>View Website</span>
        </a>
    </li>

    <li class="nav-label mg-t-25">CUSTOMER PORTAL</li>

    @if (auth()->user()->has_access_to_pages_module())
        <li class="nav-item with-sub @if (request()->routeIs('pages*')) active show @endif">
            <a href="" class="nav-link"><i data-feather="layers"></i> <span>Pages</span></a>
            <ul>
                <li @if (\Route::current()->getName() == 'pages.edit' || \Route::current()->getName() == 'pages.index' || \Route::current()->getName() == 'pages.index.advance-search') class="active" @endif><a href="{{ route('pages.index') }}">Manage Pages</a></li>
                <li @if (\Route::current()->getName() == 'pages.create') class="active" @endif><a href="{{ route('pages.create') }}">Create a Page</a></li>
            </ul>
        </li>
    @endif

    @if (auth()->user()->has_access_to_albums_module())
        <li class="nav-item with-sub @if (request()->routeIs('albums*')) active show @endif">
            <a href="#" class="nav-link"><i data-feather="image"></i> <span>Banners</span></a>
            <ul>
                <li @if (url()->current() == route('albums.edit', 1)) class="active" @endif><a href="{{ route('albums.edit', 1) }}">Manage Home Banner</a></li>
                <li @if (\Route::current()->getName() == 'albums.index' || (\Route::current()->getName() == 'albums.edit' && url()->current() != route('albums.edit', 1))) class="active" @endif><a href="{{ route('albums.index') }}">Manage Subpage Banners</a></li>
                <li @if (\Route::current()->getName() == 'albums.create') class="active" @endif><a href="{{ route('albums.create') }}">Create an Album</a></li>
            </ul>
        </li>
    @endif

    @if (auth()->user()->has_access_to_menu_module())
        <li class="nav-item with-sub @if (request()->routeIs('menus*')) active show @endif">
            <a href="" class="nav-link"><i data-feather="menu"></i> <span>Menu</span></a>
            <ul>
                <li @if (\Route::current()->getName() == 'menus.edit' || \Route::current()->getName() == 'menus.index') class="active" @endif><a href="{{ route('menus.index') }}">Manage Menu</a></li>
                <li @if (\Route::current()->getName() == 'menus.create') class="active" @endif><a href="{{ route('menus.create') }}">Create a Menu</a></li>
            </ul>
        </li>
    @endif

    @if (auth()->user()->has_access_to_news_module() || auth()->user()->has_access_to_news_categories_module())
        <li class="nav-item with-sub @if (request()->routeIs('news*') || request()->routeIs('news-categories*')) active show @endif">
            <a href="" class="nav-link"><i data-feather="edit"></i> <span>News</span></a>
            <ul>
                @if (auth()->user()->has_access_to_news_module())
                    <li @if (\Route::current()->getName() == 'news.index' || \Route::current()->getName() == 'news.edit'  || \Route::current()->getName() == 'news.index.advance-search') class="active" @endif><a href="{{ route('news.index') }}">Manage News</a></li>
                    <li @if (\Route::current()->getName() == 'news.create') class="active" @endif><a href="{{ route('news.create') }}">Create a News</a></li>
                    <li @if (\Route::current()->getName() == 'news.create') class="active" @endif><a href="{{ route('blogs.create') }}">Create a Blogs</a></li>
                @endif
                @if (auth()->user()->has_access_to_news_categories_module())
                    <li @if (\Route::current()->getName() == 'news-categories.index' || \Route::current()->getName() == 'news-categories.edit') class="active" @endif><a href="{{ route('news-categories.index') }}">Manage Categories</a></li>
                    <li @if (\Route::current()->getName() == 'news-categories.create') class="active" @endif><a href="{{ route('news-categories.create') }}">Create a Category</a></li>
                @endif
            </ul>
        </li>
    @endif

    <li class="nav-item with-sub @if (request()->routeIs('account*') || request()->routeIs('settings*') || request()->routeIs('audit*')) active show @endif">
        <a href="" class="nav-link"><i data-feather="settings"></i> <span>Settings</span></a>
        <ul>
            <li @if (\Route::current()->getName() == 'account.edit') class="active" @endif><a href="{{ route('account.edit', auth()->user()->id ) }}">Account Settings</a></li>
            {{-- @if (auth()->user()->has_access_to_cms_settings_module())
                <li @if (\Route::current()->getName() == 'settings.cms') class="active" @endif><a href="{{ route('settings.cms') }}">CMS Settings</a></li>
            @endif --}}
            @if (auth()->user()->has_access_to_website_settings_module())
                <li @if (\Route::current()->getName() == 'settings.edit') class="active" @endif><a href="{{ route('settings.edit', 1 ) }}">Website Settings</a></li>
            @endif
            @if (auth()->user()->has_access_to_audit_logs_module())
                <li @if (\Route::current()->getName() == 'settings.audit') class="active" @endif><a href="{{ route('settings.audit') }}">Audit Trail</a></li>
            @endif
        </ul>
    </li>

    <li class="nav-item">
        <a href="#" class="nav-link"><i data-feather="home"></i><span>Mailing List (No page yet)</span></a>
    </li>

    <li class="nav-label mg-t-25">E-Commerce</li>
    <li class="nav-item with-sub @if (request()->routeIs('products*') || request()->routeIs('product-categories*')) active show @endif">
        <a href="" class="nav-link"><i data-feather="box"></i> <span>Products</span></a>
        <ul>
            <li><a href="{{ route('products.index') }}">Manage Products</a></li>
            <li><a href="{{ route('products.create') }}">Create a Product</a></li>

            <li><a href="{{ route('product-categories.index') }}">Manage Categories</a></li>
            <li><a href="{{ route('product-categories.create') }}">Create a Category</a></li>

        </ul>
    </li>

    <li class="nav-item with-sub @if (request()->routeIs('customers*')) active show @endif">
        <a href="#" class="nav-link"><i data-feather="users"></i> <span>Customers</span></a>
        <ul>
            <li><a href="#">Manage Customers</a></li>
        </ul>
    </li>

    <li class="nav-item">
        <a href="#" class="nav-link"><i data-feather="home"></i><span>Order Delivery</span></a>
    </li>

    <li class="nav-item with-sub @if (request()->routeIs('deliveryrate*')) active show @endif">
        <a href="" class="nav-link"><i data-feather="box"></i> <span>Delivery Rates</span></a>
        <ul>
            <li><a href="{{ route('deliveryrate.index') }}">Manage Rates</a></li>
            <li><a href="{{ route('deliveryrate.create') }}">Create new rate</a></li>
        </ul>
    </li>

    <li class="nav-item with-sub @if (request()->routeIs('branch*')) active show @endif">
        <a href="" class="nav-link"><i data-feather="users"></i> <span>Branch</span></a>
        <ul>
            <li><a href="{{ route('branch.index') }}">Manage Branches</a></li>
            <li><a href="{{ route('branch.create') }}">Create Branches</a></li>
        </ul>
    </li>

    <li class="nav-item with-sub @if (request()->routeIs('production-branches*')) active show @endif">
        <a href="" class="nav-link"><i data-feather="users"></i> <span>Production Branches</span></a>
        <ul>
            <li><a href="{{ route('production-branches.index') }}">Manage Branches</a></li>
            <li><a href="{{ route('production-branches.create') }}">Create Branches</a></li>
        </ul>
    </li>

    <li class="nav-item with-sub @if (request()->routeIs('sales-transaction*')) active show @endif">
        <a href="" class="nav-link"><i data-feather="users"></i> <span>Sales Transaction</span></a>
        <ul>
            <li><a href="{{ route('sales-transaction.index') }}">Manage Sales Transaction</a></li>
        </ul>
    </li>
</ul>
