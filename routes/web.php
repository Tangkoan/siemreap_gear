<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\Backend\ExpenseController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ConditionController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\Backend\ExchangeRateController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\AppearanceController;

use App\Http\Controllers\DatabaseImportController;


Route::get('language/{locale}', function ($locale) {
    app()->setLocale($locale);
    session()->put('locale', $locale);
    return redirect()->back();
})->name('language.switch');

// Route::get('/', function () {
//     // return view('welcome');
//     return view('auth/login');
// });

Route::get('/', function () {
    // ត្រួតពិនិត្យប្រសិនបើអ្នកប្រើប្រាស់បាន Login រួចហើយ
    if (Auth::check()) {
        // បញ្ជូនបន្តទៅកាន់ Route ឈ្មោះ 'dashboard'
        return redirect('/dashboard');
    }
    // ប្រសិនបើមិនទាន់ Login បង្ហាញទំព័រ Login
    return view('auth/login');
});

Route::get('/dashboard', function () {
    return view('admin/index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

// ចំណុចចាប់ផ្ដើមបន្ថែម web ថ្មី ដែលក្នុងLaravel គេតែងហៅថា Route បើនិយាយទៅប្រៀបដូច URL ផងដែរ ដោយសារយើងសរសេរវាជា (/) ដើម្បីជាផ្លូវពីកន្ឡែងនេះទៅកន្លែងថ្មី
Route::get('/admin/logout', [AdminController::class, 'AdminDestroy'])->name('admin.logout');

//Admin
Route::middleware(['auth'])->group(callback: function () {

    //
    Route::post('/appearance/update', [AppearanceController::class, 'update'])->name('appearance.update');


    // Import Database
        Route::get('/database/import', [DatabaseImportController::class, 'showForm'])->name('db.import.form');
        Route::post('/database/import', [DatabaseImportController::class, 'handleImport'])->name('db.import.handle');
    // End Import Database

    // =================== Stock =====================

    // ✅ NEW Route សម្រាប់ទាញយកទិន្នន័យលក់/ទិញ
    Route::get('/stock/get-return-details', [StockController::class, 'getReturnDetails'])->name('stock.get_return_details');
    Route::post('/stock/adjust', [StockController::class, 'adjustStock'])->name('stock.adjust');

    Route::get('/stock', [StockController::class, 'searchStock'])->name('search.stock');
    Route::get('/all/stock', [StockController::class, 'StockPage'])->name('all.stock')->middleware('permission:stock.menu');


    // Route::get('/stock', 'StockManage')->name('all.stock')->middleware('permission:stock.menu');
    
    // ==================== Dashboard ==================== 
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Route AJAX
    Route::get('/dashboard/data', [DashboardController::class, 'getData'])->name('dashboard.data');
    // End
    Route::get('/admin/profile', [AdminController::class, 'AdminProfile'])->name('admin.admin_profile_view');
    Route::post('/admin/profile/store', [AdminController::class, 'AdminProfileStore'])->name('admin.profile.store');
    Route::get('/change/password', [AdminController::class, 'ChangePassword'])->name('change.password');
    Route::post('/update/password', [AdminController::class, 'UpdatePassword'])->name('update.password');


    // ================================ Exchange Rate ==========================================
        Route::resource('exchange-rates', ExchangeRateController::class)->except(['show', 'edit', 'update']);
    // ============================= Condition ==================================================
        Route::controller(ConditionController::class)->group(function () {
            Route::get('/all/condition', 'AllCondition')->name('all.condition')->middleware('permission:condition.all');

            Route::get('/add/condition', 'AddCondition')->name('add.condition')->middleware('permission:condition.add');
            Route::post('/store/condition', 'StoreCondition')->name('condition.store');



            Route::get('/edit/condition/{id}', 'EditCondition')->name('edit.condition')->middleware('permission:condition.edit');
            Route::post('/condition/update', 'ConditionUpdate')->name('condition.update');

            Route::get('/delete/condition/{id}', 'DeleteCondition')->name('delete.condition')->middleware('permission:condition.delete');

            Route::get('/search-condition',  'searchCondition')->name('search.condition');
        }); /// End 

    // ============================= Report Route ==================================================
        Route::controller(ReportController::class)->group(function () {
            Route::get('/all/reports', 'AllReports')->name('all.reports');
            Route::get('/report/orders/details', 'getOrderDetails')->name('report.orders.details');
            Route::match(['get', 'post'], '/report/orders/by-date', 'orderReportByDate')->name('report.orders.by_date');
            Route::get('/report/orders/by-date/export', [ReportController::class, 'exportOrderByDate'])->name('report.orders.export.date');
            // Route::get('/report/orders/details-modal/{id}', 'getOrderDetailsForModal')->name('report.orders.details_modal');

            // ផ្លាស់ប្តូរ Route ចាស់របស់អ្នក ឬបន្ថែម Route ថ្មីទាំងពីរនេះ
            Route::match(['get', 'post'], '/report/orders/by-month', 'orderReportByMonth')->name('report.orders.by_month');
            Route::get('/report/orders/by-month/export',  'exportOrderByMonth')->name('report.orders.export.month');

            // Year Order Report
            Route::match(['get', 'post'], '/report/orders/by-year', 'orderReportByYear')->name('report.orders.by_year');
            Route::get('/report/orders/by-year/export', 'exportOrderByYear')->name('report.orders.export.year');

            //  Export Sale(Order Report)
            Route::get('/report-order/export', 'SaleReportExport')->name('sale.report.export'); //->middleware('permission:report.order.export')

            // ============================= Report Stock ==================================================
            Route::get('/stock/report', 'AllStockReports')->name('all.report.stock');

            // By Day
            Route::get('/stock/report/by-day', 'stockReportByDay')->name('report.stock.by_day');
            Route::get('/stock/report/export/by-day', 'exportStockByDay')->name('report.stock.export.day');

            // By Month
            Route::get('/stock/report/by-month', 'stockReportByMonth')->name('report.stock.by_month');
            Route::get('/stock/report/export/by-month', 'exportStockByMonth')->name('report.stock.export.month');

            // By Year
            Route::get('/stock/report/by-year', 'stockReportByYear')->name('report.stock.by_year');
            Route::get('/stock/report/export/by-year', 'exportStockByYear')->name('report.stock.export.year');

            // Route សម្រាប់ទាញយកទិន្នន័យលម្អិត (Details)
            Route::get('/report/stock/details',  'getStockMovementDetails')->name('report.stock.details');
            // End Report Stock

            // ​===================================== Purchase Report ===============================================
            // --- Routes for Unified Purchase Report ---
            // Main view route
            Route::get('/report/purchases', 'purchaseReportView')->name('report.purchases.view');

            // AJAX routes for fetching data by date, month, year
            Route::get('/report/purchases/by-date', 'getPurchaseReportByDate')->name('report.purchases.by_date');
            Route::get('/report/purchases/by-month', 'getPurchaseReportByMonth')->name('report.purchases.by_month');
            Route::get('/report/purchases/by-year', 'getPurchaseReportByYear')->name('report.purchases.by_year');

            // AJAX route for fetching purchase details for the modal
            Route::get('/report/purchases/details', 'getPurchaseDetails')->name('report.purchases.details');

            // =================== Purchase Report Export Routes ===================
            // Route::get('/report/purchases/export/date', 'exportPurchasesByDate')->name('report.purchases.export.date');
            // Route::get('/report/purchases/export/month', 'exportPurchasesByMonth')->name('report.purchases.export.month');
            // Route::get('/report/purchases/export/year', 'exportPurchasesByYear')->name('report.purchases.export.year');
            Route::get('/purchase/report/view', 'purchaseReportView')->name('purchase.report.view');

            // AJAX Routes for fetching data
            Route::get('/report/purchases/by-date', 'getPurchaseReportByDate')->name('report.purchases.by_date');
            Route::get('/report/purchases/by-month', 'getPurchaseReportByMonth')->name('report.purchases.by_month');
            Route::get('/report/purchases/by-year', 'getPurchaseReportByYear')->name('report.purchases.by_year');
            Route::get('/report/purchases/details', 'getPurchaseDetails')->name('report.purchases.details');

            // Export Routes
            Route::get('/report/purchases/export/date', 'exportPurchasesByDate')->name('report.purchases.export.date');
            Route::get('/report/purchases/export/month', 'exportPurchasesByMonth')->name('report.purchases.export.month');
            Route::get('/report/purchases/export/year', 'exportPurchasesByYear')->name('report.purchases.export.year');

            // =================== Income Expense Report Routes =====================
            Route::get('/report/income-expense', 'incomeExpenseReportView')->name('report.income_expense.view');
            Route::get('/report/income-expense/data', 'getIncomeExpenseData')->name('report.income_expense.data');
            Route::get('/report/export',  'exportReport')->name('report.export');
            Route::get('/report/income-expense/export', 'exportIncomeExpense')->name('report.income_expense.export');
            Route::get('/report/income-expense/export-pdf', 'exportIncomeExpensePdf')->name('report.income_expense.export_pdf');
        }); // End Report Route



    // Start Product
    Route::controller(ProductController::class)->group(function () {

        Route::post('/product/update-status',  'updateProductStatus')->name('product.update.status');

        // Notification Stock Alert API
        Route::get('/get-stock-alerts',  'getStockAlerts')->name('stock.alerts');
        // API
        Route::get('/get-product-details/{id}', 'getProductDetails')->name('get.product.details');

        // End

        Route::get('/product/page', 'ProductPage')->name('all.product')->middleware('permission:product.all');

        Route::get('/product/details/{id}', 'DetailProduct')->name('detail.product')->middleware('permission:product.details');

        Route::get('/product/barcode/{id}', 'BarcodeProduct')->name('barcode.product')->middleware('permission:product.barcode');

        // Route::get('/all/product','ProductPage')->name('all.product');
        Route::get('/add/product', 'AddProduct')->name('add.product')->middleware('permission:product.add');
        // Route::get('/add/employee','AddEmployee')->name('add.employee');

        Route::post('/store/product', 'StoreProduct')->name('product.store');

        Route::get('/edit/product/{id}', 'EditProduct')->name('edit.product')->middleware('permission:product.edit');
        Route::post('/update/product', 'UpdateProduct')->name('product.update');

        Route::get('/delete/product/{id}', 'DeleteProduct')->name('delete.product')->middleware('permission:product.delete');
        // សម្រាប់ Delete (Method គឺ Post តែយើងប្រើ JSនោះទេអ្នកជំនួយក្នុងការDelete)



        // Import Export Product
        Route::get('/import/product', 'ImportProduct')->name('import.product')->middleware('permission:product.import');
        Route::get('/export', 'Export')->name('export')->middleware('permission:product.export');

        Route::post('/import', 'Import')->name('import')->middleware('permission:product.import');
    });
    // End Product

    // Category All Route 

    Route::controller(CategoryController::class)->group(function () {
        Route::get('/all/category', 'AllCategory')->name('all.category')->middleware('permission:category.all');
        Route::get('/add/category', 'AddCategory')->name('add.category')->middleware('permission:category.add');
        Route::post('/store/category', 'StoreCategory')->name('category.store');

        Route::get('/edit/category/{id}', 'EditCategory')->name('edit.category')->middleware('permission:category.edit');
        Route::post('/category/update', 'CategoryUpdate')->name('category.update');
        // Dlete ប្រើ Ajax
        Route::delete('/category/ajax-delete/{id}', [CategoryController::class, 'ajaxDelete'])->name('ajax.delete.category');


        Route::get('/delete/category/{id}', 'DeleteCategory')->name('delete.category')->middleware('permission:category.delete');
    }); /// End Category Route

    // supplier All Route 
    Route::controller(SupplierController::class)->group(function () {
        Route::get('/all/supplier', 'SupplierPage')->name('all.supplier')->middleware('permission:supplier.all');
        Route::get('/add/supplier', 'AddSupplier')->name('add.supplier')->middleware('permission:supplier.add');
        Route::post('/store/supplier', 'StoreSupplier')->name('supplier.store');
        Route::get('/edit/supplier/{id}', 'EditSupplier')->name('edit.supplier')->middleware('permission:supplier.edit');
        Route::post('/supplier/update', 'SupplierUpdate')->name('supplier.update');

        Route::get('/delete/supplier/{id}', 'DeleteSupplier')->name('delete.supplier')->middleware('permission:supplier.delete');
    });
    // End



    // Customer All Route 
    Route::controller(CustomerController::class)->group(function () {
        Route::get('/customer/page', action: 'CustomerPage')->name('customer.all')->middleware('permission:customer.all'); // ទាញមកបង្ហាញ
        Route::get('/all/customer', 'CustomerPage')->name('all.customer')->middleware('permission:customer.all'); // For Insert Data
        Route::get('/add/customer', 'AddCustomer')->name('add.customer')->middleware('permission:customer.add');
        Route::post('/store/customer', 'StoreCustomer')->name('customer.store');


        Route::get('/edit/customer/{id}', 'EditCustomer')->name('edit.customer')->middleware('permission:customer.edit');
        Route::post('/customer/update', 'CustomerUpdate')->name('customer.update');

        Route::get('/delete/customer/{id}', 'DeleteCustomer')->name('delete.customer')->middleware('permission:customer.delete');
    }); // End



    ///Expense All Route 
    Route::controller(ExpenseController::class)->group(function () {

        Route::get('/add/expense', 'AddExpense')->name('add.expense')->middleware('permission:expense.add');
        Route::post('/store/expense', 'StoreExpense')->name('expense.store');
        Route::get('/today/expense', 'TodayExpense')->name('today.expense')->middleware('permission:expense.today');


        Route::get('/edit/expense/{id}', 'EditExpense')->name('edit.expense')->middleware('permission:expense.edit');
        Route::post('/update/expense', 'UpdateExpense')->name('expense.update');

        Route::get('/month/expense', 'MonthExpense')->name('month.expense')->middleware('permission:expense.month');
        Route::get('/year/expense', 'YearExpense')->name('year.expense')->middleware('permission:expense.year');
    });
    // End

    //Order All Route Add commentMore actions

    Route::controller(OrderController::class)->group(function () {
        // API 
        Route::get('/get-invoice-html/{id}',  'getInvoiceHtml')->name('get.invoice.html');
        Route::get('/get-complete-invoice-html/{id}',  'getCompleteInvoiceHtml')->name('get.complete.invoice.html');

        // End

        Route::post('/final-invoice', 'FinalInvoice')->middleware('permission:order.menu');
        

        Route::get('/order/details/{order_id}', 'OrderDetails')->name('order.details');

        // សម្រាប់ View Details ដែលមិនមាន​Button Compleate Order
        Route::get('/order/details/due/{order_id}', 'OrderDetailsDue')->name('order.details.due');

        Route::post('/order/status/update', 'OrderStatusUpdate')->name('order.status.update');

        Route::get('/complete/order', 'CompleteOrder')->name('complete.order')->middleware('permission:order.complete');

        // PDF Complete Order
        Route::get('/order/invoice-download/{order_id}', 'OrderInvoice');
        //// Due All Route Add commentMore actions
        // Route::get('/order/pending/due', 'PendingDue')->name('pending.due');
        Route::get('/pending/order', 'PendingOrder')->name('pending.order')->middleware('permission:order.pending');
        // Route::get('/order/due/{id}','OrderDueAjax');
        Route::get('/order/due/{id}', 'getDue'); // ← This will never be reached!
        Route::get('/order/paydue/{id}', 'payDueModel')->name('order.paydue.due');

        Route::post('/update/due', 'UpdateDue')->name('update.due');
    });



    //===================================================== Purchase ==================================================================
        Route::controller(PurchaseController::class)->group(function () {

            // API
            Route::get('/get-products-for-purchase',  'getProductsForPurchase')->name('get.products.for.purchase');

            Route::get('/purchase/details/{purchase_id}', 'PurchaseDetails')->name('purchase.details');
            // View Details for purchase page Don't have button complete purhcase
            Route::get('/purchase/view/details/{purchase_id}', 'PurchaseViewDetails')->name('purchase.view.details');

            Route::get('/complete/purchase', 'CompletePurchase')->name('complete.purchase')->middleware('permission:purchase.complete');
            // PDF Complete Purchase
            Route::get('/purchase/invoice-download/{order_id}', 'PurchaseInvoice');
            //// Due All Route Add commentMore actions          
            Route::get('/purchase/pending/due', 'PendingDue')->name('purchase.pending.due')->middleware('permission:purchase.pending.due');
            Route::get('/purchase/due/{id}', 'getDue'); // ← This will never be reached!
            Route::get('/purchasesearch.purchase/paydue/{id}', 'payDueModel')->name('paydue.due');
            Route::post('/purchase/update/due', 'PurchaseUpdateDue')->name('purchase.update.due');
            Route::post('/purchase/store-supplier', 'storeSupplierAjax')->name('store.supplier.ajax');
            Route::post('/purchase/store-product', 'storeProductAjax')->name('store.product.ajax');
        });
        //
        Route::controller(PurchaseController::class)->group(function () {
            Route::get('/add/purchase', 'PurchasePage')->name('purchase.page')->middleware('permission:purchase.menu');
            Route::get('/purchase/search-products', [PurchaseController::class, 'searchPurchaseProducts'])->name('purchase.search.products');
            Route::get('/api/purchase/products', 'getProductsForPurchase')->name('api.purchase.products');
            Route::post('/purchase/add-to-cart', 'AddToCart');
            Route::post('/purchase/store', 'StorePurchase')->name('purchase.store');
            Route::post('/purchase/add-cart', [PurchaseController::class, 'AddToCart']);
            Route::post('/purchase/cart/update/{rowId}', [PurchaseController::class, 'UpdateCartItem']);
            Route::get('/purchase/cart/remove/{rowId}', [PurchaseController::class, 'RemoveCartItem']);
        });

        // =======================================  Start Permision  ====================================================
        Route::controller(RoleController::class)->group(function () {
            Route::get('/all/permission', 'AllPermission')->name('all.permission')->middleware('permission:permission.menu'); //
            Route::get('/add/permission', 'AddPermission')->name('add.permission')->middleware('permission:permission.menu');
            Route::post('/store/permission', 'StorePermission')->name('permission.store')->middleware('permission:permission.menu');

            Route::get('/edit/permission/{id}', 'EditPermission')->name('edit.permission')->middleware('permission:permission.menu');

            Route::post('/update/permission', 'UpdatePermission')->name('permission.update')->middleware('permission:permission.menu');
            Route::get('/delete/permission/{id}', 'DeletePermission')->name('delete.permission')->middleware('permission:permission.menu');

        // =======================================  ROLE  ====================================================
            Route::get('/all/roles', 'AllRoles')->name('all.roles')->middleware('permission:permission.menu');
            Route::get('/add/roles', 'AddRoles')->name('add.roles')->middleware('permission:permission.menu');
            Route::post('/store/roles', 'StoreRoles')->name('roles.store')->middleware('permission:permission.menu');

            Route::get('/edit/roles/{id}', 'EditRoles')->name('edit.roles')->middleware('permission:permission.menu');
            Route::post('/update/roles', 'UpdateRoles')->name('roles.update')->middleware('permission:permission.menu');
            Route::get('/delete/roles/{id}', 'DeleteRoles')->name('delete.roles')->middleware('permission:permission.menu');

        // =======================================  Role Permission  ====================================================
            Route::get('/add/roles/permission', 'AddRolesPermission')->name('add.roles.permission')->middleware('permission:permission.menu');
            Route::post('/role/permission/store', 'StoreRolesPermission')->name('role.permission.store')->middleware('permission:permission.menu');
            Route::get('/all/roles/permission', 'AllRolesPermission')->name('all.roles.permission')->middleware('permission:permission.menu');
            Route::get('/admin/edit/roles/{id}', 'AdminEditRoles')->name('admin.edit.roles')->middleware('permission:permission.menu');
            Route::post('/role/permission/update/{id}', 'RolePermissionUpdate')->name('role.permission.update')->middleware('permission:permission.menu');
            Route::get('/admin/delete/roles/{id}', 'AdminDeleteRoles')->name('admin.delete.roles')->middleware('permission:permission.menu');
        });
        // End Permision
 
        // =======================================  Admin User All Route  ====================================================
            Route::controller(AdminController::class)->group(function () {

                Route::get('/all/admin', 'AllAdmin')->name('all.admin')->middleware('permission:user.menu');
                Route::get('/add/admin', 'AddAdmin')->name('add.admin')->middleware('permission:user.add');
                Route::post('/store/admin', 'StoreAdmin')->name('admin.store');
                Route::get('/edit/admin/{id}', 'EditAdmin')->name('edit.admin')->middleware('permission:user.edit');
                Route::post('/update/admin', 'UpdateAdmin')->name('admin.update');
                Route::get('/delete/admin/{id}', 'DeleteAdmin')->name('delete.admin')->middleware('permission:user.delete');
                
                // Route សម្រាប់ Dashboard
                Route::get('/admin/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.dashboard');
                
                // ✅ Route ថ្មីសម្រាប់ AJAX Filter
                Route::get('/admin/dashboard/filter-data', [AdminController::class, 'getFilteredDashboardData'])->name('admin.dashboard.filter');
            });


        // =============================== Backup ================================================
            Route::controller(BackupController::class)->group(function () {
                // backup
                Route::get('/backup/now', 'backupNow')->name('admin.backup.now');
                Route::get('/backups/search',  'searchBackups')->name('backup.search');
                Route::get('/backup-status', action: 'getBackupStatus')->name('backup.status');
                Route::get('/delete/database/{getFilename}', 'DeleteDatabase');
                Route::get('/admin/backup',  'DatabaseBackup')->name('admin.backup');
                Route::get('/backup/delete/{getFilename}', 'DeleteBackup')->name('backup.delete');
                Route::get('/backup/download/{getFilename}', 'downloadBackup')->name('backup.download');
            });
        // End

    // =============================== Setting ================================================
        Route::controller(SettingController::class)->group(function () {
            // setting
            Route::get('/setting', 'settingPage')->name('admin.setting');
            // Information Shop
            Route::get('/setting/shop', 'informationShop')->name('admin.setting_infromationshop');
            Route::post('/admin/information-shop/update', 'update')->name('admin.info.update');
        });
        // End



    // ==================================== Backup Project ===========================================
        // ✅ [ថ្មី] Route សម្រាប់ Backup Project
        Route::get('/admin/backup/project', [BackupController::class, 'backupProject'])->name('admin.backup.project');
        Route::get('/project-backup-status', [BackupController::class, 'getProjectBackupStatus'])->name('project.backup.status');
        // --- ✅ [ថ្មី] Project Backup AJAX/Actions ---
        Route::get('/project-backups/search', [BackupController::class, 'searchProjectBackups'])->name('backup.project.search');
        Route::get('/project-backup/download/{filename}', [BackupController::class, 'downloadProjectBackup'])->name('backup.project.download');
        Route::get('/project-backup/delete/{filename}', [BackupController::class, 'deleteProjectBackup'])->name('backup.project.delete');
        // end
        Route::get('/search-category', [CategoryController::class, 'searchCategory'])->name('search.category');
        Route::get('/search-supplier', [SupplierController::class, 'searchSupplier'])->name('search.supplier');
        Route::get('/search-product', [ProductController::class, 'searchProduct'])->name('search.product');
        Route::get('/search-pos-products', [PosController::class, 'searchProducts']);
        Route::get('/search-customer', [CustomerController::class, 'searchCustomer'])->name('search.customer');



        // ==================================== Expense ===========================================
        Route::get('/search-today', [ExpenseController::class, 'searchToday'])->name('search.today');
        Route::get('/search-month', [ExpenseController::class, 'searchMonth'])->name('search.month');
        Route::get('/search-year', [ExpenseController::class, 'searchYear'])->name('search.year');
        Route::get('/search-pending-due', [OrderController::class, 'searchPendingDue'])->name('search.pending_due');
        Route::get('/search-purchase', [PurchaseController::class, 'search']);

        // Order 
        Route::get('/search-order', [OrderController::class, 'searchOrder'])->name('search.order');
        Route::get('/search-comlete-order', [OrderController::class, 'searchCompleteOrder'])->name('search.complete_order');
        // End Order Controler on Page Search

        // Purchase
            Route::get('/search-purchase', [PurchaseController::class, 'searchPurchase'])->name('search.purchase');
            // Update ពី Pending to Complete ទំនិញចូល Stock
            Route::post('/purchase/status/update', [PurchaseController::class, 'PurchaseStatusUpdate'])->name('purchase.status.update');

            Route::get('/search-purchase-complete', [PurchaseController::class, 'searchCompletePurchase'])->name('search.complete_purchase');
            Route::get('/search-purchase-pending-due', [PurchaseController::class, 'searchPendingDue'])->name('search.purchase_pending_due');
        // End Purchase

        // Pos
            Route::get('/get-products', [PosController::class, 'getProductsByCategory']);
            // Route::post('/add-cart', [PosController::class, 'AddCart']);
                Route::post('/add-cart', [PosController::class, 'AddCart']);
                Route::post('/cart-update/{rowId}', [PosController::class, 'CartUpdate']);
                Route::get('/cart-remove/{rowId}', [PosController::class, 'CartRemove']);
            // API Get Exchange Rate
            Route::get('/get-latest-exchange-rate', [PosController::class, 'getLatestExchangeRate'])->name('get.exchange.rate');
            Route::post('/exchange-rate/store', [App\Http\Controllers\PosController::class, 'storeExchangeRate'])->name('exchange-rate.store');
            Route::post('/exchange-rate/auto-fetch', [App\Http\Controllers\PosController::class, 'fetchAndStoreAutoRate'])->name('exchange-rate.auto-fetch');
        // End POS
        ///POS All Route 
            Route::controller(PosController::class)->group(function () {

                // quotation
                Route::post('/generate-quotation-preview',  'generateQuotationPreview')->name('generate.quotation.preview');
                Route::get('/clear-cart-and-redirect-pos',  'clearCartAndRedirect')->name('clear.cart.pos');




                Route::get('/page/pos', 'PosPage')->name('pos')->middleware('permission:pos.menu');
                // Route::post('/add-cart', 'AddCart');
                // Route::get('/allitem', 'AllItem');
                // Route::post('/cart-update/{rowId}', 'CartUpdate');
                // Route::get('/cart-remove/{rowId}', 'CartRemove');

                Route::post('/create-invoice', 'CreateInvoice');


                Route::post('/create-invoice-pos', 'CreateInvoiceVI');

                //
                Route::post('/final-invoice', 'FinalInvoice');
                Route::get('/api/products-for-pos', 'getProductsForPos')->name('api.products.pos');

                // ===== Create Customer in Page POS =====
                Route::post('/pos/store-customer', 'storeCustomerAjax')->name('store.customer.ajax');
            });
            // End

            // Permission
            Route::get('/search-permission', [RoleController::class, 'searchPermission'])->name('search.permission');
            Route::get('/search-roles', [RoleController::class, 'searchRoles'])->name('search.roles');

            Route::get('/search-roles-permission', [RoleController::class, 'searchRolesPermission'])->name('search.roles.permission');
    // Admin Role ACC
    Route::get('/search-admin', [AdminController::class, 'searchAdmin'])->name('search.admin');
}); // End User Middleware


Route::post('/store-sell', [POSController::class, 'FinalInvoice'])->name('store.sell');
Route::get('/print-invoice/{id}', [POSController::class, 'PrintInvoice'])->name('print.invoice');



