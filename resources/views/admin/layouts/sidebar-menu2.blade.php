<ul class="nav nav-aside">

    <li class="nav-item">
        <a href="{{route('home')}}" target="_blank" class="nav-link">
            <i data-feather="external-link"></i>
            <span>View Website</span>
        </a>
    </li>

    <li class="nav-label mg-t-25">PORTAL</li>
    <li class="nav-item @if (url()->current() == route('dashboard')) active @endif">
        <a href="{{ route('dashboard') }}" class="nav-link"><i data-feather="home"></i><span>Dashboard</span></a>
    </li>

    @if (auth()->user()->has_access_to_module('customer'))
        <li class="nav-item with-sub @if (request()->routeIs('customers*')) active show @endif">
            <a href="#" class="nav-link"><i data-feather="users"></i> <span>Customers</span></a>
            <ul>
                <li @if (\Route::current()->getName() == 'customers.edit' || \Route::current()->getName() == 'customers.index') class="active" @endif><a href="{{ route('customers.index') }}">Manage Customers</a></li>
                @if(auth()->user()->has_access_to_route('customers.create'))
                    <li><a href="{{ route('customers.create') }}">Create New Customer</a></li>
                @endif
            </ul>
        </li>
    @endif

    @if (auth()->user()->has_access_to_module('job_order'))
        <li class="nav-item with-sub @if (request()->routeIs('job-orders*')) active show @endif">
            <a href="#" class="nav-link"><i data-feather="list"></i> <span>Job Orders</span></a>
            <ul>
                <li @if (\Route::current()->getName() == 'joborders.edit' || \Route::current()->getName() == 'joborders.index') class="active" @endif><a href="{{ route('joborders.index') }}">Manage Job Orders</a></li>
                @if(auth()->user()->has_access_to_route('joborders.create'))
                    <li @if (\Route::current()->getName() == 'customers.create') class="active" @endif><a href="{{ route('joborders.create') }}">Create Job Orders</a></li>
                @endif
                @if (auth()->user()->has_access_to_route('joborders.create-pantaga-or-display'))
                    <li><a href="{{ route('joborders.create-pantaga-or-display') }}">Create Pantaga/Display</a></li>
                @endif
            </ul>
        </li>
    @endif

    @if (auth()->user()->has_access_to_module('forecaster'))
        <li class="nav-item @if (request()->routeIs('forecaster*')) active show @endif">
            <a href="{{route('forecaster.index')}}" class="nav-link"><i data-feather="bar-chart"></i> <span>Forecaster</span></a>
        </li>
    @endif

    @if(auth()->user()->has_access_to_module('left_over') && !auth()->user()->is_an_admin())
        <li class="nav-item @if (request()->routeIs('leftover*')) active show @endif">
            <a href="{{route('leftover.index')}}" class="nav-link"><i data-feather="bar-chart"></i> <span>Daily Leftover</span></a>
        </li>
    @endif

    @if (auth()->user()->has_access_to_module('reports'))
        <li class="nav-item with-sub @if (request()->routeIs('reports*')) active show @endif">
            <a href="#" class="nav-link"><i data-feather="pie-chart"></i> <span>Reports</span></a>
            <ul>
                @if(auth()->user()->has_access_to_route('admin.report.sales'))
                    <li><a target="_blank" href="{{route('admin.report.sales')}}">Sales Summary Report</a></li>
                @endif
                {{--                @if(auth()->user()->has_access_to_route('admin.report.sales'))--}}
                {{--                    <li><a target="_blank" href="{{route('admin.report.sales')}}" style="display:none;">Branch Sales Summary</a></li>--}}
                {{--                @endif--}}
                @if(auth()->user()->has_access_to_route('admin.report.sales_payment'))
                    <li><a target="_blank" href="{{route('admin.report.sales_payment')}}">Sales Payment Report</a></li>
                @endif
                @if(auth()->user()->has_access_to_route('admin.report.delivery_status'))
                    <li><a target="_blank" href="{{route('admin.report.delivery_status')}}">Delivery Status Report</a></li>
                @endif
                @if(auth()->user()->has_access_to_route('admin.report.joborder'))
                    <li><a target="_blank" href="{{route('admin.report.joborder')}}">Job Order Report</a></li>
                @endif
                @if(auth()->user()->has_access_to_route('admin.report.leftover'))
                    <li><a target="_blank" href="{{route('admin.report.leftover')}}">Leftover Report</a></li>
                @endif
                @if(auth()->user()->has_access_to_route('admin.report.sales-per-agent'))
                    <li><a target="_blank" href="{{route('admin.report.sales-per-agent')}}">Sales per Agent</a></li>
                @endif
                @if(auth()->user()->has_access_to_route('admin.report.sales-per-customer'))
                    <li><a target="_blank" href="{{route('admin.report.sales-per-customer')}}">Sales per Customer</a></li>
                @endif
                @if(auth()->user()->has_access_to_route('admin.report.forecaster'))
                    <li><a target="_blank" href="{{route('admin.report.forecaster')}}">Forecaster Report</a></li>
                @endif
                @if(auth()->user()->has_access_to_route('admin.report.door2door_report'))
                    <li><a target="_blank" href="{{route('admin.report.door2door_report')}}">Delivery Report</a></li>
                @endif
                
                
                {{--<li><a target="_blank" href="{{route('admin.report.door2door_report')}}">Dispatcher Report</a></li>--}}
                <li><a target="_blank" href="{{route('admin.report.sales_category')}}">Sales by Category Report</a></li>
                <li><a target="_blank" href="{{route('admin.report.sales-per-branch')}}">Sales by Branch Report</a></li>
                <li><a target="_blank" href="{{route('admin.report.sales_social')}}">Sales by Channel Report</a></li>
                <li><a target="_blank" href="{{route('admin.report.top_products')}}">Most Saleable Products</a></li>
                <li><a target="_blank" href="{{route('admin.report.top_agents')}}">Top Agents Report</a></li>
                <li><a target="_blank" href="{{route('admin.report.guest_orders')}}">Guest Logins Report</a></li>
             
            </ul>
        </li>
    @endif

    <li class="nav-label mg-t-25">CMS</li>
    @if (auth()->user()->has_access_to_pages_module())
        <li class="nav-item with-sub @if (request()->routeIs('pages*')) active show @endif">
            <a href="" class="nav-link"><i data-feather="layers"></i> <span>Pages</span></a>
            <ul>
                <li @if (\Route::current()->getName() == 'pages.edit' || \Route::current()->getName() == 'pages.index' || \Route::current()->getName() == 'pages.index.advance-search') class="active" @endif><a href="{{ route('pages.index') }}">Manage Pages</a></li>
                @if(auth()->user()->has_access_to_route('pages.create'))
                    <li @if (\Route::current()->getName() == 'pages.create') class="active" @endif><a href="{{ route('pages.create') }}">Create a Page</a></li>
                @endif
            </ul>
        </li>
    @endif
    @if (auth()->user()->has_access_to_albums_module())
        <li class="nav-item with-sub @if (request()->routeIs('albums*')) active show @endif">
            <a href="#" class="nav-link"><i data-feather="image"></i> <span>Banners</span></a>
            <ul>
                @if(auth()->user()->has_access_to_route('albums.edit'))
                    <li @if (url()->current() == route('albums.edit', 1)) class="active" @endif><a href="{{ route('albums.edit', 1) }}">Manage Home Banner</a></li>
                @endif
                <li @if (\Route::current()->getName() == 'albums.index' || (\Route::current()->getName() == 'albums.edit' && url()->current() != route('albums.edit', 1))) class="active" @endif><a href="{{ route('albums.index') }}">Manage Subpage Banners</a></li>
                @if(auth()->user()->has_access_to_route('albums.create'))
                    <li @if (\Route::current()->getName() == 'albums.create') class="active" @endif><a href="{{ route('albums.create') }}">Create an Album</a></li>
                @endif
            </ul>
        </li>
    @endif
    @if (auth()->user()->has_access_to_file_manager_module())
        <li class="nav-item @if (\Route::current()->getName() == 'file-manager.index') active @endif">
            <a href="{{ route('file-manager.index') }}" class="nav-link"><i data-feather="folder"></i> <span>Files</span></a>
        </li>
    @endif
    @if (auth()->user()->has_access_to_menu_module())
        <li class="nav-item with-sub @if (request()->routeIs('menus*')) active show @endif">
            <a href="" class="nav-link"><i data-feather="menu"></i> <span>Menu</span></a>
            <ul>
                <li @if (\Route::current()->getName() == 'menus.edit' || \Route::current()->getName() == 'menus.index') class="active" @endif><a href="{{ route('menus.index') }}">Manage Menu</a></li>

                @if(auth()->user()->has_access_to_route('menus.create'))
                    <li @if (\Route::current()->getName() == 'menus.create') class="active" @endif><a href="{{ route('menus.create') }}">Create a Menu</a></li>
                @endif
            </ul>
        </li>
    @endif
    @if (auth()->user()->has_access_to_news_module() || auth()->user()->has_access_to_news_categories_module())
        <li class="nav-item with-sub @if (request()->routeIs('news*') || request()->routeIs('news-categories*')) active show @endif">
            <a href="" class="nav-link"><i data-feather="edit"></i> <span>News</span></a>
            <ul>
                @if (auth()->user()->has_access_to_news_module())
                    <li @if (\Route::current()->getName() == 'news.index' || \Route::current()->getName() == 'news.edit'  || \Route::current()->getName() == 'news.index.advance-search') class="active" @endif><a href="{{ route('news.index') }}">Manage News</a></li>
                    @if(auth()->user()->has_access_to_route('news.create'))
                        <li @if (\Route::current()->getName() == 'news.create') class="active" @endif><a href="{{ route('news.create') }}">Create a News</a></li>
                    @endif
                @endif
                @if (auth()->user()->has_access_to_news_categories_module())
                    <li @if (\Route::current()->getName() == 'news-categories.index' || \Route::current()->getName() == 'news-categories.edit') class="active" @endif><a href="{{ route('news-categories.index') }}">Manage Categories</a></li>
                    @if(auth()->user()->has_access_to_route('news-categories.create'))
                        <li @if (\Route::current()->getName() == 'news-categories.create') class="active" @endif><a href="{{ route('news-categories.create') }}">Create a Category</a></li>
                    @endif
                @endif
            </ul>
        </li>
    @endif
    <li class="nav-item with-sub @if (request()->routeIs('account*') || request()->routeIs('settings*') || request()->routeIs('audit*')) active show @endif">
        <a href="" class="nav-link"><i data-feather="settings"></i> <span>Settings</span></a>
        <ul>
            <li @if (\Route::current()->getName() == 'account.edit') class="active" @endif><a href="{{ route('account.edit', auth()->id()) }}">Account Settings</a></li>
            @if (auth()->user()->has_access_to_website_settings_module())
                <li @if (\Route::current()->getName() == 'settings.edit') class="active" @endif><a href="{{ route('settings.edit', 1) }}">Website Settings</a></li>
            @endif
            @if (auth()->user()->has_access_to_audit_logs_module())
                <li @if (\Route::current()->getName() == 'settings.audit') class="active" @endif><a href="{{ route('settings.audit') }}">Audit Trail</a></li>
            @endif
        </ul>
    </li>
    @if (auth()->user()->has_access_to_user_module())
        <li class="nav-item with-sub @if (request()->routeIs('users*')) active show @endif">
            <a href="" class="nav-link"><i data-feather="users"></i> <span>Users</span></a>
            <ul>
                <li @if (\Route::current()->getName() == 'users.index' || \Route::current()->getName() == 'users.edit') class="active" @endif><a href="{{ route('users.index') }}">Manage Users</a></li>
                @if(auth()->user()->has_access_to_route('users.create'))
                    <li @if (\Route::current()->getName() == 'users.create') class="active" @endif><a href="{{ route('users.create') }}">Create a User</a></li>
                @endif
            </ul>
        </li>
    @endif
    @if (auth()->user()->is_an_admin())
        <li class="nav-item with-sub @if (request()->routeIs('role*') || request()->routeIs('access*') || request()->routeIs('permission*')) active show @endif">
            <a href="" class="nav-link"><i data-feather="user"></i> <span>Account Management</span></a>
            <ul>
                <li @if (request()->routeIs('role*')) class="active" @endif><a href="{{ route('role.index') }}">Roles</a></li>
                @if (auth()->user()->role_id == 1)
                    <li @if (request()->routeIs('access*')) class="active" @endif><a href="{{ route('access.index') }}">Access Rights</a></li>
                @endif
                @if (env('APP_DEBUG') == "true")
                    <li @if (request()->routeIs('permission*')) class="active" @endif><a href="{{ route('permission.index') }}">Permissions</a></li>
                @endif
            </ul>
        </li>
    @endif

    @if (auth()->user()->has_access_to_module('sent_item') || auth()->user()->has_access_to_module('subscriber') ||
        auth()->user()->has_access_to_module('campaign') || auth()->user()->has_access_to_module('subscriber_group'))
        <li class="nav-item with-sub @if (request()->routeIs('mailing-list*')) active show @endif">
            <a href="" class="nav-link"><i data-feather="credit-card"></i> <span>Mailing List</span></a>
            <ul>
                @if (auth()->user()->has_access_to_module('subscriber'))
                    <li @if (\Route::current()->getName() == 'mailing-list.subscribers.index' || \Route::current()->getName() == 'mailing-list.subscribers.edit') class="active" @endif><a href="{{ route('mailing-list.subscribers.index') }}">Manage Subscribers</a></li>
                    @if(auth()->user()->has_access_to_route('mailing-list.subscribers.create'))
                        <li @if (\Route::current()->getName() == 'mailing-list.subscribers.create') class="active" @endif><a href="{{ route('mailing-list.subscribers.create') }}">Create a Subscriber</a></li>
                    @endif
                    <li @if (\Route::current()->getName() == 'mailing-list.subscribers.unsubscribe') class="active" @endif><a href="{{ route('mailing-list.subscribers.unsubscribe') }}">Cancelled Subscription</a></li>
                @endif
                @if (auth()->user()->has_access_to_module('subscriber_group'))
                    <li @if (\Route::current()->getName() == 'mailing-list.groups.index' || \Route::current()->getName() == 'mailing-list.groups.edit') class="active" @endif><a href="{{ route('mailing-list.groups.index') }}">Manage Groups</a></li>
                    @if(auth()->user()->has_access_to_route('mailing-list.groups.create'))
                        <li @if (\Route::current()->getName() == 'mailing-list.groups.create') class="active" @endif><a href="{{ route('mailing-list.groups.create') }}">Create a Group</a></li>
                    @endif
                @endif
                @if (auth()->user()->has_access_to_module('campaign'))
                    <li @if (\Route::current()->getName() == 'mailing-list.campaigns.index' || \Route::current()->getName() == 'mailing-list.campaigns.edit') class="active" @endif><a href="{{ route('mailing-list.campaigns.index') }}">Manage Campaigns</a></li>
                    @if(auth()->user()->has_access_to_route('mailing-list.campaigns.create'))
                        <li @if (\Route::current()->getName() == 'mailing-list.campaigns.create') class="active" @endif><a href="{{ route('mailing-list.campaigns.create') }}">Create a Campaign</a></li>
                    @endif
                    @if(auth()->user()->has_access_to_route('mailing-list.campaigns.sent-campaigns'))
                        <li @if (\Route::current()->getName() == 'mailing-list.campaigns.sent-campaigns') class="active" @endif><a href="{{ route('mailing-list.campaigns.sent-campaigns') }}">Sent Items</a></li>
                    @endif
                @endif
            </ul>
        </li>
    @endif

    @if (auth()->user()->has_access_to_module('products') || auth()->user()->has_access_to_module('product_category') ||
        auth()->user()->has_access_to_module('production_branch') || auth()->user()->has_access_to_module('gift_certificate') ||
        auth()->user()->has_access_to_module('delivery_rate') || auth()->user()->has_access_to_module('branch') ||
        auth()->user()->has_access_to_module('sales_transaction'))
        <li class="nav-label mg-t-25">E-Commerce</li>
        
        <li class="nav-item @if (url()->current() == route('ecom-dashboard')) active @endif">
            <a href="{{ route('ecom-dashboard') }}" class="nav-link"><i data-feather="home"></i><span>Dashboard</span></a>
        </li>

        @if (auth()->user()->has_access_to_module('product') || auth()->user()->has_access_to_module('product_category'))
            <li class="nav-item with-sub @if (request()->routeIs('products*') || request()->routeIs('product-categories*')) active show @endif">
                <a href="" class="nav-link"><i data-feather="box"></i> <span>Products</span></a>
                <ul>
                    @if (auth()->user()->has_access_to_module('product'))
                        <li @if (\Route::current()->getName() == 'products.index' || \Route::current()->getName() == 'products.edit') class="active" @endif><a href="{{ route('products.index') }}">Manage Products</a></li>
                        @if(auth()->user()->has_access_to_route('products.create'))
                            <li @if (\Route::current()->getName() == 'products.create') class="active" @endif><a href="{{ route('products.create') }}">Create a Product</a></li>
                        @endif
                    @endif
                    @if (auth()->user()->has_access_to_module('product_category'))
                        <li @if (\Route::current()->getName() == 'product-categories.index' || \Route::current()->getName() == 'product-categories.edit') class="active" @endif><a href="{{ route('product-categories.index') }}">Manage Categories</a></li>
                        @if(auth()->user()->has_access_to_route('product-categories.create'))
                            <li @if (\Route::current()->getName() == 'product-categories.create') class="active" @endif><a href="{{ route('product-categories.create') }}">Create a Category</a></li>
                        @endif]
                    @endif
                </ul>
            </li>
        @endif

        @if (auth()->user()->has_access_to_module('production_branch'))
            <li class="nav-item with-sub @if (request()->routeIs('production-branches*')) active show @endif">
                <a href="" class="nav-link"><i data-feather="users"></i> <span>Production Branches</span></a>
                <ul>
                    <li @if (\Route::current()->getName() == 'production-branches.index' || \Route::current()->getName() == 'production-branches.edit') class="active" @endif><a href="{{ route('production-branches.index') }}">Manage Branches</a></li>
                    @if(auth()->user()->has_access_to_route('production-branches.create'))
                        <li @if (\Route::current()->getName() == 'production-branches.create') class="active" @endif><a href="{{ route('production-branches.create') }}">Create Branches</a></li>
                    @endif
                </ul>
            </li>
        @endif

        @if (auth()->user()->has_access_to_module('gift_certificate'))
            <li class="nav-item with-sub @if (request()->routeIs('gift-certificate*')) active show @endif">
                <a href="" class="nav-link"><i data-feather="users"></i> <span>Gift Certificate</span></a>
                <ul>
                    <li @if (\Route::current()->getName() == 'gift-certificate.index' || \Route::current()->getName() == 'gift-certificate.edit') class="active" @endif><a href="{{ route('gift-certificate.index') }}">Manage Gift Certificate</a></li>
                    @if(auth()->user()->has_access_to_route('gift-certificate.create'))
                        <li @if (\Route::current()->getName() == 'gift-certificate.create') class="active" @endif><a href="{{ route('gift-certificate.create') }}">Create Gift Certificate</a></li>
                        <li @if (\Route::current()->getName() == 'gift-certificate.upload') class="active" @endif><a href="{{ route('gift-certificate.upload') }}">Upload Gift Certificate</a></li>
                    @endif
                </ul>
            </li>
        @endif

        @if (auth()->user()->has_access_to_module('delivery_rate'))
            <li class="nav-item with-sub @if (\Route::current()->getName() == 'admin.locations.index' || \Route::current()->getName() == 'admin.locations.create' || \Route::current()->getName() == 'admin.locations.edit') active show @endif">
                <a href="" class="nav-link"><i data-feather="box"></i> <span>Delivery Rates</span></a>
                <ul>
                    <li @if (\Route::current()->getName() == 'admin.locations.index' || \Route::current()->getName() == 'admin.locations.edit') class="active" @endif><a href="{{ route('admin.locations.index') }}">Manage Rates</a></li>
                    @if(auth()->user()->has_access_to_route('admin.locations.create'))
                        <li @if (\Route::current()->getName() == 'admin.locations.create') class="active" @endif><a href="{{ route('admin.locations.create') }}">Create new rate</a></li>
                    @endif
                </ul>
            </li>
        @endif

        @if (auth()->user()->has_access_to_module('branch'))
            <li class="nav-item with-sub @if (request()->routeIs('branch*')) active show @endif">
                <a href="" class="nav-link"><i data-feather="users"></i> <span>Branch</span></a>
                <ul>
                    <li @if (\Route::current()->getName() == 'branch.index' || \Route::current()->getName() == 'branch.edit') class="active" @endif><a href="{{ route('branch.index') }}">Manage Branches</a></li>
                    @if(auth()->user()->has_access_to_route('branch.create'))
                        <li @if (\Route::current()->getName() == 'branch.create') class="active" @endif><a href="{{ route('branch.create') }}">Create Branches</a></li>
                    @endif
                </ul>
            </li>
        @endif

        @if (auth()->user()->has_access_to_module('sales_transaction'))
            <li class="nav-item with-sub @if (request()->routeIs('sales-transaction*')) active show @endif">
                <a href="" class="nav-link"><i data-feather="users"></i> <span>Sales Transaction</span></a>
                <ul>
                    <li @if (\Route::current()->getName() == 'sales-transaction.create') class="active" @endif><a href="{{ route('sales-transaction.index') }}">Manage Sales Transaction</a></li>

                    <li @if (\Route::current()->getName() == 'sales-transaction.payments') class="active" @endif><a href="{{ route('sales-transaction.payments') }}">Sales Transaction Payments</a></li>
                    
                </ul>
            </li>
        @endif
        
        <li class="nav-item with-sub @if (request()->routeIs('shareable-links*')) active show @endif">
            <a href="" class="nav-link"><i data-feather="users"></i> <span>Social Media Shareable Links</span></a>
            <ul>
                <li @if (\Route::current()->getName() == 'shareable-links.index' || \Route::current()->getName() == 'shareable-links.edit') class="active" @endif><a href="{{ route('shareable-links.index') }}">Manage Links</a></li>
                @if(auth()->user()->has_access_to_route('shareable-links.create'))
                    <li @if (\Route::current()->getName() == 'shareable-links.create') class="active" @endif><a href="{{ route('shareable-links.create') }}">Create Shareable Link</a></li>
                @endif
            </ul>
        </li>
        
    @endif
</ul>
