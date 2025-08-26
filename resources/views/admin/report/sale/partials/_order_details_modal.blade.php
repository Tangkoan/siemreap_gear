<div id="orderDetailsModal" class="fixed inset-0 z-50 flex h-full w-full items-center justify-center overflow-y-auto bg-black bg-opacity-60 p-4 backdrop-blur-sm hidden">
    <div class="relative w-full max-w-4xl mx-auto">
        <div id="invoice-content" class="transform rounded-2xl bg-white shadow-2xl transition-all dark:bg-slate-800">
            
            <div class="flex items-start justify-between px-8 pt-8 pb-4">
                <div>
                    <h1 class="text-3xl font-bold text-blue-600 dark:text-blue-500">INVOICE</h1>
                    <p class="mt-1 text-sm text-slate-500">Invoice No: <span id="invoice-no" class="font-semibold text-slate-700 dark:text-slate-300"></span></p>
                </div>
                <div class="flex items-center gap-2">
                    <button id="printInvoiceBtn" class="rounded-full p-2 text-slate-500 transition-colors hover:bg-slate-100 hover:text-blue-600 dark:hover:bg-slate-700" title="Print Invoice">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0" /></svg>
                    </button>
                    <button id="closeModalBtn" class="rounded-full p-2 text-slate-500 transition-colors hover:bg-slate-100 hover:text-red-600 dark:hover:bg-slate-700" title="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </div>

            <div class="print-area p-8">
                <div class="mb-8 grid grid-cols-1 gap-8 md:grid-cols-2">
                    <div>
                        <div class="flex items-baseline gap-2">
                            <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">Customer Name:</p>
                            <p id="customer-name" class="text-sm font-bold text-slate-900 dark:text-white"></p>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">Customer Phone:</p>
                            <p id="customer-phone" class="text-sm font-bold text-slate-900 dark:text-white"></p>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">Payment Method:</p>
                            <p id="summary-payment-method" class="text-sm font-bold text-slate-900 dark:text-white"></p>
                        </div>
                    </div>

                    <div class="text-left md:text-right">
                        <div class="flex items-baseline gap-2">
                            <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">DATE OF ISSUE:</p>
                            <p id="order-date" class="text-sm font-bold text-slate-900 dark:text-white"></p>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">STATUS:</p>
                            <p id="order-status-badge" class="rounded-full px-3 py-1 text-xs font-bold"></p>
                        </div>
                    </div>

                </div>

                <div class="overflow-x-auto rounded-lg border dark:border-slate-700">
                    <table class="min-w-full">
                        <thead class="bg-slate-50 dark:bg-slate-700">
                            <tr>
                                <th class="w-2/5 p-4 text-left text-sm font-semibold text-slate-600 dark:text-slate-300">PRODUCT</th>
                                <th class="p-4 text-center text-sm font-semibold text-slate-600 dark:text-slate-300">STATUS</th>
                                <th class="p-4 text-center text-sm font-semibold text-slate-600 dark:text-slate-300">QTY</th>
                                <th class="p-4 text-right text-sm font-semibold text-slate-600 dark:text-slate-300">UNIT PRICE</th>
                                <th class="p-4 text-right text-sm font-semibold text-slate-600 dark:text-slate-300">TOTAL</th>
                            </tr>
                        </thead>
                        <tbody id="modal-table-body" class="text-sm divide-y divide-slate-200 dark:divide-slate-600"></tbody>
                    </table>
                </div>

                <div class="mt-8 flex justify-end">
                    <div class="w-full max-w-xs space-y-3">
                        <div class="flex justify-between text-slate-600 dark:text-slate-300"><span>Subtotal:</span><span id="summary-subtotal">$0.00</span></div>
                        <div class="flex justify-between text-slate-600 dark:text-slate-300"><span>Discount:</span><span id="summary-discount" class="text-red-500">-$0.00</span></div>
                        <div class="my-2 border-t border-dashed border-slate-300 dark:border-slate-600"></div>
                        <div class="flex justify-between text-xl font-bold text-slate-900 dark:text-white"><span>Grand Total:</span><span id="summary-grandtotal">$0.00</span></div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    
    // --- SHARED VARIABLES ---
    let searchTimeout;
    const activeTabClasses = 'bg-red-600 text-white';
    const inactiveTabClasses = 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700';

    // --- HELPER FUNCTIONS ---
    function showLoading(tableBody) {
        tableBody.html(`<tr><td colspan="7" class="text-center p-8"><div class="flex justify-center items-center gap-2 text-slate-500"><svg class="animate-spin h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>Loading...</span></div></td></tr>`);
    }

    function updateKPIs(period, kpis) {
        if(kpis){
            $(`#kpi-revenue-${period}`).text(kpis.revenue);
            $(`#kpi-orders-${period}`).text(kpis.orders);
            $(`#kpi-items-${period}`).text(kpis.items);
            $(`#kpi-avg-${period}`).text(kpis.avg);
            $(`#kpi-pre_orders-${period}`).text(kpis.pre_orders);
        }
    }
    
    function updateExportLink(period) {
        let url;
        let params = {};
        switch(period) {
            case 'day':
                url = new URL("{{ route('report.orders.export.date') }}");
                params.date = $('#date-day').val();
                params.search = $('#search-day').val();
                break;
            case 'month':
                url = new URL("{{ route('report.orders.export.month') }}");
                params.month = $('#month-month').val();
                params.search = $('#search-month').val();
                break;
            case 'year':
                url = new URL("{{ route('report.orders.export.year') }}");
                params.year = $('#year-year').val();
                params.search = $('#search-year').val();
                break;
        }
        if(params.date) url.searchParams.set('date', params.date);
        if(params.month) url.searchParams.set('month', params.month);
        if(params.year) url.searchParams.set('year', params.year);
        if(params.search) url.searchParams.set('search', params.search);
        $(`#exportBtn-${period}`).attr('href', url.href);
    }
    
    // --- DATA FETCHING LOGIC ---
    const fetchData = (period, page = 1) => {
        let url, data = { page: page };
        switch(period) {
            case 'day':
                url = "{{ route('report.orders.by_date') }}";
                data.date = $('#date-day').val();
                data.search = $('#search-day').val();
                break;
            case 'month':
                url = "{{ route('report.orders.by_month') }}";
                data.month = $('#month-month').val();
                data.search = $('#search-month').val();
                break;
            case 'year':
                url = "{{ route('report.orders.by_year') }}";
                data.year = $('#year-year').val();
                data.search = $('#search-year').val();
                break;
        }
        showLoading($(`#report-table-body-${period}`));

        $.ajax({
            url: url,
            type: 'GET',
            data: data,
            success: function(response) {
                $(`#report-table-body-${period}`).html(response.table);
                $(`#report-table-footer-${period}`).html(response.footer);
                $(`#pagination-links-${period}`).html(response.pagination);
                updateKPIs(period, response.kpis);
                updateExportLink(period);
            },
            error: function() {
                $(`#report-table-body-${period}`).html(`<tr><td colspan="7" class="text-center p-8 text-red-500">Failed to load data. Please try again.</td></tr>`);
            }
        });
    }

    // --- TAB SWITCHING ---
    $('#reportTab .tab-button').on('click', function() {
        const target = $(this).data('tab-target');
        
        $('#reportTab .tab-button').removeClass(activeTabClasses).addClass(inactiveTabClasses);
        $(this).removeClass(inactiveTabClasses).addClass(activeTabClasses);

        $('.tab-pane').addClass('hidden');
        $(target).removeClass('hidden');

        const period = target.replace('#', '').replace('-tab-content', '');
        if (!$(this).data('loaded')) {
            fetchData(period);
            $(this).data('loaded', true);
        }
    });

    // --- EVENT LISTENERS FOR FILTERS ---
    ['day', 'month', 'year'].forEach(period => {
        $(`#date-${period}, #month-${period}, #year-${period}`).on('change', function() { fetchData(period, 1); });
        $(`#search-${period}`).on('keyup', function() { 
            clearTimeout(searchTimeout); 
            searchTimeout = setTimeout(() => fetchData(period, 1), 500); 
        });
        // Pagination click handler
        $(document).on('click', `#pagination-links-${period} .pagination a`, function(e) {
            e.preventDefault();
            let page = $(this).attr('href').split('page=')[1];
            fetchData(period, page);
        });
    });

    // --- MODAL LOGIC ---
    $(document).on('click', '.view-details-btn', function() {
        const orderId = $(this).data('order-id');
        $('#orderDetailsModal').removeClass('hidden');

        $('#modal-table-body').html('<tr><td colspan="5" class="text-center p-6">Loading details...</td></tr>'); 
        
        $.ajax({
            url: "{{ route('report.orders.details') }}", // Make sure this route is correct
            type: 'GET',
            dataType: 'json', // Expect a JSON response
            data: { order_id: orderId },
            success: function(response) {
                const { order, orderDetails, assetBaseUrl } = response;

                // Populate invoice details
                $('#invoice-no').text(order.invoice_no);
                $('#customer-name').text(order.customer.name);
                $('#customer-phone').text(order.customer.phone || 'Phone Number : N/A');
                $('#summary-payment-method').text(order.payment_status);

                const formattedDate = new Date(order.order_date).toLocaleDateString('en-GB', {
                    day: '2-digit', month: 'long', year: 'numeric'
                });
                $('#order-date').text(formattedDate);
                
                const statusBadge = $('#order-status-badge');
                statusBadge.text(order.order_status);
                statusBadge.removeClass('bg-green-100 text-green-800 bg-red-100 text-red-800').addClass(order.order_status === 'complete' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800');

                // Populate table with order items
                let detailsHtml = '';
                if (orderDetails && orderDetails.length > 0) {
                    orderDetails.forEach(item => {
                        const imageUrl = item.product.product_image ? `${assetBaseUrl}${item.product.product_image}` : `${assetBaseUrl}image/no_image.jpg`;
                        const itemStatusText = item.item_status === 'pre_ordered' ? 'Pre-Order' : 'Fulfilled';
                        const itemStatusClass = item.item_status === 'pre_ordered' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700';

                        detailsHtml += `<tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="p-4"><div class="flex items-center gap-4"><img src="${imageUrl}" alt="${item.product.product_name}" class="w-12 h-12 rounded-lg object-cover"><div class="flex-grow"><div class="font-semibold text-gray-800 dark:text-white">${item.product.product_name}</div><div class="text-xs text-gray-500">${item.product.product_code}</div></div></div></td>
                            <td class="p-4 text-center"><span class="rounded-full px-2 py-1 text-xs font-semibold leading-tight ${itemStatusClass}">${itemStatusText}</span></td>
                            <td class="p-4 text-center">${item.quantity}</td>
                            <td class="p-4 text-right">$${parseFloat(item.unitcost).toFixed(2)}</td>
                            <td class="p-4 text-right font-medium">$${parseFloat(item.total).toFixed(2)}</td>
                        </tr>`;
                    });
                } else {
                    detailsHtml = '<tr><td colspan="5" class="p-6 text-center text-slate-500">No items found.</td></tr>';
                }
                $('#modal-table-body').html(detailsHtml);

                // Populate summary totals
                $('#summary-subtotal').text(`$${parseFloat(order.sub_total || 0).toFixed(2)}`);
                $('#summary-discount').text(`-$${parseFloat(order.discount || 0).toFixed(2)}`);
                $('#summary-grandtotal').text(`$${parseFloat(order.total || 0).toFixed(2)}`);
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error, xhr.responseText);
                let message = 'Failed to load details. Please check the console for more information.';
                if (status === 'parsererror') {
                    message = 'Error: Server returned invalid data format.';
                } else if (xhr.status === 404) {
                    message = 'Error: Details endpoint not found (404).';
                }
                $('#modal-table-body').html(`<tr><td colspan="5" class="p-6 text-center text-red-500">${message}</td></tr>`);
            }
        });
    });

    // --- MODAL CLOSE & PRINT LOGIC ---
    const closeModal = () => $('#orderDetailsModal').addClass('hidden');
    $(document).on('click', '#closeModalBtn', closeModal);
    $(document).on('click', '#orderDetailsModal', function(e) { if ($(e.target).is('#orderDetailsModal')) closeModal(); });
    $(document).on('keydown', function(e) { if (e.key === "Escape" && !$('#orderDetailsModal').hasClass('hidden')) closeModal(); });
    
    $(document).on('click', '#printInvoiceBtn', function() {
        const printContent = document.getElementById('invoice-content').innerHTML;
        const originalContent = document.body.innerHTML;
        document.body.innerHTML = printContent;
        window.print();
        document.body.innerHTML = originalContent;
        location.reload(); 
    });

    // --- INITIAL LOAD ---
    $('#reportTab .tab-button').first().trigger('click');
});
</script>