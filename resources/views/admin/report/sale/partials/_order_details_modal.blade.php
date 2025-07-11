<div id="orderDetailsModal" class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm overflow-y-auto h-full w-full z-50 hidden flex items-center justify-center p-4">
    <div class="relative w-full max-w-4xl mx-auto">
        <div id="invoice-content" class="shadow-2xl rounded-2xl bg-white dark:bg-slate-800 transform transition-all">
            
            <div class="px-8 pt-8 pb-4 flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-blue-600 dark:text-blue-500">INVOICE</h1>
                    <p class="text-sm text-slate-500 mt-1">Invoice No: <span id="invoice-no" class="font-semibold text-slate-700 dark:text-slate-300"></span></p>
                </div>
                <div class="flex items-center gap-2">
                    <button id="printInvoiceBtn" class="p-2 text-slate-500 hover:text-blue-600 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-full transition-colors" title="Print Invoice">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0" /></svg>
                    </button>
                    <button id="closeModalBtn" class="p-2 text-slate-500 hover:text-red-600 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-full transition-colors" title="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </div>

            <div class="p-8 print-area">
                {{-- This div is for printing content only --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                     <div>
                        <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">BILLED TO</p>
                        <p id="customer-name" class="text-lg font-bold text-slate-900 dark:text-white mt-1"></p>
                        <p id="customer-phone" class="text-sm text-slate-500"></p>
                    </div>
                    <div class="text-left md:text-right">
                        <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">DATE OF ISSUE</p>
                        <p id="order-date" class="font-medium text-slate-800 dark:text-slate-200 mt-1"></p>
                        <p class="text-sm font-semibold text-slate-500 dark:text-slate-400 mt-4">STATUS</p>
                        <span id="order-status-badge" class="px-3 py-1 text-xs font-bold rounded-full mt-1 inline-block"></span>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-lg border dark:border-slate-700">
                    <table class="min-w-full">
                        <thead class="bg-slate-50 dark:bg-slate-700">
                            <tr>
                                <th class="p-4 text-sm font-semibold text-left text-slate-600 dark:text-slate-300 w-2/5">PRODUCT</th>
                                <th class="p-4 text-sm font-semibold text-center text-slate-600 dark:text-slate-300">QTY</th>
                                <th class="p-4 text-sm font-semibold text-right text-slate-600 dark:text-slate-300">UNIT PRICE</th>
                                <th class="p-4 text-sm font-semibold text-right text-slate-600 dark:text-slate-300">TOTAL</th>
                            </tr>
                        </thead>
                        <tbody id="modal-table-body" class="divide-y divide-slate-200 dark:divide-slate-600 text-sm"></tbody>
                    </table>
                </div>

                <div class="flex justify-end mt-8">
                    <div class="w-full max-w-xs space-y-3">
                        <div class="flex justify-between text-slate-600 dark:text-slate-300"><span>Subtotal:</span><span id="summary-subtotal">$0.00</span></div>
                        <div class="flex justify-between text-slate-600 dark:text-slate-300"><span>Discount:</span><span id="summary-discount" class="text-red-500">-$0.00</span></div>
                        <div class="border-t border-dashed border-slate-300 dark:border-slate-600 my-2"></div>
                        <div class="flex justify-between text-slate-900 dark:text-white font-bold text-xl"><span>Grand Total:</span><span id="summary-grandtotal">$0.00</span></div>
                        <div class="border-t border-slate-200 dark:border-slate-700 pt-3 mt-3"><div class="flex justify-between text-slate-600 dark:text-slate-300"><span>Payment Method:</span><span id="summary-payment-method" class="font-semibold"></span></div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>