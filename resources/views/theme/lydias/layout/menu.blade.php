<ul>
    @php
        $menu = \App\Menu::where('is_active', 1)->first();
    @endphp
    @foreach ($menu->parent_navigation() as $item)
        @include('theme.'.env('FRONTEND_TEMPLATE').'.layout.menu-item', ['item' => $item])
    @endforeach
</ul>
