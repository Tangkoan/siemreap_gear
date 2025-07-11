<script>
$(document).ready(function() {
    
    // --- SHARED VARIABLES ---
    let searchTimeout;
    const activeTabClasses = 'bg-blue-600 text-white';
    const inactiveTabClasses = 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700';

    // --- HELPER FUNCTIONS ---
    function showLoading(tableBody) {
        tableBody.html(`<tr><td colspan="7" class="text-center p-8"><div class="flex justify-center items-center gap-2 text-slate-500"><svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>Loading...</span></div></td></tr>`);
    }

    function updateKPIs(period, kpis) {
        if(kpis){
            $(`#kpi-revenue-${period}`).text(kpis.revenue);
            $(`#kpi-orders-${period}`).text(kpis.orders);
            $(`#kpi-items-${period}`).text(kpis.items);
            $(`#kpi-avg-${period}`).text(kpis.avg);
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
        $('#modal-table-body').html('<tr><td colspan="4" class="text-center p-6">Loading details...</td></tr>');
        
        $.ajax({
            url: "{{ route('report.orders.details') }}",
            type: 'GET',
            data: { order_id: orderId },
            success: function(response) {
                const { order, orderDetails } = response;
                const assetBaseUrl = "{{ asset('') }}";
                $('#invoice-no').text(order.invoice_no);
                $('#order-date').text(new Date(order.order_date).toLocaleDateString('en-GB', { day: '2-digit', month: 'long', year: 'numeric'}));
                $('#customer-name').text(order.customer.name);
                $('#customer-phone').text(order.customer.phone);
                
                const statusBadge = $('#order-status-badge');
                statusBadge.text(order.order_status);
                if (order.order_status === 'Paid') {
                    statusBadge.attr('class', 'px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300');
                } else {
                    statusBadge.attr('class', 'px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300');
                }

                $('#summary-subtotal').text(`$${parseFloat(order.sub_total).toFixed(2)}`);
                $('#summary-discount').text(`-$${parseFloat(order.discount || 0).toFixed(2)}`);
                $('#summary-grandtotal').text(`$${parseFloat(order.total).toFixed(2)}`);
                $('#summary-payment-method').text(order.payment_status);

                let detailsHtml = '';
                if (orderDetails && orderDetails.length > 0) {
                    orderDetails.forEach(item => {
                        detailsHtml += `<tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="p-4"><div class="flex items-center gap-4"><img src="${assetBaseUrl}${item.product.product_image}" alt="${item.product.product_name}" class="w-12 h-12 object-cover rounded-lg"><div class="flex-grow"><div class="font-semibold text-gray-800 dark:text-white">${item.product.product_name}</div><div class="text-xs text-gray-500">${item.product.product_code}</div></div></div></td>
                            <td class="p-4 text-center">${item.quantity}</td>
                            <td class="p-4 text-right">$${parseFloat(item.unitcost).toFixed(2)}</td>
                            <td class="p-4 text-right font-medium">$${parseFloat(item.total).toFixed(2)}</td>
                        </tr>`;
                    });
                } else {
                    detailsHtml = '<tr><td colspan="4" class="text-center p-6 text-slate-500">No items found.</td></tr>';
                }
                $('#modal-table-body').html(detailsHtml);
            },
            error: function() { $('#modal-table-body').html('<tr><td colspan="4" class="text-center text-red-500 p-6">Failed to load details.</td></tr>');}
        });
    });

    const closeModal = () => $('#orderDetailsModal').addClass('hidden');
    $(document).on('click', '#closeModalBtn', closeModal);
    $(document).on('click', '#orderDetailsModal', function(e) { if ($(e.target).is('#orderDetailsModal')) closeModal(); });
    $(document).on('keydown', function(e) { if (e.key === "Escape" && !$('#orderDetailsModal').hasClass('hidden')) closeModal(); });
    
    // Print logic for modal
    $(document).on('click', '#printInvoiceBtn', function() {
        const printContent = document.getElementById('invoice-content').innerHTML;
        const originalContent = document.body.innerHTML;
        document.body.innerHTML = printContent;
        window.print();
        document.body.innerHTML = originalContent;
        // Re-initialize the scripts because we replaced the body's content
        location.reload(); 
    });


    // --- INITIAL LOAD ---
    $('#reportTab .tab-button').first().trigger('click');
});
</script>