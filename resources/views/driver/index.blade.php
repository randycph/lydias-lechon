<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>Driver Sales Summary</title>
    <meta name="description" content="@yield('meta_description', 'Lydia\'s Lechon - The Best Lechon in the Philippines')">
    <meta name="keywords" content="@yield('meta_keywords', 'lechon, filipino food, best lechon, lydia\'s lechon')">
    <meta name="author" content="Lydia's Lechon">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#018441">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:title" content="@yield('title', 'Lydia\'s Lechon')">
    <meta property="og:description" content="@yield('meta_description', 'Lydia\'s Lechon - The Best Lechon in the Philippines')">
    <meta property="og:image" content="@yield('image', asset('images/lydia-store-img2.png'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ config('app.name', 'Lydia\'s Lechon') }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'Lydia\'s Lechon')">
    <meta name="twitter:description" content="@yield('meta_description', 'Lydia\'s Lechon - The Best Lechon in the Philippines')">
    <meta name="twitter:image" content="@yield('image', asset('images/lydia-store-img2.png'))">
    <meta name="twitter:site" content="@lydiaslechon">
    <meta name="twitter:creator" content="@lydiaslechon">
    <meta name="twitter:url" content="{{ url()->current() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hurricane&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @font-face {
            font-family: 'Feelings';
            src: url('{{ asset('fonts/Feelings.ttf') }}') format('opentype');
            font-weight: 500;
            font-style: normal;
        }

        [x-cloak] {
            display: none
        }
    </style>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    @yield('alpine.plugins')

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

</head>

<body class="min-h-screen bg-[#F5F6FA] flex justify-center md:items-center p-4">

    <div x-data="deliveries()" class="w-full max-w-sm relative">
        <!-- LIST -->
        <div x-show="page==='list'">
            <header class="mb-4">
                <h2>Hey {{ auth()->check() ? auth()->user()->name : '' }}</h2>
                <p class="mb-2">Good {{ now()->format('H') < 12 ? 'morning' : (now()->format('H') < 18 ? 'afternoon' : 'evening') }}!</p>
                <h1 class="text-2xl font-semibold text-gray-900">Deliveries</h1>
                <p class="text-sm text-gray-500">Assigned to you</p>

                <label class="mt-3 relative block">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                        <!-- search icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                                d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15z" />
                        </svg>
                    </span>
                    <input x-model="q" placeholder="Search name / order #"
                        class="w-full pl-10 pr-4 py-3 rounded-2xl bg-white text-[15px] shadow-sm outline-none ring-1 ring-gray-200 focus:ring-[#FFC83D]/60">
                </label>
            </header>

            <template x-if="loading">
                <div class="text-sm text-gray-500">Loading deliveries…</div>
            </template>

            <div class="space-y-3" x-show="!loading">
                <template x-for="d in filtered" :key="`${d.type}-${d.id}`">
                    <button @click="open(d)"
                        class="w-full text-left rounded-2xl border border-gray-100 bg-white shadow-sm p-3">
                        <div class="flex items-start gap-3">
                            <!-- badge -->
                            <div class="flex">
                                <span style="word-break: break-word;" class=" mt-0.5 inline-flex items-center rounded-full w-16 px-2 py-0.5 text-xs font-medium" :class="badgeClass(d.delivery_status)"
                                x-text="(d.delivery_status == 'Returned/Rejected' ? 'Returned' : (d.delivery_status == 'Delivered/Picked Up' ? 'Delivered' : 'In Transit'))"></span>
                            </div>

                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <div class="font-semibold text-gray-900" x-text="d.customer_name"></div>
                                    <div class="text-[13px] text-gray-500" x-text="formatDate(d.date_needed)"></div>
                                </div>
                                <div class="mt-1 text-[13px] text-gray-600">
                                    <span class="font-medium" x-text="'#' + d.order_number"></span>
                                    · <span x-text="d.delivery_type"></span>
                                </div>
                                <div class="mt-1 text-[13px] text-gray-900 font-medium" x-text="money(d.gross_amount)">
                                </div>
                            </div>

                            <span
                                class="shrink-0 h-8 w-8 rounded-full bg-[#FFC83D] flex items-center justify-center shadow">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-900"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                        d="M9 6l6 6-6 6" />
                                </svg>
                            </span>
                        </div>
                    </button>
                </template>
            </div>
        </div>

        <!-- DETAILS -->
        <div x-show="page==='detail'" x-transition.opacity.duration.150ms class="fixed inset-0 bg-white z-10">
            <div class="max-w-sm mx-auto h-full">

                <!-- Scroll container + collapsing hero -->
                <div class="relative h-full overflow-hidden" x-data="collapsingHero()">
                    <div class="absolute inset-0 overflow-y-auto" x-ref="scroller" @scroll="onScroll">

                        <!-- HERO (height shrinks on scroll) -->
                        <div class="relative w-full" :style="`height:${heroHeight}px`">
                            <img :src="active.getImage()"
                                class="absolute inset-0 w-full h-full object-cover"
                                :style="`transform:scale(${heroScale})`" alt="" />
                            <!-- gradient for text legibility -->
                            <div class="absolute inset-x-0 top-0 h-28 bg-gradient-to-b from-black/35 to-transparent">
                            </div>

                            <!-- top bar -->
                            <div class="absolute inset-x-0 top-0 p-4 flex items-center justify-start text-white">
                                <button @click="page='list'"
                                    class="h-9 w-9 rounded-full bg-black/30 backdrop-blur flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                            d="M15 18l-6-6 6-6" />
                                    </svg>
                                </button>
                                <div class="font-medium ml-2">Deliveries</div>
                                <div class="h-9 w-9"></div>
                            </div>
                        </div>

                        <!-- CONTENT -->
                        <div class="-mt-5 rounded-t-3xl bg-white px-5 pb-6 pt-6 space-y-4">
                            <div>
                                <div class="text-sm text-gray-500" x-text="'Order #' + active.order_number"></div>
                                <h2 class="text-xl font-semibold" x-text="active.customer_name"></h2>
                                <div class="mt-1 text-[13px] text-gray-600">
                                    <span x-text="active.delivery_type"></span> ·
                                    <span x-text="formatDate(active.date_needed)"></span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium"
                                    :class="badgeClass(active.delivery_status)"
                                    x-text="active.delivery_status || '—'"></span>
                                <span class="text-[15px] font-medium" x-text="money(active.gross_amount)"></span>
                            </div>

                            <template x-if="active.delivery_address">
                                <div class="text-[13px] text-gray-700 flex flex-col gap-4">
                                    <div>
                                        <div class="font-medium mb-1">Delivery address</div>
                                        <div x-text="active.delivery_address"></div>
                                    </div>
                                    <div x-show="active.contact_person">Contact Person: <span
                                            x-text="active.contact_person"></span></div>
                                    <div x-show="active.contact_number">Contact Number: <a
                                            :href="'tel:' + active.contact_number" x-text="active.contact_number"
                                            class="underline"></a></div>
                                </div>
                            </template>

                            <div>
                                {{-- make a button that says Update Status middle aligned and awlays stick at the bottom
                                --}}
                                <button class="w-full py-3 bg-[#FFC83D] text-gray-900 font-medium rounded-md shadow"
                                    @click="openSheet()">
                                    Update Status
                                </button>
                            </div>
                        </div>
                        <!-- /CONTENT -->

                        <!-- BACKDROP -->
                        <div x-show="sheetOpen" x-transition.opacity class="fixed inset-0 z-20 bg-black/40"
                            @click="closeSheet()"></div>

                        <!-- BOTTOM SHEET -->
                        <div x-show="sheetOpen" x-trap.noscroll="sheetOpen"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full"
                            class="fixed inset-x-0 bottom-0 z-30" @keydown.escape.prevent.stop="closeSheet()">
                            <div class="mx-auto w-full max-w-sm rounded-t-2xl bg-white shadow-2xl">
                                <!-- Handle -->
                                <div class="flex justify-center pt-3">
                                    <span class="h-1.5 w-12 rounded-full bg-gray-300"></span>
                                </div>

                                <div class="px-5 pb-5 pt-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <h3 class="text-lg font-semibold text-gray-900">Update Status</h3>
                                        <button class="p-2 rounded-full hover:bg-gray-100" @click="closeSheet()">
                                            <svg class="h-5 w-5 text-gray-600" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.7" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Status -->
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                    <select x-model="form.status"
                                        class="w-full rounded-xl border-gray-200 text-[15px] focus:ring-[#FFC83D] focus:border-[#FFC83D]">
                                        <template x-for="s in statuses" :key="s">
                                            <option :value="s" x-text="s"></option>
                                        </template>
                                    </select>

                                    <!-- Remarks -->
                                    <label class="block text-sm font-medium text-gray-700 mt-4 mb-1">Remarks</label>
                                    <textarea x-model="form.remarks" rows="3" placeholder="Add delivery notes…"
                                        class="w-full rounded-xl border-gray-200 text-[15px] focus:ring-[#FFC83D] focus:border-[#FFC83D]"></textarea>

                                    <!-- File upload -->
                                    <div class="mt-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Attachment</label>

                                        <input type="file" class="hidden" x-ref="file" @change="onFileChange">
                                        <div class="flex items-center gap-3">
                                            <button type="button"
                                                class="rounded-xl border border-gray-200 px-3 py-2 text-[14px] shadow-sm hover:bg-gray-50"
                                                @click="$refs.file.click()">
                                                Upload file
                                            </button>
                                            <template x-if="form.fileName">
                                                <div class="text-[13px] text-gray-600 truncate">
                                                    <span class="font-medium" x-text="form.fileName"></span>
                                                    <button class="ml-2 text-gray-500 hover:text-gray-700"
                                                        @click="removeFile()" title="Remove file">×</button>
                                                </div>
                                            </template>
                                        </div>
                                        <p class="mt-1 text-[12px] text-gray-500">JPEG/PNG/PDF up to 10MB.</p>
                                    </div>

                                    <!-- Actions -->
                                    <div class="mt-5 flex gap-3">
                                        <button class="flex-1 py-3 rounded-2xl bg-gray-100 text-gray-800"
                                            @click="closeSheet()">Cancel</button>

                                        <button
                                            class="flex-1 py-3 rounded-2xl bg-[#FFC83D] text-gray-900 font-medium disabled:opacity-60"
                                            :disabled="saving" @click="submitStatus()">
                                            <span x-show="!saving">Save</span>
                                            <span x-show="saving" class="inline-flex items-center gap-2">
                                                <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24">
                                                    <circle cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="3" fill="none"
                                                        class="opacity-25" />
                                                    <path d="M4 12a8 8 0 018-8" stroke="currentColor" stroke-width="3"
                                                        class="opacity-75" fill="none" />
                                                </svg>
                                                Saving…
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- bottom padding so last content isn't hidden behind iOS bars -->
                        <div class="h-6"></div>
                    </div>
                </div>
                <!-- /scroll container -->
            </div>
        </div>

    </div>

    <script>
        function deliveries() {
            return {
                page: 'list',
                loading: true,
                q: '',
                list: [],
                active: {},
                async init() {
                    try {
                        const r = await fetch(`{{ route('driver.deliveries') }}`);
                        const json = await r.json();
                        this.list = json.data || [];
                    } catch (e) {
                        console.error(e);
                    } finally {
                        this.loading = false;
                    }
                },
                get filtered() {
                    const q = this.q.trim().toLowerCase();
                    if (!q) return this.list;
                    return this.list.filter(d =>
                        (d.customer_name || '').toLowerCase().includes(q) ||
                        (d.order_number || '').toLowerCase().includes(q)
                    );
                },
                open(d) {
                    this.active = d;
                    this.page = 'detail';
                },
                money(n) {
                    return `₱${Number(n||0).toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2})}`;
                },
                formatDate(d) {
                    if (!d) return '—';
                    return new Date(d).toLocaleDateString();
                },
                formatDateTime(d) {
                    if (!d) return '—';
                    const dt = new Date(d);
                    return dt.toLocaleString();
                },
                formatAddress(a) {
                    if (!a) return '';
                    const parts = ['address1', 'address2', 'city', 'province', 'postal_code', 'region']
                        .map(k => a[k]).filter(Boolean);
                    return parts.join(', ');
                },
                badgeClass(status) {
                    status = (status || '').toLowerCase();
                    if (status.includes('in transit')) return 'bg-yellow-100 text-yellow-800';
                    if (status.includes('delivered') || status.includes('picked')) return 'bg-green-100 text-green-800';
                    if (status.includes('return') || status.includes('reject')) return 'bg-red-100 text-red-800';
                    return 'bg-gray-100 text-gray-700';
                },
                getImage() {
                    return this.active.product ? this.active.product[0] : null;
                },
                sheetOpen: false,
                saving: false,
                statuses: ['In Transit', 'Delivered/Picked Up', 'Returned/Rejected'],
                form: {
                    status: '',
                    remarks: '',
                    file: null,
                    fileName: ''
                },

                openSheet() {
                    this.form.status = this.active.delivery_status || 'In Transit';
                    this.form.remarks = '';
                    this.form.file = null;
                    this.form.fileName = '';
                    this.sheetOpen = true;
                },

                closeSheet() {
                    this.sheetOpen = false;
                },

                onFileChange(e) {
                    const file = e.target.files[0];
                    if (!file) return;
                    if (file.size > 10 * 1024 * 1024) { // 10MB
                        alert('File too large (max 10MB).');
                        e.target.value = '';
                        return;
                    }
                    this.form.file = file;
                    this.form.fileName = file.name;
                },

                removeFile() {
                    this.form.file = null;
                    this.form.fileName = '';
                    this.$refs.file.value = '';
                },

                async submitStatus() {
                    this.saving = true;
                    try {
                        const fd = new FormData();
                        fd.append('type', this.active.type); // 'sales' | 'job'
                        fd.append('id', this.active.id);
                        fd.append('status', this.form.status);
                        fd.append('remarks', this.form.remarks || '');
                        if (this.form.file) fd.append('attachment', this.form.file);

                        const r = await fetch(`{{ route('sales-transaction.delivery_status') }}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: fd
                        });

                        if (!r.ok) throw new Error('Failed to save');
                        const json = await r.json();

                        // Optimistically update UI
                        this.active.delivery_status = this.form.status;
                        if (Array.isArray(this.active.statuses)) {
                            this.active.statuses.unshift({
                                id: json.status_id ?? Date.now(),
                                status: this.form.status,
                                note: this.form.remarks,
                                created_at: new Date().toISOString()
                            });
                        }

                        this.sheetOpen = false;
                    } catch (e) {
                        console.error(e);
                        alert('Could not save status. Please try again.');
                    } finally {
                        this.saving = false;
                    }
                },
            }
        }
    </script>

    <script>
        function collapsingHero() {
            return {
                // tune these to your taste
                maxH: 280, // initial hero height (px)
                minH: 110, // collapsed height (px)
                st: 0, // scrollTop
                onScroll(e) {
                    this.st = e.target.scrollTop;
                },
                get heroHeight() {
                    // shrink until minH
                    return Math.max(this.minH, this.maxH - this.st);
                },
                get heroScale() {
                    // gentle stretch when pulling down (negative overscroll on iOS)
                    return this.st < 0 ? 1 + Math.min(Math.abs(this.st) / 300, 0.2) : 1;
                }
            }
        }
    </script>


</body>

</html>
