<div class="rounded-lg border bg-white border-[#DFDFDF] shadow-md">
    <div class="px-6 py-4 border-b border-[#DFDFDF]">
        <h2 class="text-lg font-bold text-primary text-left uppercase">my account</h2>
    </div>
    <div class="flex items-start font-bold gap-4 flex-col px-6 py-5 border-b border-[#DFDFDF]">
        <div class="">Hi, {{ auth()->check() && auth()->user()?->is_org ? auth()->user()->organization : auth()->user()?->name }}</div>
        <div class="text-tertiary  underline"><a href="{{ route('user-logout') }}">Sign out</a></div>
    </div>
    <div class="flex items-start font-bold flex-col gap-2  py-5 border-b border-[#DFDFDF]">

        <a href="{{ route('my-account') }}" class="group relative items-center flex pl-6 hover:text-tertiary  cursor-pointer {{ route('my-account') == request()->url() ? 'text-tertiary' : '' }}">
            <div class="absolute left-0 top-0 h-10 w-1 bg-orange-500 opacity-0 group-hover:opacity-100 transition duration-200 {{ route('my-account') == request()->url() ? 'opacity-100' : 'opacity-0' }}"></div>
            <div class="py-2">Manage Account</div>
        </a>

         <a href="{{ route('my-coupons') }}" 
        class="group relative items-center flex pl-6 hover:text-tertiary cursor-pointer 
        {{ request()->routeIs('my-coupons') ? 'text-tertiary' : '' }}">
            
            <div class="absolute left-0 top-0 h-10 w-1 bg-orange-500 opacity-0 
                group-hover:opacity-100 transition duration-200 
                {{ request()->routeIs('my-coupons') ? 'opacity-100' : 'opacity-0' }}">
            </div>

            <div class="py-2">Coupons</div>
        </a>

        
        @if (Auth::check() && Auth()->user()->role_id == 6)
        <a href="{{ route('my-cart') }}" class="group relative items-center flex pl-6 hover:text-tertiary  cursor-pointer {{ route('my-cart') == request()->url() ? 'text-tertiary' : '' }}">
            <div class="absolute left-0 top-0 h-10 w-1 bg-orange-500 opacity-0 group-hover:opacity-100 transition duration-200 {{ route('my-cart') == request()->url() ? 'opacity-100' : 'opacity-0' }}"></div>
            <div class="py-2">My Cart</div>
        </a>
        @endif
        
        <a href="{{ route('change-password') }}" class="group relative items-center flex pl-6 hover:text-tertiary cursor-pointer {{ route('change-password') == request()->url() ? 'text-tertiary' : '' }}">
            <div class="absolute left-0 top-0 h-10 w-1 bg-orange-500 opacity-0 group-hover:opacity-100 transition duration-200 {{ route('change-password') == request()->url() ? 'opacity-100' : 'opacity-0' }}"></div>
            <div class="py-2">Change Password</div>
        </a>

      
        
        @if (Auth::check() && Auth()->user()->role_id == 6)
        <a href="{{ route('order-history') }}" class="group relative items-center flex pl-6 hover:text-tertiary cursor-pointer {{ route('order-history') == request()->url() ? 'text-tertiary' : '' }}">
            <div class="absolute left-0 top-0 h-10 w-1 bg-orange-500 group-hover:opacity-100 transition duration-200 {{ route('order-history') == request()->url() ? 'opacity-100' : 'opacity-0' }}"></div>
            <div class="py-2">Order History</div>
        </a>
        @endif
        
        {{-- @if (Auth::check() && Auth()->user()->role_id == 6)
        <a href="{{ route('my-coupons') }}" class="group relative items-center flex pl-6 hover:text-tertiary cursor-pointer {{ route('my-coupons') == request()->url() ? 'text-tertiary' : '' }}">
            <div class="absolute left-0 top-0 h-10 w-1 bg-orange-500 group-hover:opacity-100 transition duration-200 {{ route('my-coupons') == request()->url() ? 'opacity-100' : 'opacity-0' }}"></div>
            <div class="py-2">Coupons</div>
        </a>
        @endif --}}
    </div>
</div>