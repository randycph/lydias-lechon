<ul>
    @php
        $menu = \App\Models\Menu::where('is_active', 1)->first();
    @endphp
    @foreach ($menu->parent_navigation() as $item)
        @include('theme.'.config('app.frontend_template').'.layout.menu-item', ['item' => $item])
    @endforeach
</ul>
