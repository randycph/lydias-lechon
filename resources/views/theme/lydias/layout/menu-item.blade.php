@php
    $page = $item->page;
@endphp
@if (!empty($page) && $item->is_page_type() && $page->is_published())
    <li @if(url()->current() == \URL::to('/').'/'.$page->slug || ($page->id == 1 && url()->current() == \URL::to('/'))) class="active" @endif>
        <a href="{{ url('/') }}/{{$page->slug}}">
            @if (!empty($page->label))
                {{ $page->label }}
            @else
                {{ $page->name }}
            @endif
        </a>
{{--        strtolower($item->sub_pages->unique('status')->values()->first->status) == "published"--}}
        @if ($item->has_sub_menus())
            <ul>
                @foreach ($item->sub_pages as $subItem)
                    @include('theme.'.config('app.frontend_template').'.layout.menu-item', ['item' => $subItem])
                @endforeach

{{--            START NEWS CATEGORY--}}
{{--                To add news category in news navigation--}}
{{--                @if (isset($type) && $type == "news")--}}
{{--                    @php--}}
{{--                        $cats = \App\Category::all();--}}
{{--                        foreach($cats as $cat){--}}
{{--                            echo '<li><a href="/news?type=category&criteria='.$cat->id.'">'.$cat->name.'</a></li>';--}}
{{--                        }--}}
{{--                        $uncats = \App\Models\Article::where('category_id', null)->orWhere('category_id', 0)->get();--}}
{{--                        if(count($uncats)) {--}}
{{--                            echo '<li><a href="/news?type=category&criteria=0">Uncategorized</a></li>';--}}
{{--                        }--}}
{{--                    @endphp--}}
{{--                @endif--}}
{{--             END NEWS CATEGORY--}}

            </ul>
        @endif
    </li>
@elseif ($item->is_external_type())
    <li>
        <a href="{{ $item->uri }}" target="{{ $item->target }}">{{ $item->label }}</a>
        @if ($item->has_sub_menus())
            <ul>
                @foreach ($item->sub_pages as $subItem)
                    @include('theme.'.config('app.frontend_template').'.layout.menu-item', ['item' => $subItem])
                @endforeach
            </ul>
        @endif
    </li>
@endif
