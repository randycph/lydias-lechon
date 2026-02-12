<div
    x-cloak
    x-show="privacyModal"
    x-transition.opacity
    class="fixed inset-0 z-50"
>

    {{-- BACKDROP --}}
    <div
        class="fixed inset-0 bg-black/50"
        @click="closePrivacyModal"
    ></div>


    {{-- MODAL CONTAINER --}}
    <div class="fixed inset-0 flex items-center justify-center p-4">

        <div
            x-show="privacyModal"
            x-transition
            class="w-full max-w-2xl"
        >

            <div class="relative bg-white rounded-2xl shadow-2xl overflow-hidden">

                {{-- HEADER --}}
                <div class="px-6 py-4 border-b flex justify-between items-center">
                    <h2 class="text-xl font-semibold">
                        Privacy Protection Policy
                    </h2>

                    <button
                        type="button"
                        @click="closePrivacyModal"
                        class="text-gray-500 hover:text-gray-700"
                    >
                        ✕
                    </button>
                </div>


                {{-- BODY --}}
                <div class="px-6 py-6 max-h-[500px] overflow-y-auto text-sm text-gray-700 leading-relaxed">

                    {{-- Rendered Privacy HTML --}}
                    <div>
                        {!! $dataPrivacyRender ?? '' !!}
                    </div>

                </div>


                {{-- FOOTER --}}
                <div class="px-6 py-4 border-t flex justify-end gap-3">

                    <button
                        type="button"
                        @click="closePrivacyModal"
                        class="px-4 py-2 border rounded-md text-sm"
                    >
                        Cancel
                    </button>

                    <button
                        type="button"
                        @click="agreePrivacy"
                        class="px-6 py-2 bg-primary text-white rounded-md text-sm"
                    >
                        Agree
                    </button>

                </div>

            </div>

        </div>
    </div>
</div>
