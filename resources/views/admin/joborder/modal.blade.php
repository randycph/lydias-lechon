<div class="modal fade" id="addmiscellaneous" tabindex="-1" role="dialog" aria-labelledby="addmiscellaneous" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Add Miscellaneous</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" class="" style="display: none;" id="misc_msg">
                        <div class="alert alert-warning">Selected miscellaneous is already in the list.</div>
                    </div>
                    <div class="col-md-9">
                        <div class="form-group">
                            <label class="d-block">Select Product <span class="tx-danger">*</span></label>
                            <select class="selectpicker mg-b-5" data-style="btn btn-outline-light btn-md btn-block tx-left" title="Select miscellaneous products" data-width="100%" id="misc_products" onchange="add_misc_product();">
                                @foreach($miscelaneous as $m)
                                <option value="{{$m->name}}|{{$m->price}}|{{$m->id}}">{{$m->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="hidden" id="product_id">
                            <input type="hidden" id="product_name">
                            <label class="d-block">Quantity <span class="tx-danger">*</span></label>
                            <input type="number" name="qty1" value="1" class="form-control" id="misc_qty" onchange="misc_qty();" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        Total: Php
                    </div>
                    <div class="col-md-9">
                        <div class="form-group">
                            <input type="hidden" id="product_price">
                            <span class="d-flex justify-content-end" id="total_misc_price">0.00</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="misc_btn_add" onclick="add_to_screen_computation();">Add</button>
            </div>
        </div>
    </div>
</div>