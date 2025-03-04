<ul class="nav nav-aside">
    <li class="nav-item">
        <a href="{{route('home')}}" target="_blank" class="nav-link">
            <i data-feather="external-link"></i>
            <span>View Website</span>
        </a>
    </li>

    <li class="nav-label mg-t-25">FORECASTER PORTAL</li>

    <li class="nav-item with-sub @if (request()->routeIs('job-orders*')) active show @endif">
        <a href="#" class="nav-link"><i data-feather="list"></i> <span>Job Orders</span></a>
        <ul>
            <li><a href="{{ route('joborders.index') }}">Manage Job Orders</a></li>
            <li><a href="{{ route('joborders.create') }}">Create Job Order</a></li>
            <li><a href="{{ route('joborders.create-pantaga-or-display') }}">Create Pantaga/Display</a></li>
        </ul>
    </li>

    <li class="nav-item @if (request()->routeIs('forecaster*')) active show @endif">
        <a href="{{route('forecaster.index')}}" class="nav-link"><i data-feather="bar-chart"></i> <span>Forecaster</span></a>
    </li>

    <li class="nav-item with-sub @if (request()->routeIs('account*') || request()->routeIs('settings*') || request()->routeIs('audit*')) active show @endif">
        <a href="" class="nav-link"><i data-feather="settings"></i> <span>Settings</span></a>
        <ul>
            <li @if (\Route::current()->getName() == 'account.edit') class="active" @endif><a href="{{ route('account.edit', auth()->user()->id ) }}">Account Settings</a></li>
        </ul>
    </li>

    <li class="nav-item with-sub @if (request()->routeIs('reports*')) active show @endif">
        <a href="#" class="nav-link"><i data-feather="pie-chart"></i> <span>Reports</span></a>
        <ul>
            <li><a target="_blank" href="{{route('admin.report.delivery_status')}}">Delivery Status Report</a></li>
            <li><a target="_blank" href="{{route('admin.report.joborder')}}">Job Order Report</a></li>
            <li><a target="_blank" href="{{route('admin.report.forecaster')}}">Forecast Report</a></li>            
            <li><a target="_blank" href="{{route('admin.report.door2door_report')}}">Delivery Report</a></li>
        </ul>
    </li>

  
    <li class="nav-item with-sub @if (request()->routeIs('sales-transaction*')) active show @endif">
        <a href="" class="nav-link"><i data-feather="users"></i> <span>Sales Transaction</span></a>
        <ul>
            <li @if (\Route::current()->getName() == 'sales-transaction.create') class="active" @endif><a href="{{ route('sales-transaction.index') }}">Manage Sales Transaction</a></li>
        </ul>
    </li>
       
</ul>
