<section class="container my-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
        <h5 class="mb-0">Block Dates & Times</h5>
    </div>

    <!-- Form Section -->
    <div class="row g-4 align-items-start">

        <!-- LEFT COLUMN -->
        <div class="col-md-6">

            <!-- Scope -->
            <div class="mb-3">
                <label class="form-label fw-bold">Select Scope</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="scope" id="scopeAll" checked value="all">
                    <label class="form-check-label" for="scopeAll">All Products</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="scope" id="scopeCategory" value="category">
                    <label class="form-check-label" for="scopeCategory">By Category</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="scope" id="scopeProduct" value="product">
                    <label class="form-check-label" for="scopeProduct">By Product</label>
                </div>
            </div>

            <!-- Category -->
            <div class="mb-3">
                <label class="form-label">Select Category</label>
                <select 
                    class=" select2 form-control" 
                    id="category"
                    name="category_ids[]"
                    multiple
                    disabled
                >
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Product -->
            <div class="mb-3">
                <label class="form-label">Select Product</label>
                <select 
                    class=" select2 form-control" 
                    id="product"
                    name="product_ids[]"
                    multiple
                    disabled
                >
                    @foreach ($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Date Picker -->
            <div class="row g-2" id="dateRangeInputs">
                <div class="col">
                    <label class="form-label fw-bold">Start Date</label>
                    <input type="text" class="form-control" placeholder="Start date" autocomplete="off">
                </div>

                <div class="col">
                    <label class="form-label fw-bold">End Date</label>
                    <input type="text" class="form-control" placeholder="End date" autocomplete="off">
                </div>
            </div>


        </div>

        <!-- RIGHT COLUMN -->
        <div class="col-md-6">

            <!-- Time Slots -->
            <label class="form-label fw-bold">Select Time Slot(s)</label>

            <div class="border rounded p-3 mb-3" id="times">
                @foreach (range(9, 19) as $hour)
                <div class="form-check">
                    <input id="timeSlot{{ $hour }}" class="form-check-input time-slot" type="checkbox"
                        value="{{ sprintf('%02d:00', $hour) }}">
                    {{-- show AM and PM in label --}}
                    <label for="timeSlot{{ $hour }}" class="form-check-label">
                        {{ sprintf('%02d:00 %s', ($hour > 12 ? $hour - 12 : $hour), ($hour >= 12 ? 'PM' : 'AM')) }}
                    </label>
                </div>
                @endforeach

                <hr>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="allday">
                    <label for="allday" class="form-check-label fw-bold">Block whole day</label>
                </div>

                <hr>

                <!-- Block Type -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Block Type</label>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="block_type" id="blockBoth" value="both"
                            checked>
                        <label class="form-check-label" for="blockBoth">
                            Disable Delivery & Pickup
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="block_type" id="blockDelivery"
                            value="delivery">
                        <label class="form-check-label" for="blockDelivery">
                            Disable Delivery Only
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="block_type" id="blockPickup"
                            value="pickup">
                        <label class="form-check-label" for="blockPickup">
                            Disable Pickup Only
                        </label>
                    </div>
                </div>

            </div>

            <button type="button" class="btn btn-dark px-4" id="addBlock">
                Add Block
            </button>


        </div>
    </div>

    <!-- CALENDAR -->
    <div class="mt-5">
        <h6 class="fw-bold mb-3">Blocked Schedules</h6>
        <div class="border rounded p-2">
            <div id="calendar" style="min-height: 500px;"></div>
        </div>
    </div>

</section>

@section('customjs')
<script>
    document.addEventListener('DOMContentLoaded', function () {

        /* ---------------------------------------------------
        * 1. ELEMENT REFERENCES
        * --------------------------------------------------- */
        const scopeRadios    = document.querySelectorAll('input[name="scope"]');
        const categorySelect = document.querySelector('select[name="category_ids[]"]');
        const productSelect  = document.querySelector('select[name="product_ids[]"]');
        const addBlockBtn    = document.getElementById('addBlock');
        const dateInput      = document.getElementById('blockDates');
        const allDayCheckbox = document.getElementById('allday');
        const timeSlots      = document.querySelectorAll('.time-slot');

        const categoryMap = @json($categories->pluck('name', 'id'));
        const productMap  = @json($products->pluck('name', 'id'));

        $('#category').select2({
            placeholder: "Select category",
            width: '100%'
        });

        $('#product').select2({
            placeholder: "Select product",
            width: '100%'
        });

        /* ---------------------------------------------------
        * 2. DATEPICKER INITIALIZATION
        * --------------------------------------------------- */
        const datepicker = new DateRangePicker(
            document.getElementById('dateRangeInputs'),
            {
                format: 'yyyy-mm-dd',
                autohide: false,
                todayHighlight: false,
                clearBtn: true,
                allowOneSidedRange: true
            }
        );


        /* ---------------------------------------------------
        * 3. FULLCALENDAR INITIALIZATION
        * --------------------------------------------------- */
        const calendarEl = document.getElementById('calendar');

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            slotMinTime: '07:00:00',
            dayMaxEvents: 4,
            slotMaxTime: '20:00:00',
            allDaySlot: true,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            eventColor: '#e5e5e5',
            eventTextColor: '#000',
            eventBorderColor: '#000',
            events: '/blocks/events',
        });
        
        calendar.render();

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            calendar.updateSize();
        });

        /* ---------------------------------------------------
        * 4. SCOPE LOGIC (ENABLE / DISABLE)
        * --------------------------------------------------- */
        scopeRadios.forEach(radio => {
            radio.addEventListener('change', () => {
                const value = radio.value;

                categorySelect.disabled = value !== 'category';
                productSelect.disabled  = value !== 'product';

                if (categorySelect.disabled) {
                    categorySelect.selectedIndex = -1;
                }

                if (productSelect.disabled) {
                    productSelect.selectedIndex = -1;
                }
            });
        });

        /* ---------------------------------------------------
        * 5. ALL DAY TOGGLE
        * --------------------------------------------------- */
        allDayCheckbox.addEventListener('change', () => {
            document.querySelectorAll('.time-slot').forEach(cb => {
                cb.disabled = allDayCheckbox.checked;
                if (allDayCheckbox.checked) cb.checked = false;
            });
        });

        /* ---------------------------------------------------
        * 6. ADD BLOCK → CALENDAR
        * --------------------------------------------------- */
        addBlockBtn.addEventListener('click', async () => {

            const ranges = datepicker.getDates();

            const startDate = ranges[0];
            const endDate   = ranges[1];

            if (!startDate || !endDate) {
                alert('Please select start and end dates');
                return;
            }

            const dates = expandDateRange(startDate, endDate);
            const isAllDay = allDayCheckbox.checked;

            let times = [];

            if (!isAllDay) {
                document.querySelectorAll('.time-slot:checked').forEach(cb => {
                    times.push({
                        start: cb.value,
                        end: addOneHour(cb.value)
                    });
                });

                if (!times.length) {
                    alert('Select at least one time slot.');
                    return;
                }
            }

            const blockType = document.querySelector(
                'input[name="block_type"]:checked'
                ).value;

            const categoryIds = Array.from(categorySelect.selectedOptions)
                .map(opt => opt.value);

            const productIds = Array.from(productSelect.selectedOptions)
                .map(opt => opt.value);

            const payload = {
                scope: document.querySelector('input[name="scope"]:checked').value,
                block_type: blockType,
                category_ids: categoryIds.length ? categoryIds : null,
                product_ids: productIds.length ? productIds : null,
                dates,
                is_all_day: isAllDay,
                times
            };

            const res = await fetch('{{ route('blocks.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                },
                body: JSON.stringify(payload)
            });

            if (!res.ok) {
                alert('Failed to save blocks');
                return;
            }

            document
                .querySelectorAll('#dateRangeInputs input')
                .forEach(input => input.value = '');

            calendar.refetchEvents();
            resetForm();
        });
        

        /* ---------------------------------------------------
        * 7. HELPERS
        * --------------------------------------------------- */
        function addOneHour(time) {
            const [h, m] = time.split(':').map(Number);
            const newH = (h + 1) % 24;
            return String(newH).padStart(2,'0') + ':' + String(m).padStart(2,'0');
        }

        function getScopeLabel() {
            const selected = document.querySelector('input[name="scope"]:checked').id;
            if (selected === 'scopeProduct') return 'PRODUCT BLOCKED';
            if (selected === 'scopeCategory') return 'CATEGORY BLOCKED';
            return 'ALL PRODUCTS BLOCKED';
        }

        function resetForm() {
            datepicker.setDates([null, null]);
            timeSlots.forEach(cb => { cb.checked = false; cb.disabled = false; });
            allDayCheckbox.checked = false;
        }

        function formatDateLocal(date) {
            const y = date.getFullYear();
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const d = String(date.getDate()).padStart(2, '0');
            return `${y}-${m}-${d}`;
        }

        function formatTimeDisplay(time) {
            const [h, m] = time.split(':');
            const hour = ((h % 12) || 12);
            const ampm = h < 12 ? 'AM' : 'PM';
            return `${hour}:${m} ${ampm}`;
        }

        function expandDateRange(start, end) {
            const dates = [];
            let current = new Date(start);

            while (current <= end) {
                dates.push(formatDateLocal(current));
                current.setDate(current.getDate() + 1);
            }

            return dates;
        }

        let selectedEvent = null;

        calendar.on('eventClick', function(info) {

            selectedEvent = info.event;

            const props = info.event.extendedProps;

            // Scope
            document.getElementById('modalScope').innerText =
                props.scope.toUpperCase();

            document.getElementById('modalBlockType').innerText =
                formatBlockType(props.block_type);

            // MULTIPLE CATEGORIES
            const categoryEl = document.getElementById('modalCategory');
            if (props.categories && props.categories.length) {
                categoryEl.innerHTML = props.categories
                    .map(c => `<span class="badge badge-pill badge-light border mr-1">${c.name}</span>`)
                    .join('');
            } else {
                categoryEl.innerText = '—';
            }

            // MULTIPLE PRODUCTS
            const productEl = document.getElementById('modalProduct');
            if (props.products && props.products.length) {
                productEl.innerHTML = props.products
                    .map(p => `<span class="badge badge-secondary mr-1">${p.name}</span>`)
                    .join('');
            } else {
                productEl.innerText = '—';
            }

            let type = '';
            
            if (props.block_type) {

                if (props.block_type === 'delivery') type = 'DELIVERY';
                else if (props.block_type === 'pickup') type = 'PICKUP';
                else type = props.block_type.toUpperCase();
            }

            document.getElementById('modalBlockType').innerText =
                type + ' BLOCK';

            // Date (range-aware)
            // format date to Feb 05, 2026
            if (info.event.start !== info.event.end) {
                document.getElementById('modalDate').innerText = formatDateLocal(info.event.start) + ' - ' + formatDateLocal(info.event.end);
            } else {
                document.getElementById('modalDate').innerText =
                    formatDateLocal(info.event.start);
            }

            // Time
            document.getElementById('modalTime').innerText =
                props.is_all_day
                    ? 'Whole day'
                    : formatTimeDisplay(props.start_time) + ' - ' + formatTimeDisplay(props.end_time);

            $('#blockModal').modal('show');
        });

        function formatBlockType(type) {
            switch (type) {
                case 'both': return 'Delivery & Pickup Disabled';
                case 'delivery': return 'Delivery Disabled';
                case 'pickup': return 'Pickup Disabled';
                default: return type;
            }
        }

        document.getElementById('deleteBlock').addEventListener('click', async () => {
            if (!selectedEvent) return;

            await fetch(`/blocks/${selectedEvent.id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                }
            });

            calendar.refetchEvents();
            
            $('#blockModal').modal('hide');
        });
    });
</script>
@endsection