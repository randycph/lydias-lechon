<div class="bg-tertiary py-12 px-6 text-white" 
    x-data="{
        newsltter: '',
        isSubmitting: false,
        message: '',
        errorMessage: '',
        async subscribe() {
            if (this.newsltter === '') {
                this.message = '';
                this.errorMessage = 'Please enter your email address.';
            }

            this.isSubmitting = true;

            try {
                const response = await fetch('{{ route('mailing-list.front.subscribe') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        email: this.newsltter
                    })
                });

                const result = await response.json();

                if (response.ok) {
                    this.errorMessage = '';
                    this.message = 'Thank you for subscribing!';
                    this.newsltter = '';
                } else {
                    this.message = '';
                    this.errorMessage = result.errors?.email?.[0] || result.message || 'An error occurred. Please try again.';
                }

            } catch (error) {
                this.message = '';
                this.errorMessage = 'An error occurred. Please try again.';
            }

            this.isSubmitting = false;
        }
    }">
    <div class="container">
        <div class="flex flex-col lg:flex-row items-start justify-between">
            <div>
                <h2 class="text-4xl font-cubao font-medium text-left">Join our Newsletter</h2>
                <div class="mt-4 text-left w-full max-w-lg">Join our subscriber's list to get the latest updates and articles delivered straight to your inbox.</div>
            </div>
            <div class="mt-4 text-black">
                <label for="newsltter" class="sr-only">Label</label>
                <form class="relative flex rounded-lg" @submit.prevent="subscribe">
                    <input required x-model="newsltter" placeholder="Enter your email" type="text" id="newsltter" name="newsltter" class="py-2.5 sm:py-3 px-4 ps-11 block w-full rounded-s-lg sm:text-sm focus:z-10 border-none focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none lg:w-[300px] focus:outline-none focus:ring-2 focus:ring-secondary placeholder:text-gray-400 text-base" />
                    <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4 text-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="shrink-0 text-primary dark:text-neutral-500 size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                        </svg>
                        <span class="ps-4"></span>
                    </div>
                    <button type="submit" :disabled="isSubmitting" class="py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-e-md border border-transparent bg-primary text-white hover:bg-primary-dark focus:outline-hidden focus:bg-primary-dark disabled:opacity-50 disabled:pointer-events-none">
                        <template x-if="isSubmitting">
                            <span>Loading...</span>
                        </template>
                        <template x-if="!isSubmitting">
                            <span>Subscribe</span>
                        </template>
                    </button>
                </form>
                <template x-if="errorMessage">
                    <p class="text-sm text-white mt-2" x-text="errorMessage"></p>
                </template>
                <template x-if="message">
                    <p class="text-sm text-white mt-2" x-text="message"></p>
                </template>
            </div>
        </div>
    </div>
</div>
