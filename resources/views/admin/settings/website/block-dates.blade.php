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
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="scope" id="scopeLocation" value="location">
                    <label class="form-check-label" for="scopeLocation">By Location</label>
                </div>
            </div>

            <!-- Category -->
            <div class="mb-3">
                <label class="form-label">Select Location</label>
                <select 
                    class="select2 form-control" 
                    id="location"
                    name="location_ids[]"
                    multiple
                    disabled
                >
                    @foreach ($locations as $location)
                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                    @endforeach
                </select>
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

            <div class="mb-3">
                <label class="form-label fw-bold">Date Selection Mode</label>

                <div class="form-check">
                    <input class="form-check-input" type="radio" name="date_mode" value="range" checked id="dateModeRange">
                    <label class="form-check-label" for="dateModeRange">Date Range</label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="radio" name="date_mode" value="multiple" id="dateModeMultiple">
                    <label class="form-check-label" for="dateModeMultiple">Multiple Dates</label>
                </div>
            </div>

            <!-- Date Picker -->
            <div id="rangeWrapper">
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

            <div id="multipleWrapper" style="display:none;">

                <label class="form-label fw-bold">Select Dates</label>

                <div id="multipleDatesContainer" class=""></div>

                <button type="button" class="btn btn-sm btn-outline-primary mb-3" id="addDateBtn">
                    + Add Date
                </button>
            </div>

            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="allow-combo">
                    <label for="allow-combo" class="form-check-label fw-bold">Allow Combo (Override Blocking)</label>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Select Products</label>
                <select 
                    class=" select2 form-control" 
                    id="combo-product"
                    name="combo_products[]"
                    multiple
                    disabled
                >
                    @foreach ($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            <small class="form-text text-muted">
                Example: “Whole Lechon (Small)” is blocked on a specific date, but “Pancit Canton” is added under Allow Combo, customers can still place an order if their selection includes Pancit Canton.
            </small>

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

            <button type="button" class="btn btn-danger px-4" id="deleteEntireBlock">
                Delete Entire Month
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
        const locationSelect = document.querySelector('select[name="location_ids[]"]');
        const productSelect  = document.querySelector('select[name="product_ids[]"]');
        const addBlockBtn    = document.getElementById('addBlock');
        const deleteBlockBtn = document.getElementById('deleteEntireBlock');
        const dateInput      = document.getElementById('blockDates');
        const allDayCheckbox = document.getElementById('allday');
        const timeSlots      = document.querySelectorAll('.time-slot');
        const editBlockBtn    = document.getElementById('editBlock');
        const allowComboCheck = document.getElementById('allow-combo');
        const commonProductInput = document.getElementById('combo-product');

        allDayCheckbox.checked = true;
        timeSlots.forEach(cb => {
            cb.disabled = allDayCheckbox.checked;
            if (allDayCheckbox.checked) cb.checked = false;
        });


        let selectedEvent = null;
        let clickedDate = null;
        let dates = [];

        const isEditing = !!window.editingGroupId;

        const categoryMap = @json($categories->pluck('name', 'id'));
        const productMap  = @json($products->pluck('name', 'id'));

        $('#category').select2({
            placeholder: "Select category",
            width: '100%'
        });

        $('#location').select2({
            placeholder: "Select location",
            width: '100%'
        });

        $('#product').select2({
            placeholder: "Select product",
            width: '100%'
        });

        $('#combo-product').select2({
            placeholder: "Select product",
            width: '100%'
        });

        /* ---------------------------------------------------
        * 2. DATEPICKER INITIALIZATION
        * --------------------------------------------------- */
        let rangePicker = new DateRangePicker(
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
            eventClick: function(info) {
                selectedEvent = info.event;

                const x = info.jsEvent.clientX;
                const y = info.jsEvent.clientY;

                document.querySelectorAll('[data-date]').forEach(cell => {
                    const rect = cell.getBoundingClientRect();

                    if (
                        x >= rect.left &&
                        x <= rect.right &&
                        y >= rect.top &&
                        y <= rect.bottom
                    ) {
                        clickedDate = cell.getAttribute('data-date');
                    }
                });

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

                // MULTIPLE LOCATIONS
                const locationEl = document.getElementById('modalLocation');
                if (props.locations && props.locations.length) {
                    locationEl.innerHTML = props.locations
                        .map(l => `<span class="badge badge-info mr-1">${l.name}</span>`)
                        .join('');
                } else {
                    locationEl.innerText = '—';
                }

                let type = '';
                
                if (props.block_type) {

                    if (props.block_type === 'delivery') type = 'DELIVERY';
                    else if (props.block_type === 'pickup') type = 'PICKUP';
                    else type = props.block_type.toUpperCase();
                }
                
                let badgeClass = 'secondary';
                let blockTypeText = '';
                if (type === 'DELIVERY') {
                    badgeClass = 'danger';
                    blockTypeText = 'DELIVERY';
                } else if (type === 'PICKUP') {
                    badgeClass = 'warning';
                    blockTypeText = 'PICKUP';
                } else {
                    badgeClass = 'dark';
                    blockTypeText = 'DELIVERY & PICKUP';
                }

                document.getElementById('modalBlockType').innerHTML =
                    type ? `<span class="badge badge-${badgeClass} mr-1">${blockTypeText}</span>` : '—';

                // Date (range-aware)
                // format date to Feb 05, 2026
                if (info.event.start && info.event.end) {

                    const start = info.event.start;
                    const end = new Date(info.event.end);

                    // check if end has time on it, if not, subtract one day to show correct date range in modal
                    if (end.getHours() === 0 && end.getMinutes() === 0 && end.getSeconds() === 0) {
                        end.setDate(end.getDate() - 1);
                    } else {
                        end.setDate(end.getDate());
                    }

                    if (start.toDateString() !== end.toDateString()) {
                        document.getElementById('modalDate').innerText =
                            formatDateLocal(start) + ' - ' + formatDateLocal(end);
                    } else {
                        document.getElementById('modalDate').innerText =
                            formatDateLocal(start);
                    }
                }

                // Time
                document.getElementById('modalTime').innerText =
                    props.is_all_day
                        ? 'Whole day'
                        : formatTimeDisplay(props.start_time) + ' - ' + formatTimeDisplay(props.end_time);

                $('#blockModal').modal('show');
            }
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

                // reset all
                categorySelect.disabled = true;
                productSelect.disabled  = true;
                locationSelect.disabled = true;

                // clear all
                $('#category').val(null).trigger('change');
                $('#product').val(null).trigger('change');
                $('#location').val(null).trigger('change');

                if (value === 'all') {
                    // everything stays disabled
                    return;
                }

                if (value === 'category') {
                    categorySelect.disabled = false;
                }

                if (value === 'product') {
                    productSelect.disabled = false;
                }

                if (value === 'location') {
                    categorySelect.disabled = false;
                    productSelect.disabled  = false;
                    locationSelect.disabled = false;
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

        const editAlldayCheckbox = document.getElementById('editAllday');
        editAlldayCheckbox.checked = true;
        if (editAlldayCheckbox) {
            editAlldayCheckbox.addEventListener('change', () => {
                document.getElementById('editTimes').disabled = editAlldayCheckbox.checked;
                if (!editAlldayCheckbox.checked) {
                    $('#editTimes').val([]).trigger('change');
                }
            });
        }

        /* ---------------------------------------------------
        * 6. ADD BLOCK → CALENDAR
        * --------------------------------------------------- */
        addBlockBtn.addEventListener('click', async () => {

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

            const mode = document.querySelector('input[name="date_mode"]:checked').value;

            let ranges = [];

            if (mode === 'range') {

                ranges = rangePicker.getDates();

                if (!ranges[0] || !ranges[1]) {
                    alert('Select start and end date');
                    return;
                }

                dates = expandDateRange(ranges[0], ranges[1]);

            } else {

                document.querySelectorAll('.single-date').forEach(input => {
                    if (input.value) {
                        dates.push(input.value);
                    }
                });

                if (!dates.length) {
                    alert('Select at least one date');
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

            const locationIds = Array.from(locationSelect.selectedOptions)
                .map(opt => opt.value);

            const comboProductIds = Array.from(commonProductInput.selectedOptions)
                .map(opt => opt.value);

            const payload = {
                scope: document.querySelector('input[name="scope"]:checked').value,
                block_type: blockType,
                category_ids: categoryIds.length ? categoryIds : null,
                product_ids: productIds.length ? productIds : null,
                dates,
                is_all_day: isAllDay,
                times,
                combo_product_ids: comboProductIds.length ? comboProductIds : null,
                location_ids: locationIds.length ? locationIds : null,
                date_mode: mode
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
            if (selected === 'scopeLocation') return 'LOCATION BLOCKED';
            return 'ALL PRODUCTS BLOCKED';
        }

        function resetForm() {
            rangePicker.setDates([null, null]);
            timeSlots.forEach(cb => {
                cb.disabled = allDayCheckbox.checked;
                if (allDayCheckbox.checked) cb.checked = false;
            });
            allDayCheckbox.checked = true;
            dates = [];
            times = [];
        }

        function formatDateLocal(date) {
            return date.getFullYear() + '-' +
                String(date.getMonth() + 1).padStart(2, '0') + '-' +
                String(date.getDate()).padStart(2, '0');
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

        calendar.on('eventClick', function(info) {
            selectedEvent = info.event;
        });

        function formatBlockType(type) {
            switch (type) {
                case 'both': return 'Delivery & Pickup Disabled';
                case 'delivery': return 'Delivery Disabled';
                case 'pickup': return 'Pickup Disabled';
                default: return type;
            }
        }

        $('#editCategory').select2({ placeholder: 'Select category', width: '100%' });
        $('#editProduct').select2({ placeholder: 'Select product', width: '100%' });
        $('#editTimes').select2({ placeholder: 'Select time slot', width: '100%' });
        $('#editLocation').select2({ placeholder: 'Select location', width: '100%' });

        const editRangePicker = new DateRangePicker(
            document.getElementById('editDateRangeInputs'),
            {
                format: 'yyyy-mm-dd',
                autohide: false
            }
        );

        function addEditDate(date = null) {

            const container = document.getElementById('editMultipleDates');

            const wrapper = document.createElement('div');
            wrapper.className = 'd-flex gap-2 mb-2';

            const input = document.createElement('input');
            input.type = 'text';
            input.className = 'form-control edit-single-date';
            input.placeholder = 'Select date';

            // dont show remove button for first date if it's the only one left
            const removeBtn = document.createElement('button');
            removeBtn.className = 'btn btn-danger btn-sm';
            removeBtn.innerText = '✕';
            removeBtn.type = 'button';

            if (container.children.length === 0) {
                removeBtn.style.visibility = 'hidden';
            }

            removeBtn.onclick = () => wrapper.remove();

            wrapper.appendChild(input);
            wrapper.appendChild(removeBtn);
            container.appendChild(wrapper);

            const picker = new Datepicker(input, {
                format: 'yyyy-mm-dd',
                autohide: true
            });

            // set value if editing existing
            if (date) {
                picker.setDate(date);
            }
        }

        function getEditDates() {

            const mode = document.querySelector('input[name="edit_date_mode"]:checked').value;

            let dates = [];

            if (mode === 'range') {

                const ranges = editRangePicker.getDates();

                if (!ranges[0] || !ranges[1]) {
                    alert('Select start and end date');
                    return [];
                }

                dates = expandDateRange(ranges[0], ranges[1]);

            } else {

                document.querySelectorAll('.edit-single-date').forEach(input => {
                    if (input.value) {
                        dates.push(input.value);
                    }
                });

                if (!dates.length) {
                    alert('Select at least one date');
                }
            }

            return [...new Set(dates)].sort();
        }

        document.getElementById('editAddDate').addEventListener('click', () => {
            addEditDate();
        });
        
        document.querySelectorAll('input[name="edit_date_mode"]').forEach(radio => {
            radio.addEventListener('change', () => {

                if (radio.value === 'range' && radio.checked) {
                    document.getElementById('editRangeWrapper').style.display = '';
                    document.getElementById('editMultipleWrapper').style.display = 'none';
                }

                if (radio.value === 'multiple' && radio.checked) {
                    document.getElementById('editRangeWrapper').style.display = 'none';
                    document.getElementById('editMultipleWrapper').style.display = '';
                }
            });
        });

        document.getElementById('saveEditBlock').addEventListener('click', async () => {

            const comboIds = $('#editComboProduct').val();

            const payload = {
                scope: $('input[name="edit_scope"]:checked').val(),
                block_type: $('#editBlockType').val(),
                category_ids: $('#editCategory').val(),
                product_ids: $('#editProduct').val(),
                location_ids: $('#editLocation').val(),
                dates: getEditDates(),
                is_all_day: $('#editTimes').val().length === 0,
                times: buildTimePayload($('#editTimes').val()),
                combo_product_ids: comboIds && comboIds.length ? comboIds : null,
                date_mode: $('input[name="edit_date_mode"]:checked').val()
            };

            await fetch(`/blocks/update/${window.editingGroupId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                },
                body: JSON.stringify(payload)
            });

            $('#editBlockModal').modal('hide');
            calendar.refetchEvents();
        });

        deleteBlockBtn.addEventListener('click', async () => {
            if (!confirm('This will delete all blocks for the selected month. Are you sure?')) return;

            const currentDate = calendar.getDate();

            const year = currentDate.getFullYear();
            const month = String(currentDate.getMonth() + 1).padStart(2, '0');

            const formattedMonth = `${year}-${month}`;

            await fetch("{{ route('blocks.destroy-month') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                },
                body: JSON.stringify({
                    month: formattedMonth
                })
            });

            calendar.refetchEvents();
            
            $('#blockModal').modal('hide');
        });

        // document.getElementById('deleteGroup').addEventListener('click', async () => {
        //     if (!selectedEvent) return;

        //     if (!confirm('This will delete the entire block group. Are you sure?')) return;

        //     const groupId = selectedEvent.extendedProps.group_id;

        //     await fetch(`/blocks/${groupId}`, {
        //         method: 'POST',
        //         headers: {
        //             'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
        //         }
        //     });

        //     calendar.refetchEvents();
        //     $('#blockModal').modal('hide');
        // });

        const rangeWrapper = document.getElementById('rangeWrapper');
        const multipleWrapper = document.getElementById('multipleWrapper');

        document.querySelectorAll('input[name="date_mode"]').forEach(radio => {
            radio.addEventListener('change', () => {

                if (radio.value === 'range' && radio.checked) {
                    rangeWrapper.style.display = '';
                    multipleWrapper.style.display = 'none';
                }

                if (radio.value === 'multiple' && radio.checked) {
                    rangeWrapper.style.display = 'none';
                    multipleWrapper.style.display = '';
                }

            });
        });

        const container = document.getElementById('multipleDatesContainer');
        const addBtn = document.getElementById('addDateBtn');

        let pickers = [];

        function addDatePicker(value = '') {

            const wrapper = document.createElement('div');
            wrapper.className = 'd-flex gap-2 mb-2';

            const input = document.createElement('input');
            input.type = 'text';
            input.className = 'form-control single-date';
            input.placeholder = 'Select date';

            const removeBtn = document.createElement('button');
            removeBtn.className = 'btn btn-danger btn-sm';
            removeBtn.innerText = '✕';

            if (container.children.length === 0) {
                removeBtn.style.visibility = 'hidden';
            }

            removeBtn.onclick = () => {
                wrapper.remove();
            };

            wrapper.appendChild(input);
            wrapper.appendChild(removeBtn);
            container.appendChild(wrapper);

            const picker = new Datepicker(input, {
                format: 'yyyy-mm-dd',
                autohide: true
            });

            pickers.push(picker);
        }

        // initial one
        addDatePicker();

        addBtn.addEventListener('click', () => {
            addDatePicker();
        });

        allowComboCheck.addEventListener('change', function() {
            if (this.checked) {
                commonProductInput.disabled = false;
            } else {
                commonProductInput.disabled = true;
                $('#combo-product').val([]).trigger('change');
            }
            
        });

        const editAllowCombo = document.getElementById('editAllowCombo');
        const editComboProduct = document.getElementById('editComboProduct');

        $('#editComboProduct').select2({
            placeholder: "Select product",
            width: '100%'
        });

        editAllowCombo.addEventListener('change', function() {
            if (this.checked) {
                editComboProduct.disabled = false;
            } else {
                editComboProduct.disabled = true;
                $('#editComboProduct').val([]).trigger('change');
            }
        });

        /* ---------------------------------------------------
        * GROUP EDIT LOGIC
        * --------------------------------------------------- */
        $('#editGroupCategory').select2({ placeholder: 'Select category', width: '100%' });
        $('#editGroupProduct').select2({ placeholder: 'Select product', width: '100%' });
        $('#editGroupLocation').select2({ placeholder: 'Select location', width: '100%' });
        $('#editGroupTimes').select2({ placeholder: 'Select time', width: '100%' });
        $('#editGroupComboProduct').select2({ placeholder: 'Select product', width: '100%' });

        // DATE RANGE
        const editGroupRangePicker = new DateRangePicker(
            document.getElementById('editGroupDateRangeInputs'),
            {
                format: 'yyyy-mm-dd',
                autohide: false
            }
        );

        const editGroupAllday = document.getElementById('editGroupAllday');

        editGroupAllday.addEventListener('change', () => {

            const isAllDay = editGroupAllday.checked;
            const timeSelect = document.getElementById('editGroupTimes');

            timeSelect.disabled = isAllDay;

            if (isAllDay) {
                $('#editGroupTimes').val([]).trigger('change');
            }
        });

        const groupScopeRadios = document.querySelectorAll('input[name="group_scope"]');
        const groupCategory = document.getElementById('editGroupCategory');
        const groupProduct  = document.getElementById('editGroupProduct');
        const groupLocation = document.getElementById('editGroupLocation');

        groupScopeRadios.forEach(radio => {
            radio.addEventListener('change', () => {
                const val = radio.value;

                groupCategory.disabled = val !== 'category';
                groupProduct.disabled  = val !== 'product';
                groupLocation.disabled = val !== 'location';

                $('#editGroupCategory').val([]).trigger('change');
                $('#editGroupProduct').val([]).trigger('change');
                $('#editGroupLocation').val([]).trigger('change');

                if (val === 'all') {
                    return;
                }

                if (val === 'category') {
                    groupCategory.disabled = false;
                }

                if (val === 'product') {
                    groupProduct.disabled = false;
                }

                if (val === 'location') {
                    groupCategory.disabled = false;
                    groupProduct.disabled  = false;
                    groupLocation.disabled = false;
                }
            });
        });

        const editGroupAllowCombo = document.getElementById('editGroupAllowCombo');
        const editGroupComboProduct = document.getElementById('editGroupComboProduct');

        editGroupAllowCombo.addEventListener('change', function () {
            editGroupComboProduct.disabled = !this.checked;

            if (!this.checked) {
                $('#editGroupComboProduct').val([]).trigger('change');
            }
        });

        document.querySelectorAll('input[name="edit_group_date_mode"]').forEach(radio => {
            radio.addEventListener('change', () => {

                if (radio.value === 'range' && radio.checked) {
                    document.getElementById('editGroupRangeWrapper').style.display = '';
                    document.getElementById('editGroupMultipleWrapper').style.display = 'none';
                }

                if (radio.value === 'multiple' && radio.checked) {
                    document.getElementById('editGroupRangeWrapper').style.display = 'none';
                    document.getElementById('editGroupMultipleWrapper').style.display = '';
                }
            });
        });

        document.getElementById('editGroupAddDate').addEventListener('click', () => {
            addEditGroupDate();
        });

        const editGroupBtn = document.getElementById('editGroup');

        editGroupBtn.addEventListener('click', async () => {

            const groupId = selectedEvent.extendedProps.group_id;

            const res = await fetch(`/blocks/group/${groupId}`);
            const data = await res.json();

            window.editingGroupId = groupId;

            // RESET
            document.getElementById('editGroupMultipleDates').innerHTML = '';
            editGroupRangePicker.setDates([]);

            // DETECT MODE
            const dates = data.dates;

            const mode = data.date_mode;

            let isContinuous = true;

            for (let i = 1; i < dates.length; i++) {

                const prev = parseLocalDate(dates[i - 1]);
                prev.setDate(prev.getDate() + 1);

                if (formatDateLocal(prev) !== dates[i]) {
                    isContinuous = false;
                    break;
                }
            }

            if (mode === 'range') {

                document.getElementById('editGroupRangeLabel').checked = true;
                document.getElementById('editGroupRangeWrapper').style.display = '';
                document.getElementById('editGroupMultipleWrapper').style.display = 'none';

                editGroupRangePicker.setDates([]);
                editGroupRangePicker.setDates(
                    dates[0],
                    dates[dates.length - 1]
                );

            } else {

                document.getElementById('editGroupMultipleLabel').checked = true;
                document.getElementById('editGroupRangeWrapper').style.display = 'none';
                document.getElementById('editGroupMultipleWrapper').style.display = '';

                dates.forEach(d => addEditGroupDate(d));
            }
            
            // SCOPE
            document.querySelectorAll('input[name="group_scope"]').forEach(r => {
                r.checked = r.value === data.scope;
            });

            document.querySelector('input[name="group_scope"]:checked')
                ?.dispatchEvent(new Event('change'));

            // BLOCK TYPE
            $('#editGroupBlockType').val(data.block_type).trigger('change');

            // RELATIONS
            $('#editGroupCategory').val(data.categories.map(c => c.id)).trigger('change');
            $('#editGroupProduct').val(data.products.map(p => p.id)).trigger('change');
            $('#editGroupLocation').val(data.locations.map(l => l.id)).trigger('change');

            // COMBO
            if (data.combo_products.length) {
                $('#editGroupAllowCombo').prop('checked', true);
                $('#editGroupComboProduct')
                    .prop('disabled', false)
                    .val(data.combo_products.map(p => p.id))
                    .trigger('change');
            } else {
                $('#editGroupAllowCombo').prop('checked', false);
                $('#editGroupComboProduct').prop('disabled', true).val([]).trigger('change');
            }

            // TIME
            if (!data.is_all_day && data.time_slots.length) {
                $('#editGroupTimes')
                    .val(data.time_slots.map(t => t.slice(0,5)))
                    .trigger('change');

                $('#editGroupTimes').prop('disabled', false);
                $('#editGroupAllday').prop('checked', false);

            } else {
                $('#editGroupTimes').val([]).trigger('change');
                $('#editGroupTimes').prop('disabled', true);
                $('#editGroupAllday').prop('checked', true);
            }

            $('#blockModal').modal('hide');
            $('#editGroupModal').modal('show');
        });

        document.getElementById('deleteBlock').addEventListener('click', async () => {
            if (!selectedEvent) return;

            if (!confirm('Delete this specific block?')) return;

            const groupId = selectedEvent.extendedProps.group_id;
            const props = selectedEvent.extendedProps;

            await fetch(`/blocks/${groupId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                },
                body: JSON.stringify({
                    date: clickedDate,
                    start_time: props.is_all_day ? null : props.start_time,
                    end_time: props.is_all_day ? null : props.end_time
                })
            });

            calendar.refetchEvents();
            $('#blockModal').modal('hide');
        });

        document.getElementById('saveEditGroup').addEventListener('click', async () => {

            const dates = getEditGroupDates();

            if (!dates.length) return;

            const isAllDay = document.getElementById('editGroupAllday').checked;

            const payload = {
                scope: document.querySelector('input[name="group_scope"]:checked').value,
                block_type: $('#editGroupBlockType').val(),
                category_ids: $('#editGroupCategory').val(),
                product_ids: $('#editGroupProduct').val(),
                location_ids: $('#editGroupLocation').val(),
                dates: dates,
                is_all_day: isAllDay,
                times: isAllDay ? [] : buildTimePayload($('#editGroupTimes').val()),
                combo_product_ids: $('#editGroupComboProduct').val(),
                date_mode: document.querySelector('input[name="edit_group_date_mode"]:checked').value
            };

            const res = await fetch(`/blocks/update/${window.editingGroupId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                },
                body: JSON.stringify(payload)
            });

            if (!res.ok) {
                alert('Failed to update group');
                return;
            }

            $('#editGroupModal').modal('hide');
            calendar.refetchEvents();
        });

        function parseLocalDate(dateStr) {
            const [y, m, d] = dateStr.split('-').map(Number);
            return new Date(y, m - 1, d);
        }

        function addEditGroupDate(date = null) {

            const container = document.getElementById('editGroupMultipleDates');

            const wrapper = document.createElement('div');
            wrapper.className = 'd-flex gap-2 mb-2';

            const input = document.createElement('input');
            input.type = 'text';
            input.className = 'form-control';
            input.placeholder = 'Select date';

            const removeBtn = document.createElement('button');
            removeBtn.className = 'btn btn-danger btn-sm';
            removeBtn.innerText = '✕';
            removeBtn.type = 'button';

            removeBtn.onclick = () => wrapper.remove();

            wrapper.appendChild(input);
            wrapper.appendChild(removeBtn);
            container.appendChild(wrapper);

            const picker = new Datepicker(input, {
                format: 'yyyy-mm-dd',
                autohide: true
            });

            if (date) picker.setDate(date);
        }

        function buildTimePayload(selectedTimes) {

            if (!selectedTimes || !selectedTimes.length) {
                return [];
            }

            return selectedTimes.map(time => {
                const [h, m] = time.split(':').map(Number);

                const endH = (h + 1) % 24;

                return {
                    start: time,
                    end: String(endH).padStart(2, '0') + ':' + String(m).padStart(2, '0')
                };
            });
        }

        function getEditGroupDates() {

            const mode = document.querySelector('input[name="edit_group_date_mode"]:checked').value;

            let dates = [];

            if (mode === 'range') {

                const ranges = editGroupRangePicker.getDates();

                if (!ranges[0] || !ranges[1]) {
                    alert('Select start and end date');
                    return [];
                }

                dates = expandDateRange(ranges[0], ranges[1]);

            } else {

                document.querySelectorAll('#editGroupMultipleDates input').forEach(input => {
                    if (input.value) dates.push(input.value);
                });

                if (!dates.length) {
                    alert('Select at least one date');
                }
            }

            return [...new Set(dates)].sort();
        }

    });
</script>
@endsection