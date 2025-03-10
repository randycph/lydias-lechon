<aside class="aside aside-fixed">
    <div class="aside-header">
        <a href="{{ route('dashboard') }}" class="aside-logo">Admin <span>Portal</span></a>
        <a href="" class="aside-menu-link">
            <i data-feather="menu"></i>
            <i data-feather="x"></i>
        </a>
    </div>
    <div class="aside-body">
        <div class="aside-loggedin">
            @if(Auth::user()->avatar == '')
                <div class="d-flex justify-content-center">
                    <a href="{{ route('account.edit', Auth::user()->id ) }}" class="avatar wd-100"><img src="{{ asset('images/user.png') }}" class="rounded-circle" alt=""></a>
                </div>
                <div class="aside-loggedin-user tx-center">
                    <h6 class="tx-semibold mg-b-0">{{ Auth::user()->fullname }}</h6>
{{--                    <p class="tx-color-03 tx-11 mg-b-0 tx-uppercase">{{ App\Models\User::userRole(Auth::user()->role_id) }}</p>--}}
                    <p class="tx-color-03 tx-11 mg-b-0 tx-uppercase">{{ Auth::user()->user_role->name }}</p>
                </div>
            @else
                <div class="d-flex justify-content-center">
                    <a href="{{ route('account.edit', Auth::user()->id ) }}" class="avatar wd-100"><img src="{{ Auth::user()->avatar }}" class="rounded-circle" alt=""></a>
                </div>
                <div class="aside-loggedin-user tx-center">
                    <h6 class="tx-semibold mg-b-0">{{ Auth::user()->fullname }}</h6>
                    <p class="tx-color-03 tx-11 mg-b-0 tx-uppercase">{{ App\Models\User::userRole(Auth::user()->role_id) }}</p>
                </div>
            @endif
        </div>
        <!-- aside-loggedin -->
{{--        @if(auth()->user()->is_a_cms_user())--}}
            @include('admin.layouts.sidebar-menu2')
{{--        @endif--}}

{{--        @if(auth()->user()->is_a_customer_user())--}}
{{--            @include('admin.layouts.sidebar-menu-customer')--}}
{{--        @endif--}}

{{--        @if(auth()->user()->is_a_branch_user())--}}
{{--            @include('admin.layouts.sidebar-menu-branch')--}}
{{--        @endif--}}

{{--        @if(auth()->user()->is_a_staff_user())--}}
{{--            @include('admin.layouts.sidebar-menu-staff')--}}
{{--        @endif--}}

{{--        @if(auth()->user()->is_a_forecaster_user())--}}
{{--            @include('admin.layouts.sidebar-menu-forecaster')--}}
{{--        @endif--}}

{{--        @if(auth()->user()->is_a_dispatcher_user())--}}
{{--            @include('admin.layouts.sidebar-menu-dispatcher')--}}
{{--        @endif--}}
    </div>
</aside>
