<hr>
<div class="form-group">
    <div class="jo-products">
        <div class="card bg-gray-100">
            <div class="card-header"><i class="fa fa-calculator"></i> On-Screen Computation</div>
            <div class="card-body">
                <div>Added Products</div>
                <table class="table table-borderless table-xs mg-b-20 bd-b">
                    <thead>
                        <th>Product Name</th>
                        <th>Price/Item</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                    </thead>
                    <tbody id="order_summary">
                    </tbody>
                </table>
                <div>Added Miscellaneous Products</div>
                <table class="table table-borderless table-xs mg-b-20 bd-b">
                    <thead>
                        <th>Product Name</th>
                        <th>Price/Item</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th></th>
                    </thead>
                    <tbody id="misc_summary">
                        <tr style="display: none;"><td><input type="text" name="total_misc" value="0" id="total_misc"></td></tr>
                    </tbody>
                </table>
                <div class="table-responsive pd-b-20 on-screen-computation">
                    <table class="table table-borderless table-xs mg-b-0 bd-b">
                        <tbody id="tbod">
                            <tr>
                                <th scope="row"><strong>Sub Total :</strong> </th>
                                <td id="subtotal">0</td>
                                <td style="display: none;"><input type="text" id="input_subtotal" value="0"></td>
                            </tr>
                            <tr>
                                <th scope="row"><strong>Quantity :</strong></th>
                                <td id="totalQty">0</td>
                            </tr>
                            <tr>
                                <th scope="row"><strong>Delivery Charge :</strong></th>
                                <td id="summary_delivery_charge">0</td>
                                <td style="display: none;"><input type="text" id="input_delivery_charge" value="0"></td>
                            </tr>
                            <tr>
                                <th scope="row"><strong>Total Misc :</strong></th>
                                <td id="summary_total_misc">0</td>
                                <td style="display: none;"><input type="text" id="input_total_misc" value="0"></td>
                            </tr>
                            
                        </tbody>
                    </table>
                    <table class="table table-borderless table-xs mg-b-0 bd-b">
                        <tbody>
                            <tr>
                                <th scope="row"><strong>Total :</strong> </th>
                                <td id="grand_total">0</td>
                                <td style="display: none;"><input type="text" name="gross" id="summary_input_gross" value="0"></td>
                            </tr>
                            @if(auth()->user()->role_id == 4)
                            <tr>
                                <th scope="row"><strong>Deposit :</strong></th>
                                <td id="summary_deposit">0</td>
                                <td style="display: none;"><input type="text" id="summary_input_deposit" value="0"></td>
                            </tr>
                            <tr>
                                <th scope="row"><strong>Discount :</strong></th>
                                <td id="summary_discount">0</td>
                                <td style="display: none;"><input type="text" id="summary_input_discount" value="0"></td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    @if(auth()->user()->role_id == 4)
                    <table class="table table-borderless table-xs mg-b-0">
                        <tbody>
                            <tr>
                                <th scope="row"><strong>BALANCE :</strong> </th>
                                <td id="balance"><strong>PHP 0.00</strong></td>
                                <td style="display: none;"><input type="text" name="balance" id="summary_input_balance" value="0"></td>
                            </tr>
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>