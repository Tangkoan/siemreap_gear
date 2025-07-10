{{-- 
    ============================================================
    FINAL INVOICE-STYLE MODAL - Using 'orderDetails'
    ============================================================
--}}

<div id="orderDetailsModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-4xl">
        
        <div id="invoice-content" class="shadow-lg rounded-lg bg-white dark:bg-gray-800 p-8">
            
            <div class="flex justify-between items-start pb-6 border-b border-gray-200 dark:border-gray-700">
                <div>
                    <h1 class="text-3xl font-bold text-blue-600 dark:text-blue-500">INVOICE</h1>
                    <p class="text-sm text-gray-500">Invoice No: <span id="invoice-no" class="font-medium text-gray-700 dark:text-gray-300"></span></p>
                </div>
                <div class="text-right">
                    <p class="text-lg font-semibold text-gray-800 dark:text-white">Your Company Name</p>
                    <p class="text-sm text-gray-500">Your Address, Siem Reap</p>
                    <p class="text-sm text-gray-500">Phone: 012 345 678</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 my-6">
                <div>
                    <p class="font-semibold text-gray-600 dark:text-gray-400">BILLED TO:</p>
                    <p id="customer-name" class="text-lg font-medium text-gray-900 dark:text-white"></p>
                    <p id="customer-phone" class="text-sm text-gray-500"></p>
                </div>
                <div class="text-right">
                    <p><span class="font-semibold text-gray-600 dark:text-gray-400">Date of Issue:</span> <span id="order-date"></span></p>
                    <p><span class="font-semibold text-gray-600 dark:text-gray-400">Status:</span> <span id="order-status-badge" class="px-2 py-1 text-xs font-bold rounded-full"></span></p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="p-3 text-sm font-semibold text-left text-gray-600 dark:text-gray-300 w-2/5">PRODUCT</th>
                            <th class="p-3 text-sm font-semibold text-center text-gray-600 dark:text-gray-300">QTY</th>
                            <th class="p-3 text-sm font-semibold text-right text-gray-600 dark:text-gray-300">PRICE</th>
                            <th class="p-3 text-sm font-semibold text-right text-gray-600 dark:text-gray-300">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody id="modal-table-body" class="divide-y divide-gray-200 dark:divide-gray-600 text-sm"></tbody>
                </table>
            </div>

            <div class="flex justify-end mt-6">
                <div class="w-full max-w-sm">
                    <div class="flex justify-between text-gray-600 dark:text-gray-300">
                        <span>Subtotal:</span>
                        <span id="summary-subtotal">$0.00</span>
                    </div>
                    <div class="flex justify-between text-gray-600 dark:text-gray-300 mt-2">
                        <span>Discount:</span>
                        <span id="summary-discount" class="text-red-500">-$0.00</span>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-600 my-2"></div>
                    <div class="flex justify-between text-gray-900 dark:text-white font-bold text-lg">
                        <span>Grand Total:</span>
                        <span id="summary-grandtotal">$0.00</span>
                    </div>
                    <div class="flex justify-between text-gray-600 dark:text-gray-300 mt-2">
                        <span>Payment Method:</span>
                        <span id="summary-payment-method" class="font-medium"></span>
                    </div>
                </div>
            </div>

        </div>
        <button id="closeModalBtn" class="absolute -top-3 -right-3 p-2 bg-white dark:bg-gray-600 rounded-full shadow-lg hover:bg-gray-200 dark:hover:bg-gray-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800 dark:text-gray-100" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
    </div>
</div>

<script>
$(document).ready(function() {
    
    $(document).on('click', '.view-details-btn', function() {
        const orderId = $(this).data('order-id');
        $('#orderDetailsModal').removeClass('hidden');
        $('#modal-table-body').html('<tr><td colspan="4" class="text-center p-6">Loading...</td></tr>');
        
        $.ajax({
            url: `{{ route('report.orders.details') }}`,
            type: 'GET',
            data: { order_id: orderId },
            success: function(response) {
                const order = response.order;
                // ✅ កែពី orderItems ទៅជា orderDetails
                const orderDetails = response.orderDetails; 
                const assetBaseUrl = "{{ asset('') }}";

                // Populate Invoice Data
                $('#invoice-no').text(order.invoice_no);
                $('#order-date').text(new Date(order.order_date).toLocaleDateString('en-GB'));
                $('#customer-name').text(order.customer.name);
                $('#customer-phone').text(order.customer.phone);
                
                const statusBadge = $('#order-status-badge');
                statusBadge.text(order.order_status);
                if (order.order_status === 'Paid') {
                    statusBadge.removeClass('bg-red-100 text-red-800').addClass('bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300');
                } else {
                    statusBadge.removeClass('bg-green-100 text-green-800').addClass('bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300');
                }

                let subtotal = 0;
                // ✅ ធានាថា Subtotal គណនាត្រឹមត្រូវ
                if (orderDetails && Array.isArray(orderDetails)) {
                    orderDetails.forEach(item => subtotal += parseFloat(item.total));
                }
                
                $('#summary-subtotal').text(`$${subtotal.toFixed(2)}`);
                $('#summary-discount').text(`-$${parseFloat(order.discount || 0).toFixed(2)}`);
                $('#summary-grandtotal').text(`$${parseFloat(order.total).toFixed(2)}`);
                $('#summary-payment-method').text(order.payment_status);

                let detailsHtml = '';
                // ✅ ប្រើ orderDetails សម្រាប់วนลูป
                if (orderDetails && Array.isArray(orderDetails) && orderDetails.length > 0) {
                    orderDetails.forEach(function(item) {
                        detailsHtml += `
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="p-3"><div class="flex items-center gap-4"><img src="${assetBaseUrl}/${item.product.product_image}" alt="${item.product.product_name}" class="w-12 h-12 object-cover rounded-md"><div><div class="font-semibold text-gray-800 dark:text-white">${item.product.product_name}</div><div class="text-xs text-gray-500">${item.product.product_code}</div></div></div></td>
                                <td class="p-3 text-center">${item.quantity}</td>
                                <td class="p-3 text-right">$${parseFloat(item.unitcost).toFixed(2)}</td>
                                <td class="p-3 text-right font-medium">$${parseFloat(item.total).toFixed(2)}</td>
                            </tr>`;
                    });
                } else {
                    detailsHtml = '<tr><td colspan="4" class="text-center p-6">No items found for this order.</td></tr>';
                }
                $('#modal-table-body').html(detailsHtml);
            },
            error: function() {
                $('#modal-table-body').html('<tr><td colspan="4" class="text-center text-red-500 p-6">Failed to load details.</td></tr>');
            }
        });
    });

    // Close Modal Logic
    function closeModal() { $('#orderDetailsModal').addClass('hidden'); }
    $(document).on('click', '#closeModalBtn', closeModal);
    $('#orderDetailsModal').on('click', function(e) { if ($(e.target).is('#orderDetailsModal')) closeModal(); });
    $(document).on('keydown', function(e) { if (e.key === "Escape" && !$('#orderDetailsModal').hasClass('hidden')) closeModal(); });
});
</script>