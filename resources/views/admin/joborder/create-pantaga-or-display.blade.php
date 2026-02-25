@extends('admin.layouts.app')

@section('pagetitle')
    Job Order
@endsection

@section('pagecss')
    <link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/prismjs/themes/prism-vs.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/datextime/daterangepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/flatpickr/flatpickr.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/lydias-admin.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/bootstrap-tagsinput/bootstrap-tagsinput.css') }}" rel="stylesheet">

    <link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">

    <style>
        .bootstrap-tagsinput .tag {
            background-color : rgb(255, 255, 255, 0.5);
            color : black;
        }
    </style>
@endsection

@section('content')

<div class="container pd-x-0">
    <div class="d-sm-flex justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                    <li class="breadcrumb-item" aria-current="page">Portal</li>
                    <li class="breadcrumb-item active" aria-current="page">Job Orders</li>
                </ol>
            </nav>
            <h4 class="mg-b-0 tx-spacing--1">Create Pantaga/Display</h4>
        </div>
    </div>
    <form autocomplete="off" action="{{ route('joborders.pantage-or-display-store') }}" method="post">
        @csrf
        <div class="row row-sm">
            <div class="col-lg-6">
                <form>
                    <div class="form-row">
                        <div class="col-md-8">
                            {{-- <div class="form-group">
                                <label class="d-block">Select Product <span class="tx-danger">*</span></label>
                                <select class="selectpicker mg-b-5" data-style="btn btn-outline-light btn-md btn-block tx-left" title="Choose product" data-width="100%" name="product_id">
                                    @foreach($products as $prod)
                                        <option value="{{$prod->id}}">{{$prod->name}} ({{$prod->weight}})</option>
                                    @endforeach
                                </select>
                            </div> --}}
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="d-block">Quantity <span class="tx-danger">*</span></label>
                                <input type="number" name="qty" value="1.00" step="0.01" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="d-block">Category <span class="tx-danger">*</span></label>
                        <select class="selectpicker mg-b-5" data-style="btn btn-outline-light btn-md btn-block tx-left" title="Choose category" data-width="100%" name="jo_category">
                            <option value="Belly Pantaga" {{ old('jo_category') == 'Belly Pantaga' ? 'selected' : '' }}>Belly Pantaga</option>
                            <option value="Pantaga" {{ old('jo_category') == 'Pantaga' ? 'selected' : '' }}>Pantaga</option>
                            <option value="Display" {{ old('jo_category') == 'Display' ? 'selected' : '' }}>Display</option>
                            <option value="Alpha Size" {{ old('jo_category') == 'Alpha Size' ? 'selected' : '' }}>Alpha Size</option>
                        </select>
                        @error('jo_category')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <input type="hidden" name="order_type" value="Whole">
                    <input type="hidden" name="product_id" value="37">
                    {{-- <div class="form-group">
                        <label class="d-block">Order Type <span class="tx-danger">*</span></label>
                        <select class="selectpicker mg-b-5" data-style="btn btn-outline-light btn-md btn-block tx-left" title="Select order type" data-width="100%" name="order_type">
                            <option value="Whole">Whole</option>
                            <option value="Reserved">Reserved</option>
                            <option value="Additional">Additional</option>
                        </select>
                    </div> --}}

                    <!-- <div class="form-group">
                        <label class="d-block">Delivery Type <span class="tx-danger">*</span></label>
                        <select class="selectpicker mg-b-5" data-style="btn btn-outline-light btn-md btn-block tx-left" title="Select order type" data-width="100%" name="delivery_type">
                            <option value="door to door">Door to door</option>
                            <option value="pick-up at store">Pick-up at store</option>
                        </select>
                    </div> -->
                    <input type="hidden" name="delivery_type" id="delivery_type" value="storepickup">

                    <div class="form-group">
                        <label class="d-block">Production Branch <span class="tx-danger">*</span></label>
                        <select class="selectpicker mg-b-5" data-style="btn btn-outline-light btn-md btn-block tx-left" title="Select production branch" data-width="100%" name="prodbranch_id">
                            @foreach($prod_branches as $branch)
                                <option value="{{$branch->id}}" {{ old('prodbranch_id') == $branch->id ? 'selected' : '' }}>{{$branch->name}}</option>
                            @endforeach
                        </select>
                        @error('prodbranch_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="d-block">Receiver Branch <span class="tx-danger">*</span></label>
                        <select class="selectpicker mg-b-5" multiple="multiple" data-style="btn btn-outline-light btn-md btn-block tx-left" title="Select branch" data-width="100%" name="branch_id[]">
                            @foreach($prod_stores as $store)
                                @if(!in_array($store->name,['Globe: +63917 538 0304', 'Globe: +63917 820 2989', 'Smart: +6918 967 5213']))
                                    <option value="{{$store->id}}" {{ old('branch_id') && in_array($store->id, old('branch_id')) ? 'selected' : '' }}>{{$store->name}}</option>
                                @endif
                            @endforeach
                        </select>
                        @error('branch_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="d-block">Production Time <i class="text-danger">*</i></label>
                                <input type="text" name="production_date" class="form-control" placeholder="Choose date" id="date2" value="{{ old('production_date') }}">
                                @error('production_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="d-block">&nbsp;</label>
                                <div class="input-group timepicker">
                                    <select class="selectpicker" data-style="btn btn-outline-light btn-md btn-block tx-left" title="Choose time" data-width="100%" name="production_time">
                                        <option value="07:00">07:00 AM</option>
                                        <option value="12:00">12:00 NN</option>
                                        <option value="14:00">02:00 PM</option>
                                        <option value="17:00">05:00 PM</option>
                                        <option value="01:00">01:00 AM</option>
                                        <option value="02:00">02:00 AM</option>
                                        <option value="03:00">03:00 AM</option>
                                        <option value="04:00">04:00 AM</option>
                                        <option value="05:00">05:00 AM</option>
                                        <option value="06:00">06:00 AM</option>
                                        <option value="08:00">08:00 AM</option>
                                        <option value="09:00">09:00 AM</option>
                                        <option value="10:00">10:00 AM</option>
                                        <option value="11:00">11:00 AM</option>
                                        <option value="13:00">01:00 PM</option>
                                        <option value="15:00">03:00 PM</option>
                                        <option value="16:00">04:00 PM</option>
                                        <option value="17:00">05:00 PM</option>
                                        <option value="18:00">06:00 PM</option>
                                        <option value="19:00">07:00 PM</option>
                                        <option value="20:00">08:00 PM</option>
                                        <option value="21:00">09:00 PM</option>
                                        <option value="22:00">10:00 PM</option>
                                        <option value="23:00">11:00 PM</option>
                                    </select>
                                </div>
                                @error('production_time')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="d-block">Date Needed <i class="text-danger">*</i></label>
                                <input type="text" name="date_needed" class="form-control" placeholder="Choose date" id="date1" value="{{ old('date_needed') }}">
                                @error('date_needed')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="d-block">&nbsp;</label>
                                <div class="input-group timepicker">
                                    <select class="selectpicker" data-style="btn btn-outline-light btn-md btn-block tx-left" title="Choose time" data-width="100%" name="time_needed">
                                        <option value="07:00">07:00 AM</option>
                                        <option value="12:00">12:00 NN</option>
                                        <option value="14:00">02:00 PM</option>
                                        <option value="17:00">05:00 PM</option>
                                        <option value="01:00">01:00 AM</option>
                                        <option value="02:00">02:00 AM</option>
                                        <option value="03:00">03:00 AM</option>
                                        <option value="04:00">04:00 AM</option>
                                        <option value="05:00">05:00 AM</option>
                                        <option value="06:00">06:00 AM</option>
                                        <option value="08:00">08:00 AM</option>
                                        <option value="09:00">09:00 AM</option>
                                        <option value="10:00">10:00 AM</option>
                                        <option value="11:00">11:00 AM</option>
                                        <option value="13:00">01:00 PM</option>
                                        <option value="15:00">03:00 PM</option>
                                        <option value="16:00">04:00 PM</option>
                                        <option value="17:00">05:00 PM</option>
                                        <option value="18:00">06:00 PM</option>
                                        <option value="19:00">07:00 PM</option>
                                        <option value="20:00">08:00 PM</option>
                                        <option value="21:00">09:00 PM</option>
                                        <option value="22:00">10:00 PM</option>
                                        <option value="23:00">11:00 PM</option>
                                    </select>
                                </div>
                                @error('time_needed')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="d-block">Size </label>
                        <input name="size" class="form-control" value="{{ old('size') }}"></input>
                        @error('size')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="d-block">Additional Instruction </label>
                        <textarea name="remarks" class="form-control">{{ old('remarks') }}</textarea>
                        @error('remarks')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </form>
            </div>

            <div class="col-lg-12 mg-t-20 mg-b-30">
                <button class="btn btn-primary btn-sm btn-uppercase" type="submit">Save Job Order</button>
                <a href="{{route('joborders.index')}}" class="btn btn-outline-secondary btn-sm btn-uppercase ">Cancel</a>
            </div>
        </div>
    </form>
</div>

<div class="modal effect-scale" id="prompt-product-validation" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="prompt_msg"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pagejs')
    <script src="{{ asset('lib/jqueryui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/prismjs/prism.js') }}"></script>
    <script src="{{ asset('lib/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('lib/typeahead.js/typeahead.bundle.min.js') }}"></script>
    <script src="{{ asset('lib/bootstrap-tagsinput/bootstrap-tagsinput.min.js') }}"></script>

    <script src="{{ asset('lib/datextime/moment.min.js') }}"></script>
    <script src="{{ asset('lib/datextime/daterangepicker.js') }}"></script>
    <script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>
@endsection

@section('customjs')

<script>
document.addEventListener('DOMContentLoaded', function () {
  // Elements
  const $prodDate = document.getElementById('date2');                  // Production date
  const $needDate = document.getElementById('date1');                  // Date Needed date
  const $prodTime = document.querySelector('select[name="production_time"]');
  const $needTime = document.querySelector('select[name="time_needed"]');

  // Helpers
  const parseYMD = (s) => {
    if (!s) return null;
    const [y,m,d] = s.split('-').map(Number);
    if (!y || !m || !d) return null;
    return new Date(y, m - 1, d);
  };
  const sameYMD = (a, b) =>
    !!(a && b) &&
    a.getFullYear() === b.getFullYear() &&
    a.getMonth() === b.getMonth() &&
    a.getDate() === b.getDate();

  const toMinutes = (hhmm) => {
    if (!hhmm) return null;
    const [h, m] = hhmm.split(':').map(Number);
    return h * 60 + (m || 0);
  };

  function refreshSelectpicker(sel) {
    if (sel && sel.classList.contains('selectpicker') && typeof $().selectpicker === 'function') {
      $(sel).selectpicker('refresh');
    }
  }

  function enforceNeedTimeBounds() {
    const prodD = parseYMD($prodDate.value);
    const needD = parseYMD($needDate.value);
    const pMins = toMinutes($prodTime.value);

    // Reset: show/enable all times first
    Array.from($needTime.options).forEach(opt => {
      opt.hidden = false;
      opt.disabled = false;
    });

    if (prodD && needD && sameYMD(prodD, needD) && pMins != null) {
      Array.from($needTime.options).forEach(opt => {
        const m = toMinutes(opt.value);
        if (m != null && m < pMins) {
          opt.hidden = true;      // hide earlier times
          opt.disabled = true;    // and disable for safety
        }
      });
    }

    // If selected is invalid, pick the first visible valid option
    const selected = $needTime.selectedOptions[0];
    if (!selected || selected.disabled || selected.hidden) {
      const firstValid = Array.from($needTime.options).find(o => !o.disabled && !o.hidden);
      $needTime.value = firstValid ? firstValid.value : '';
    }
    refreshSelectpicker($needTime);
  }

  function enforceDateBounds() {
    const prodD = parseYMD($prodDate.value);
    // Update Need Date min to Production Date (or today if none)
    needPicker.set('minDate', prodD || new Date());

    // If Need Date < Prod Date, snap to Prod Date
    const needD = parseYMD($needDate.value);
    if (prodD && needD && needD < prodD) {
      needPicker.setDate(prodD, true); // triggerChange = true
    }

    enforceNeedTimeBounds();
  }

  // Flatpickr init
  const common = {
    dateFormat: 'Y-m-d',
    allowInput: true,
    disableMobile: false
  };

  const prodPicker = flatpickr($prodDate, {
    ...common,
    minDate: new Date(),
    onChange: function () { enforceDateBounds(); }
  });

  const needPicker = flatpickr($needDate, {
    ...common,
    minDate: new Date(),
    onChange: function () { enforceNeedTimeBounds(); }
  });

  // Events for time selects
  $prodTime.addEventListener('change', enforceNeedTimeBounds);

  // Initial pass (handles old() values)
  enforceDateBounds();
  enforceNeedTimeBounds();
});
</script>




@endsection
