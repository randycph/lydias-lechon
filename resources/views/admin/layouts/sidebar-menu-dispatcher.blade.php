<ul class="nav nav-aside">
    <li class="nav-item">
        <a href="{{route('home')}}" target="_blank" class="nav-link">
            <i data-feather="external-link"></i>
            <span>View Website</span>
        </a>
    </li>

    <li class="nav-label mg-t-25">DISPATCHER PORTAL</li>
    
    

    <li class="nav-item">
        <a target="_blank" href="{{route('admin.report.joborder')}}" class="nav-link"><i data-feather="list"></i> Job Order Report</a>
    </li>
    <li class="nav-item">
        <a target="_blank" href="{{route('admin.report.forecaster')}}" class="nav-link"><i data-feather="pie-chart"></i> Forecaster Report</a>
    </li>
    <li class="nav-item">
        <a href="{{ route('account.edit', auth()->user()->id ) }}" class="nav-link"><i data-feather="settings"></i> Account Settings</a>
    </li>

        
   
    
</ul>
