<div>
    <div class="bg-gray-300 w-full h-10 mb-3"></div>
    <div class="flex col px-6 flex-col gap-4">
        <!-- Repeat this for each cart item -->
        <template x-for="i in 2">
            <div class="flex justify-between items-center gap-4 py-2">
                <div class="flex gap-4 items-center w-full">
                    <!-- Image Placeholder -->
                    <div class="bg-gray-300 rounded-md w-20 h-20"></div>

                    <!-- Text & Quantity -->
                    <div class="flex flex-col gap-2 flex-grow">
                        <!-- Product Name -->
                        <div class="bg-gray-300 h-4 w-40 rounded"></div>

                        <!-- Price -->
                        <div class="bg-gray-200 h-3 w-24 rounded"></div>

                        <!-- Quantity Control -->
                        <div class="flex space-x-2 mt-2">
                            <div class="w-8 h-8 bg-gray-300 rounded-md"></div>
                            <div class="w-8 h-8 bg-gray-200 rounded-md"></div>
                            <div class="w-8 h-8 bg-gray-300 rounded-md"></div>
                        </div>
                    </div>
                </div>

                <!-- Trash Icon Placeholder -->
                <div class="w-6 h-6 bg-gray-300 rounded-full"></div>
            </div>
        </template>

        <!-- Shipping Method Buttons -->
        <div class="mt-6 space-y-2">
            <div class="h-4 bg-gray-300 rounded w-40"></div>
            <div class="flex gap-4">
                <div class="w-full h-10 bg-gray-200 rounded-md"></div>
                <div class="w-full h-10 bg-gray-200 rounded-md"></div>
            </div>
        </div>

        <!-- Coupon Input -->
        <div class="bg-gray-100 rounded-md mt-6 p-4">
            <div class="flex gap-2">
                <div class="w-full h-10 bg-gray-200 rounded-md"></div>
                <div class="w-24 h-10 bg-gray-300 rounded-md"></div>
            </div>
        </div>

        <!-- Subtotal -->
        <div class="mt-4">
            <div class="h-4 bg-gray-300 w-32 rounded mb-2"></div>
            <div class="h-3 bg-gray-200 w-48 rounded"></div>
        </div>

        <!-- Checkout Button -->
        <div class="w-full mt-6">
            <div class="h-12 bg-gray-300 rounded-md"></div>
        </div>
    </div>
</div>