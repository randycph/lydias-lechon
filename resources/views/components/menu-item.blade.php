@php
    $url = '#';

    if ($item->page_id > 0 && $item->page) {
        $url = $item->page->slug ? route('page', ['slug' => $item->page->slug]) : '#';
    } elseif (!empty($item->uri)) {
        $url = $item->uri;
    }

    $hasChildren = $grouped->has($item->id);
@endphp

<style>
    .hoverable-parent {
        display: none;
    }

    .relative:hover > .hoverable-parent {
        display: block;
    }
</style>

<div class="relative">
    @if ($item->type == 'external' && $item->uri == 'https://modal')
        <button @click="openHotline = true" class="uppercase hover:bg-primary-dark py-4 px-2 transition-all duration-100 w-full text-left z-10">
            {{ $item->label }}
        </button>
    @else
        <a href="{{ $url }}" class="uppercase hover:bg-primary-dark py-4 px-2 transition-all duration-100 block w-full text-left z-10">
            {{ $item->label }}
        </a>
    @endif

    @if ($hasChildren)
        <div class="absolute bg-green-700 text-white shadow-md z-50 w-max hidden hoverable-parent 
            {{ isset($isChild) && $isChild ? 'left-full top-0' : 'top-full left-0' }}">
            <div class="flex flex-col">
                @foreach ($grouped[$item->id] as $child)
                    @include('components.menu-item', [
                        'item' => $child,
                        'grouped' => $grouped,
                        'isChild' => true
                    ])
                @endforeach
            </div>
        </div>
    @endif
</div>
