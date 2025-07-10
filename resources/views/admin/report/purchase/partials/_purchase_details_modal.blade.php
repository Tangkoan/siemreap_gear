<div id="purchaseDetailsModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-4xl">
        <div class="shadow-lg rounded-lg bg-white dark:bg-gray-800 p-8">
            <div class="flex justify-between items-start pb-6 border-b border-gray-200 dark:border-gray-700">
                <div><h1 class="text-3xl font-bold text-cyan-600 dark:text-cyan-500">PURCHASE VOUCHER</h1><p class="text-sm text-gray-500">Invoice: <span id="purchase-invoice-no" class="font-medium"></span></p></div>
                <div class="text-right"><p class="text-lg font-semibold">Your Company Name</p><p class="text-sm text-gray-500">Your Address</p></div>
            </div>
            <div class="grid grid-cols-2 gap-4 my-6">
                <div><p class="font-semibold text-gray-600">SUPPLIER:</p><p id="supplier-name" class="text-lg font-medium"></p><p id="supplier-phone" class="text-sm"></p></div>
                <div class="text-right"><p><span class="font-semibold">Date:</span> <span id="purchase-date"></span></p><p><span class="font-semibold">Status:</span> <span id="purchase-status-badge"></span></p></div>
            </div>
            <div class="overflow-x-auto"><table class="min-w-full"><thead class="bg-gray-100 dark:bg-gray-700"><tr><th class="p-3 text-left">PRODUCT</th><th class="p-3 text-center">QTY</th><th class="p-3 text-right">PRICE</th><th class="p-3 text-right">TOTAL</th></tr></thead><tbody id="modal-purchase-table-body"></tbody></table></div>
            <div class="flex justify-end mt-6"><div class="w-full max-w-sm"><div class="flex justify-between mt-2"><span class="text-gray-600">Subtotal:</span><span id="summary-subtotal">$0.00</span></div><div class="flex justify-between mt-2"><span class="text-gray-600">Discount:</span><span id="summary-discount" class="text-red-500">-$0.00</span></div><div class="flex justify-between mt-2"><span class="text-gray-600">Shipping:</span><span id="summary-shipping">$0.00</span></div><div class="flex justify-between mt-2"><span class="text-gray-600">VAT:</span><span id="summary-vat">$0.00</span></div><div class="border-t my-2"></div><div class="flex justify-between font-bold text-lg"><span >Grand Total:</span><span id="summary-grandtotal">$0.00</span></div></div></div>
        </div>
        <button id="closePurchaseModalBtn" class="absolute -top-3 -right-3 p-2 bg-white rounded-full shadow-lg"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
    </div>
</div>
<script>
$(document).ready(function() {
    $(document).on('click', '.view-details-btn', function() {
        const purchaseId = $(this).data('purchase-id');
        $('#purchaseDetailsModal').removeClass('hidden');
        $('#modal-purchase-table-body').html('<tr><td colspan="4" class="text-center p-6">Loading...</td></tr>');
        $.ajax({
            url: `{{ route('report.purchases.details') }}`, type: 'GET', data: { purchase_id: purchaseId },
            success: function(response) {
                const purchase = response.purchase;
                const purchaseDetails = response.purchaseDetails;
                const assetBaseUrl = "{{ asset('') }}";
                $('#purchase-invoice-no').text(purchase.invoice_no);
                $('#purchase-date').text(new Date(purchase.purchase_date).toLocaleDateString('en-GB'));
                $('#supplier-name').text(purchase.supplier.name);
                $('#supplier-phone').text(purchase.supplier.phone);
                const statusBadge = $('#purchase-status-badge');
                statusBadge.text(purchase.payment_status);
                statusBadge.removeClass('bg-green-100 text-green-800 bg-red-100 text-red-800').addClass(purchase.payment_status === 'Paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800');
                $('#summary-subtotal').text(`$${parseFloat(purchase.sub_total || 0).toFixed(2)}`);
                $('#summary-discount').text(`-$${parseFloat(purchase.discount || 0).toFixed(2)}`);
                $('#summary-shipping').text(`$${parseFloat(purchase.shipping || 0).toFixed(2)}`);
                $('#summary-vat').text(`$${parseFloat(purchase.vat || 0).toFixed(2)}`);
                $('#summary-grandtotal').text(`$${parseFloat(purchase.total).toFixed(2)}`);
                let detailsHtml = '';
                if (purchaseDetails && purchaseDetails.length > 0) {
                    purchaseDetails.forEach(function(item) {
                        detailsHtml += `<tr><td class="p-3"><div class="flex items-center gap-4"><img src="${assetBaseUrl}/${item.product.product_image}" class="w-12 h-12 object-cover rounded-md"><div><div class="font-semibold">${item.product.product_name}</div><div class="text-xs text-gray-500">${item.product.product_code}</div></div></div></td><td class="p-3 text-center">${item.quantity}</td><td class="p-3 text-right">$${parseFloat(item.purchase_price).toFixed(2)}</td><td class="p-3 text-right font-medium">$${parseFloat(item.total).toFixed(2)}</td></tr>`;
                    });
                } else {
                    detailsHtml = '<tr><td colspan="4" class="text-center p-6">No items found.</td></tr>';
                }
                $('#modal-purchase-table-body').html(detailsHtml);
            },
            error: function() { $('#modal-purchase-table-body').html('<tr><td colspan="4" class="text-red-500 p-6">Error loading details.</td></tr>'); }
        });
    });
    function closePurchaseModal() { $('#purchaseDetailsModal').addClass('hidden'); }
    $(document).on('click', '#closePurchaseModalBtn', closePurchaseModal);
});
</script>