<ul class="nav nav-aside">
    <li class="nav-item">
        <a href="{{route('home')}}" target="_blank" class="nav-link">
            <i data-feather="external-link"></i>
            <span>View Website</span>
        </a>
    </li>

    <li class="nav-label mg-t-25">STAFF PORTAL</li>

    <li class="nav-item with-sub @if (request()->routeIs('customers*')) active show @endif">
        <a href="#" class="nav-link"><i data-feather="users"></i> <span>Customers</span></a>
        <ul>
            <li><a href="{{ route('customers.index') }}">Manage Customers</a></li>
        </ul>
    </li>

    <li class="nav-item with-sub @if (request()->routeIs('account*') || request()->routeIs('settings*') || request()->routeIs('audit*')) active show @endif">
        <a href="" class="nav-link"><i data-feather="settings"></i> <span>Settings</span></a>
        <ul>
            <li @if (\Route::current()->getName() == 'account.edit') class="active" @endif><a href="{{ route('account.edit', auth()->user()->id ) }}">Account Settings</a></li>
        </ul>
    </li>

    <li class="nav-item with-sub @if (request()->routeIs('sales-transaction*')) active show @endif">
        <a href="" class="nav-link"><i data-feather="users"></i> <span>Sales Transaction</span></a>
        <ul>
            <li><a href="{{ route('sales-transaction.index') }}">Manage Sales Transaction</a></li>
        </ul>
    </li>

    <li class="nav-item with-sub @if (request()->routeIs('reports*')) active show @endif">
        <a href="#" class="nav-link"><i data-feather="pie-chart"></i> <span>Reports</span></a>
        <ul>
            <li><a target="_blank" href="{{route('admin.report.leftover')}}">Leftover Report</a></li>
        </ul>
    </li>
</ul>
