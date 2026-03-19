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

            <div class="mb-3">
                <label class="form-label fw-bold">Date Selection Mode</label>

                <div class="form-check">
                    <input class="form-check-input" type="radio" name="date_mode" value="range" checked>
                    <label class="form-check-label">Date Range</label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="radio" name="date_mode" value="multiple">
                    <label class="form-check-label">Multiple Dates</label>
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

                <div id="multipleDatesContainer" class="mb-2"></div>

                <button type="button" class="btn btn-sm btn-outline-primary" id="addDateBtn">
                    + Add Date
                </button>

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
        const productSelect  = document.querySelector('select[name="product_ids[]"]');
        const addBlockBtn    = document.getElementById('addBlock');
        const deleteBlockBtn = document.getElementById('deleteEntireBlock');
        const dateInput      = document.getElementById('blockDates');
        const allDayCheckbox = document.getElementById('allday');
        const timeSlots      = document.querySelectorAll('.time-slot');
        const editBlockBtn    = document.getElementById('editBlock');

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

        $('#product').select2({
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
                if (info.event.start && info.event.end) {

                    const start = info.event.start;
                    const end = new Date(info.event.end);

                    end.setDate(end.getDate() - 1);

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

                categorySelect.disabled = value !== 'category';
                productSelect.disabled  = value !== 'product';

                if (value === 'product') {
                    $('#category').val(null).trigger('change');
                }

                if (value === 'category') {
                    $('#product').val(null).trigger('change');
                }

                if (value === 'all') {
                    $('#category').val(null).trigger('change');
                    $('#product').val(null).trigger('change');
                }

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

        editBlockBtn.addEventListener('click', () => {

            const props = selectedEvent.extendedProps;

            window.editingGroupId = props.group_id;

            // clear multiple dates
            document.getElementById('editMultipleDates').innerHTML = '';

            // clear range
            editRangePicker.setDates([]);

            $('#editCategory').val([]).trigger('change');
            $('#editProduct').val([]).trigger('change');

            // Scope
            // $(`input[name="edit_scope"][value="${props.scope}"]`).prop('checked', true).trigger('change');

            const editScopeRadios = document.querySelectorAll('input[name="edit_scope"]');
            const editCategorySelect = document.querySelector('select[name="editCategory_ids[]"]');
            const editProductSelect  = document.querySelector('select[name="editProduct_ids[]"]');

            editScopeRadios.forEach(radio => {
                radio.addEventListener('change', () => {
                    const value = radio.value;

                    editCategorySelect.disabled = value !== 'category';
                    editProductSelect.disabled  = value !== 'product';

                    if (value === 'product') {
                        $('#editCategory').val([]).trigger('change');
                    }

                    if (value === 'category') {
                        $('#editProduct').val([]).trigger('change');
                    }

                    if (value === 'all') {
                        $('#editCategory').val([]).trigger('change');
                        $('#editProduct').val([]).trigger('change');
                    }

                    if (editCategorySelect.disabled) {
                        editCategorySelect.selectedIndex = -1;
                    }

                    if (editProductSelect.disabled) {
                        editProductSelect.selectedIndex = -1;
                    }
                });
            });


            // Block type
            $('#editBlockType').val(props.block_type).trigger('change');

            // Categories
            if (props.categories?.length) {
                const ids = props.categories.map(c => c.id);
                $('#editCategory').val(ids).trigger('change');
            }

            // Products
            if (props.products?.length) {
                const ids = props.products.map(p => p.id);
                $('#editProduct').val(ids).trigger('change');
            }

            // Dates
            const start = selectedEvent.start;
            const end = new Date(selectedEvent.end);
            end.setDate(end.getDate() - 1);

            // Detect range vs single
            if (start.toDateString() === end.toDateString()) {
                $('input[name="edit_date_mode"][value="multiple"]').prop('checked', true);
                $('#editRangeWrapper').hide();
                $('#editMultipleWrapper').show();
                addEditDate(start);
            } else {
                $('input[name="edit_date_mode"][value="range"]').prop('checked', true);
                $('#editRangeWrapper').show();
                $('#editMultipleWrapper').hide();
                editRangePicker.setDates([start, end]);
            }

            // Time
            if (!props.is_all_day) {
                $('#editTimes').val([props.start_time.slice(0,5)]).trigger('change');
            } else {
                $('#editTimes').val([]).trigger('change');
            }

            $('#blockModal').modal('hide');
            $('#editBlockModal').modal('show');
        });

        document.getElementById('saveEditBlock').addEventListener('click', async () => {

            const payload = {
                scope: $('input[name="edit_scope"]:checked').val(),
                block_type: $('#editBlockType').val(),
                category_ids: $('#editCategory').val(),
                product_ids: $('#editProduct').val(),
                dates: getEditDates(),
                is_all_day: $('#editTimes').val().length === 0,
                times: buildTimePayload($('#editTimes').val())
            };

            await fetch(`/blocks/group/${window.editingGroupId}`, {
                method: 'PUT',
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

        document.getElementById('deleteBlock').addEventListener('click', async () => {
            if (!selectedEvent) return;

            if (!confirm('This will delete the entire block group. Are you sure?')) return;

            const groupId = selectedEvent.extendedProps.group_id;

            await fetch(`/blocks/${groupId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                },
                body: JSON.stringify({
                    date: clickedDate
                })
            });

            calendar.refetchEvents();
            
            $('#blockModal').modal('hide');
        });

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
    });
</script>
@endsection