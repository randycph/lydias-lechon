<div class="py-3">
    <div class="">
        <div class="flex justify-between items-center px-3">
            <div class="flex gap-2 items-center">
                <div class="text-2xl font-bold">{{ $dataPrivacy->title }}</div>
            </div>
            <button @click="showModal = false" class="self-end text-2xl text-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="text-gray-600 font-medium px-4 mt-4">
            {!! $dataPrivacy->contents !!}
        </div>
    </div>
</div>