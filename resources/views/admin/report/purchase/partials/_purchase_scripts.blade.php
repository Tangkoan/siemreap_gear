<script>
$(document).ready(function() {
    let searchTimeout;
    const activeTabClasses = 'bg-cyan-600 text-white';
    const inactiveTabClasses = 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700';

    function showLoading(tableBody) {
        tableBody.html(`<tr><td colspan="7" class="text-center p-8"><div class="flex justify-center items-center gap-2 text-slate-500"><svg class="animate-spin h-5 w-5 text-cyan-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>Loading...</span></div></td></tr>`);
    }

    function updateKPIs(period, kpis) {
        if(kpis){
            $(`#kpi-spending-${period}`).text(kpis.spending);
            $(`#kpi-purchases-${period}`).text(kpis.purchases);
            $(`#kpi-items-${period}`).text(kpis.items);
            $(`#kpi-avg-${period}`).text(kpis.avg);
        }
    }
    
    const fetchData = (period, page = 1) => {
        let url, data = { page: page };
        const ids = {day: '#date-day', month: '#month-month', year: '#year-year'};
        const searchIds = {day: '#search-purchase-day', month: '#search-purchase-month', year: '#search-purchase-year'};
        const routes = {day: "{{ route('report.purchases.by_date') }}", month: "{{ route('report.purchases.by_month') }}", year: "{{ route('report.purchases.by_year') }}"};
        
        url = routes[period];
        data[period] = $(ids[period]).val();
        data.search = $(searchIds[period]).val();

        showLoading($(`#report-table-body-${period}`));

        $.ajax({
            url: url, type: 'GET', data: data,
            success: function(response) {
                $(`#report-table-body-${period}`).html(response.table);
                $(`#report-table-footer-${period}`).html(response.footer);
                $(`#pagination-links-${period}`).html(response.pagination);
                updateKPIs(period, response.kpis);
            },
            error: function() { $(`#report-table-body-${period}`).html(`<tr><td colspan="7" class="text-center p-8 text-red-500">Failed to load data.</td></tr>`);}
        });
    }

    $('#reportTab .tab-button').on('click', function() {
        const target = $(this).data('tab-target');
        $('#reportTab .tab-button').removeClass(activeTabClasses).addClass(inactiveTabClasses);
        $(this).removeClass(inactiveTabClasses).addClass(activeTabClasses);
        $('.tab-pane').addClass('hidden');
        $(target).removeClass('hidden');
        const period = target.replace(/#|-tab-content/g, '');
        if (!$(this).data('loaded')) { fetchData(period); $(this).data('loaded', true); }
    });

    ['day', 'month', 'year'].forEach(p => {
        $(`#date-${p}, #month-${p}, #year-${p}`).on('change', () => fetchData(p, 1));
        $(`#search-purchase-${p}`).on('keyup', () => { clearTimeout(searchTimeout); searchTimeout = setTimeout(() => fetchData(p, 1), 500); });
        $(document).on('click', `#pagination-links-${p} .pagination a`, function(e) { e.preventDefault(); fetchData(p, $(this).attr('href').split('page=')[1]); });
    });

    $(document).on('click', '.view-details-btn', function() {
        $.ajax({
            url: "{{ route('report.purchases.details') }}", data: { purchase_id: $(this).data('purchase-id') },
            success: function(response) {
                const { purchase, purchaseDetails } = response;
                $('#purchase-invoice-no').text(purchase.invoice_no);
                $('#purchase-date').text(new Date(purchase.purchase_date).toLocaleDateString('en-GB'));
                $('#supplier-name').text(purchase.supplier.name);
                $('#supplier-phone').text(purchase.supplier.phone);
                $('#purchase-status-badge').text(purchase.payment_status).attr('class', purchase.payment_status === 'Paid' ? 'badge-success' : 'badge-danger');
                $('#summary-subtotal').text(`$${parseFloat(purchase.sub_total || 0).toFixed(2)}`);
                $('#summary-discount').text(`-$${parseFloat(purchase.discount || 0).toFixed(2)}`);
                $('#summary-shipping').text(`$${parseFloat(purchase.shipping || 0).toFixed(2)}`);
                $('#summary-grandtotal').text(`$${parseFloat(purchase.total).toFixed(2)}`);

                let detailsHtml = purchaseDetails?.length ? '' : '<tr><td colspan="4" class="text-center p-6">No items found.</td></tr>';
                purchaseDetails?.forEach(item => {
                    detailsHtml += `<tr><td class="p-3"><div class="flex items-center gap-3"><img src="{{ asset('') }}/${item.product.product_image}" class="w-10 h-10 object-cover rounded"><div class="font-semibold">${item.product.product_name}</div></div></td><td class="p-3 text-center">${item.quantity}</td><td class="p-3 text-right">$${parseFloat(item.purchase_price).toFixed(2)}</td><td class="p-3 text-right font-medium">$${parseFloat(item.total).toFixed(2)}</td></tr>`;
                });
                $('#modal-purchase-table-body').html(detailsHtml);
                $('#purchaseDetailsModal').removeClass('hidden');
            }
        });
    });

    const closeModal = () => $('#purchaseDetailsModal').addClass('hidden');
    
    $('#closePurchaseModalBtn, #purchaseDetailsModal').on('click', function(e) { if (this === e.target) closeModal(); });
    $(document).on('keydown', e => e.key === "Escape" ? closeModal() : '');
    $('#printPurchaseBtn').on('click', () => {
        const content = document.getElementById('purchase-voucher-content').innerHTML;
        const newWindow = window.open('', 'Print', 'height=600,width=800');
        newWindow.document.write('<html><head><title>Print Voucher</title>');
        newWindow.document.write('<link href="{{ asset('css/app.css') }}" rel="stylesheet">'); // Link to your compiled Tailwind CSS
        newWindow.document.write('<style>body{padding: 2rem;}</style>');
        newWindow.document.write('</head><body>');
        newWindow.document.write(content);
        newWindow.document.write('</body></html>');
        newWindow.document.close();
        newWindow.focus();
        setTimeout(() => newWindow.print(), 500);
    });

    $('#reportTab .tab-button').first().trigger('click');
});
</script>

<style>
.form-input { @apply h-10 border dark:bg-slate-700 dark:text-white border-slate-300 dark:border-slate-600 rounded-lg text-sm w-full focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500; }
.badge-success { @apply inline-block px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300; }
.badge-danger { @apply inline-block px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300; }
</style>