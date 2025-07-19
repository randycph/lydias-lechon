@foreach ($articles as $blog)
    <a href="{{ route('article', ['category' => $blog->category->slug, 'slug' => $blog->slug]) }}" class="rounded-lg">
        <img onerror="this.onerror=null;this.src='{{ asset('images/no-image.jpg') }}'" src="{{ $blog->thumbnail_url }}" alt="{{ $blog->name }}" class="w-full h-[188px] lg:h-[362px] object-cover rounded-t-lg hover:scale-105 transition duration-300 hover:opacity-80">
        <div class="uppercase text-base lg:text-lg font-semibold py-2 px-1">{{ $blog->name }}</div>
    </a>
@endforeach
