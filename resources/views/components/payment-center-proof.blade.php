<div 
    x-data="{
        submitPayment() {
            this.successPaymentModal = true;
            this.paymentCenterProof = false;
            this.paymentMethodModal = false;
        }
    }"
    >

    <div x-show="paymentCenterProof"
        x-transition
        class="relative z-50"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true"
        style="display: none;">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>

        <!-- Modal content -->
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg pb-5">
                    <!-- Modal body -->
                    <div class="">
                        <div class="flex justify-between items-center px-3 pt-3">
                            <div class="flex gap-2 items-center">
                                <div class="text-2xl font-bold">Payment Center Proof</div>
                            </div>
                            <button @click="paymentCenterProof = false" class="self-end text-2xl text-gray-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
            
                        <div class="text-gray-600 font-medium px-4 mt-4">
                            To complete your order, please upload the payment details and proof of payment for validation.
                        </div>
            
                        <div class="px-4 mt-5">
                            <div class="mt-3">
                                <div class="my-2">
                                    <label for="bank" class="block mb-2 text-sm font-bold text-gray-900">Bank <span class="text-red-700">*</span></label>
                                    <select id="bank" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                        <option selected>Select a bank</option>
                                        <option value="bdo">BDO Unibank, Inc</option>
                                        <option value="un">Unionbank of the Philippines</option>
                                        <option value="metrobank">Metrobank</option>
                                      </select>
                                </div>
                                <div class="my-2">
                                    <label for="receipt" class="block mb-2 text-sm font-bold text-gray-900">Receipt/Reference Number * <span class="text-red-700">*</span></label>
                                    <input type="text" id="receipt" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="+63" required />
                                </div>
                                <div class="my-2">
                                    <label for="date" class="block mb-2 text-sm font-bold text-gray-900">Payment Date <span class="text-red-700">*</span></label>
                                    <div class="relative max-w-sm">
                                        <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                        </svg>
                                        </div>
                                        <input datepicker datepicker-autohide id="default-datepicker" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5" placeholder="Select date">
                                    </div>
                                </div>
                                <div class="my-2">
                                    <label for="date" class="block mb-2 text-sm font-bold text-gray-900">Attachment <span class="text-red-700">*</span></label>
                                    <div class="flex items-center justify-center w-full">
                                        <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 ">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                                </svg>
                                                <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                                <p class="text-xs text-gray-500">SVG, PNG, JPG or GIF (MAX. 800x400px)</p>
                                            </div>
                                            <input id="dropzone-file" type="file" class="hidden" />
                                        </label>
                                    </div> 
                                </div>
            
                                <div>
                                    <button 
                                        @click="submitPayment"
                                        class="bg-primary text-white py-4 px-4 rounded-lg mt-4 w-full">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>