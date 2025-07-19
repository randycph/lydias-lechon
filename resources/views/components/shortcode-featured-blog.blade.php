<div class="relative mx-auto pb-12">
    <div class="bg-tertiary container">
        <div class="flex flex-col lg:flex-row">
            <img class="w-full lg:w-1/2" onerror="this.onerror=null;this.src='{{ asset('images/no-image.jpg') }}'" src="{{ $featuredBlog?->image_url ?? $featuredBlog?->thumbnail_url }}" alt="Lydiandary the story of how a little girl’s idea became the world famous Lydia’s Lechon">
            <div class="w-full lg:w-1/2 p-2 lg:p-10">
                <div class="p-6 text-white">
                    <div class="font-light text-base lg:text-xl">{{ $featuredBlog?->created_at->format('F d, Y') }}</div>
                    <h2 class="text-3xl lg:text-5xl font-semibold mt-5">{{ $featuredBlog->name }}</h2>
                </div>
                <div class="px-6 pb-6 lg:pt-10">
                    <div class="rounded-md border border-white py-3 px-10 w-max custom-btn btn-tertiary">
                        <a href="{{ route('article', ['category' => $featuredBlog?->category->slug, 'slug' => $featuredBlog?->slug]) }}" class="text-center text-base lg:text-xl  text-white flex justify-center relative">Read Article</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>