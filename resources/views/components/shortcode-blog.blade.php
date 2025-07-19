<div class="container">
    <div class="px-4"
            x-data="{
            page: 1,
            loaded: false,
            hasMore: true,
            async init() {
                if (this.loaded) return; // 🛡️ prevent double run
                this.loaded = true;
                console.log('[Alpine] init triggered once');
                await this.loadArticles();
            },
            async loadArticles() {
                await this.fetchArticles();
            },
            async loadMore() {
                this.page++;
                this.loading = true;
                await this.fetchArticles();
                this.loading = false;
            },
            async fetchArticles() {
                try {
                    const response = await fetch(`{{ route('articles.load-more') }}?page=${this.page}`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (!response.ok) throw new Error('Network response was not ok');

                    const data = await response.json();

                    document.getElementById('blogs').insertAdjacentHTML('beforeend', data.html);
                    this.hasMore = data.hasMore;
                } catch (error) {
                    console.error('Fetch error:', error);
                }
            }
        }"
        x-init="$nextTick(() => init())"
    >

        <div id="blogs" class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
            
        </div>
        <div class="flex justify-center mt-6" x-show="hasMore">
            <button
                @click="loadMore"
                :disabled="loading"
                class="custom-btn btn-primary border-primary border text-base lg:text-lg text-primary px-6 py-3 rounded-md flex items-center gap-2"
            >
                <svg x-show="loading" class="animate-spin h-5 w-5 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
                <span x-show="!loading">Load More</span>
                <span x-show="loading">Loading...</span>
            </button>
        </div>
    </div>
</div>