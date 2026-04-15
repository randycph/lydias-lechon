<div {{ $attributes->merge([
        'class' => 'px-4 py-3 border-b border-[#DFDFDF]'
    ]) }}>
    
    <div class="flex items-center justify-between">

        <h2 class="text-lg lg:text-2xl font-semibold">
            {{ $title }}
        </h2>

        @isset($right)
            <div>
                {{ $right }}
            </div>
        @endisset

    </div>

    @isset($subtitle)
        <p class="text-sm text-gray-500 mt-1">
            {{ $subtitle }}
        </p>
    @endisset

</div>
