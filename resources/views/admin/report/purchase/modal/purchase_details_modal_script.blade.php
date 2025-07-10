$(document).on('click', '.view-details-btn', function() {
    let purchaseId = $(this).data('purchase-id');
    let url = `/report/purchases/details-modal/${purchaseId}`;

    $('#modal-body').html('<p class="text-center p-8">Loading details...</p>');
    $('#detailsModal').removeClass('hidden');

    $.ajax({
        url: url, type: 'GET',
        success: function(purchase) {
            let detailsHtml = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6"><div><h4 class="font-bold text-lg mb-2">Supplier Info</h4><p><strong>Name:</strong> ${purchase.supplier.name||'N/A'}</p><p><strong>Phone:</strong> ${purchase.supplier.phone||'N/A'}</p><p><strong>Address:</strong> ${purchase.supplier.address||'N/A'}</p></div><div><h4 class="font-bold text-lg mb-2">Purchase Info</h4><p><strong>Invoice #:</strong> ${purchase.invoice_no}</p><p><strong>Purchase Date:</strong> ${new Date(purchase.purchase_date).toLocaleDateString('en-GB')}</p><p><strong>Purchase Status:</strong> ${purchase.purchase_status}</p><p><strong>Payment Status:</strong> ${purchase.payment_status}</p></div></div>
                <div class="mt-6"><h4 class="font-bold text-lg mb-2">Purchase Items</h4><table class="min-w-full text-left text-sm"><thead class="bg-gray-100 dark:bg-gray-700"><tr><th class="p-2">Product</th><th class="p-2 text-center">Quantity</th><th class="p-2 text-right">Unit Price</th><th class="p-2 text-right">Total</th></tr></thead><tbody id="modal-items-body" class="divide-y divide-gray-200 dark:divide-gray-600"></tbody><tfoot class="font-semibold border-t-2 border-gray-300 dark:border-gray-600"><tr><td colspan="3" class="p-2 text-right">Subtotal:</td><td class="p-2 text-right">$${parseFloat(purchase.sub_total).toFixed(2)}</td></tr><tr><td colspan="3" class="p-2 text-right">Discount:</td><td class="p-2 text-right text-orange-500">$${parseFloat(purchase.discount||0).toFixed(2)}</td></tr><tr class="text-lg"><td colspan="3" class="p-2 text-right border-t">Total:</td><td class="p-2 text-right border-t">$${parseFloat(purchase.total).toFixed(2)}</td></tr><tr class="text-lg text-green-600"><td colspan="3" class="p-2 text-right">Paid Amount:</td><td class="p-2 text-right">$${parseFloat(purchase.pay||0).toFixed(2)}</td></tr><tr class="text-lg" id="modal-due-row"><td colspan="3" class="p-2 text-right">Amount Due:</td><td class="p-2 text-right">$${parseFloat(purchase.due||0).toFixed(2)}</td></tr></tfoot></table></div>`;
            $('#modal-body').html(detailsHtml);
            let itemsHtml = '';
            purchase.purchase_details.forEach(item => {
                itemsHtml += `<tr><td class="p-2">${item.product.product_name}</td><td class="p-2 text-center">${item.quantity}</td><td class="p-2 text-right">$${parseFloat(item.purchase_price).toFixed(2)}</td><td class="p-2 text-right">$${parseFloat(item.total).toFixed(2)}</td></tr>`;
            });
            $('#modal-items-body').html(itemsHtml);
            if(parseFloat(purchase.due)>0){$('#modal-due-row').addClass('text-red-600 font-bold');}else{$('#modal-due-row').addClass('text-gray-500');}
            $('#modal-title').text('Purchase Details: '+purchase.invoice_no);
        },
        error: function() {$('#modal-body').html('<p class="text-center text-red-500 p-8">Failed to load details.</p>');}
    });
});
$('#closeModal').on('click', function() {$('#detailsModal').addClass('hidden');});