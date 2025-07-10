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




Route::get('/', function () {
    // return view('welcome');
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
    Route::get('/admin/profile', [AdminController::class, 'AdminProfile'])->name('admin.admin_profile_view');
    Route::post('/admin/profile/store', [AdminController::class, 'AdminProfileStore'])->name('admin.profile.store');
    Route::get('/change/password', [AdminController::class, 'ChangePassword'])->name('change.password');
    Route::post('/update/password', [AdminController::class, 'UpdatePassword'])->name('update.password');

    Route::get('/customer/page', [CustomerController::class, 'CustomerPage'])->name('customer.all')->middleware('permission:customer.all');

    // Report Route
    Route::controller(ReportController::class)->group(function () {
        Route::get('/all/reports', 'AllReports')->name('all.reports');
        Route::get('/report/orders/details', 'getOrderDetails')->name('report.orders.details');


        Route::match(['get', 'post'], '/report/orders/by-date', 'orderReportByDate')->name('report.orders.by_date');
        Route::get('/report/orders/by-date/export', [ReportController::class, 'exportOrderByDate'])->name('report.orders.export.date');
        Route::get('/report/orders/details-modal/{id}', 'getOrderDetailsForModal')->name('report.orders.details_modal');

        // ផ្លាស់ប្តូរ Route ចាស់របស់អ្នក ឬបន្ថែម Route ថ្មីទាំងពីរនេះ
        Route::match(['get', 'post'], '/report/orders/by-month', 'orderReportByMonth')->name('report.orders.by_month');
        Route::get('/report/orders/by-month/export',  'exportOrderByMonth')->name('report.orders.export.month');

        // Year Order Report
        Route::match(['get', 'post'], '/report/orders/by-year', 'orderReportByYear')->name('report.orders.by_year');
        Route::get('/report/orders/by-year/export', 'exportOrderByYear')->name('report.orders.export.year');
      
        //  Export Sale(Order Report)
        Route::get('/report-order/export', 'SaleReportExport')->name('sale.report.export'); //->middleware('permission:report.order.export')

        // Report Stock
        Route::get('/all/stock/reports', 'AllStockReports')->name('all.report.stock');

        Route::match(['get', 'post'], '/report/stock-by-day', 'stockReportByDay')->name('report.stock.by_day');
        Route::get('/report/stock/details/day', 'getStockMovementDetailsByDay')->name('report.stock.details.day');
        Route::get('/report/stock/by-day/export',  'exportStockByDay')->name('report.stock.export.day');

        Route::match(['get', 'post'], '/report/stock/by-month', 'stockReportByMonth')->name('report.stock.by_month');
        Route::get('/report/stock/details/month', 'getStockMovementDetailsByMonth')->name('report.stock.details.month');
        Route::get('/report/stock/by-month/export', 'exportStockByMonth')->name('report.stock.export.month');


        Route::match(['get', 'post'], '/report/stock/by-year', 'stockReportByYear')->name('report.stock.by_year');
        Route::match(['get', 'post'], '/report/stock/details', 'getStockMovementDetails')->name('report.stock.details');
        Route::get('/report/stock/by-year/export',  'exportStockByYear')->name('report.stock.export.year');

        // End Report Stock

        // Purchase Report

        // --- Routes for Unified Purchase Report ---
        Route::get('/report/purchases/unified', 'showUnifiedPurchaseReport')->name('report.purchases.unified');
        Route::get('/report/purchases/by-date', 'purchaseReportByDate')->name('report.purchases.by_date');
        Route::get('/report/purchases/by-month','purchaseReportByMonth')->name('report.purchases.by_month');
        Route::get('/report/purchases/by-year',  'purchaseReportByYear')->name('report.purchases.by_year');
        Route::get('/report/purchases/details-modal/{id}',  'getPurchaseDetailsForModal')->name('report.purchases.details_modal');
        Route::get('/report/purchases/by-date/export',  'exportPurchaseByDate')->name('report.purchases.export.date');
        Route::get('/report/purchases/by-month/export',  'exportPurchaseByMonth')->name('report.purchases.export.month');
        Route::get('/report/purchases/by-year/export',  'exportPurchaseByYear')->name('report.purchases.export.year');
    });// End Report Route
    


    // Start Product
    Route::controller(ProductController::class)->group(function () {


        // Notification Stock Alert API
        Route::get('/get-stock-alerts',  'getStockAlerts')->name('stock.alerts');
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

        Route::get('/delete/category/{id}', 'DeleteCategory')->name('delete.category')->middleware('permission:category.delete');
    });/// End Category Route
    







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
        Route::get('/all/customer', 'CustomerPage')->name('all.customer')->middleware('permission:customer.all');
        Route::get('/add/customer', 'AddCustomer')->name('add.customer')->middleware('permission:customer.add');
        Route::post('/store/customer', 'StoreCustomer')->name('customer.store');


        Route::get('/edit/customer/{id}', 'EditCustomer')->name('edit.customer')->middleware('permission:customer.edit');
        Route::post('/customer/update', 'CustomerUpdate')->name('customer.update');

        Route::get('/delete/customer/{id}', 'DeleteCustomer')->name('delete.customer')->middleware('permission:customer.delete');
    });// End
    


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

        Route::post('/final-invoice', 'FinalInvoice')->middleware('permission:order.menu');
        Route::get('/pending/order', 'PendingOrder')->name('pending.order')->middleware('permission:order.pending');
        
        Route::get('/order/details/{order_id}', 'OrderDetails')->name('order.details');

        // សម្រាប់ View Details ដែលមិនមាន​Button Compleate Order
        Route::get('/order/details/due/{order_id}', 'OrderDetailsDue')->name('order.details.due');

        Route::post('/order/status/update', 'OrderStatusUpdate')->name('order.status.update');

        Route::get('/complete/order', 'CompleteOrder')->name('complete.order')->middleware('permission:order.complete');

        // Stock
        Route::get('/stock', 'StockManage')->name('all.stock')->middleware('permission:stock.menu');



        // PDF Complete Order
        Route::get('/order/invoice-download/{order_id}', 'OrderInvoice');



        //// Due All Route Add commentMore actions
        Route::get('/order/pending/due', 'PendingDue')->name('pending.due');
        // Route::get('/order/due/{id}','OrderDueAjax');
        Route::get('/order/due/{id}', 'getDue'); // ← This will never be reached!
        Route::get('/order/paydue/{id}', 'payDueModel')->name('order.paydue.due');

        Route::post('/update/due', 'UpdateDue')->name('update.due');
    });



    ///Purchase All Route Add commentMore actions

    Route::controller(PurchaseController::class)->group(function () {

        Route::post('/final-invoice', 'FinalInvoice')->middleware('permission:purchase.menu');

        Route::get('/pending/purchase', 'PendingPurchase')->name('pending.purchase')->middleware('permission:purchase.pending');

        Route::get('/purchase/details/{purchase_id}', 'PurchaseDetails')->name('purchase.details');

        // View Details for purchase page Don't have button complete purhcase
        Route::get('/purchase/view/details/{purchase_id}', 'PurchaseViewDetails')->name('purchase.view.details');


        Route::post('/purchase/status/update', 'PurchaserStatusUpdate')->name('purchase.status.update');

        Route::get('/complete/purchase', 'CompletePurchase')->name('complete.purchase')->middleware('permission:purchase.complete');

        // PDF Complete Purchase
        Route::get('/purchase/invoice-download/{order_id}', 'PurchaseInvoice');



        //// Due All Route Add commentMore actions
        Route::get('/purchase/pending/due', 'PendingDue')->name('purchase.pending.due');
        // Route::get('/order/due/{id}','OrderDueAjax');
        Route::get('/purchase/due/{id}', 'getDue'); // ← This will never be reached!
        Route::get('/purchasesearch.purchase/paydue/{id}', 'payDueModel')->name('paydue.due');

        Route::post('/purchase/update/due', 'PurchaseUpdateDue')->name('purchase.update.due');


        // New Route
        // Route::get('/add/purchase', 'AddPurchase')->name('add.purchase'); 
    });

    //
    Route::controller(PurchaseController::class)->group(function () {
        Route::get('/add/purchase', 'PurchasePage')->name('purchase.page');
        Route::get('/purchase/product/search', 'PurchaseProductSearch')->name('purchase.product.search'); 



        Route::get('/api/purchase/products', 'getProductsForPurchase')->name('api.purchase.products');
        Route::post('/purchase/add-to-cart', 'AddToCart');

        Route::post('/purchase/store', 'StorePurchase')->name('purchase.store');

        // Route::get('/purchase/cart/remove/{rowId}', 'RemoveCartItem');
        // Route::post('/purchase/cart/update/{rowId}', 'UpdateCartItem');

        Route::post('/purchase/add-cart', [PurchaseController::class, 'AddToCart']);
        Route::post('/purchase/cart/update/{rowId}', [PurchaseController::class, 'UpdateCartItem']);
        Route::get('/purchase/cart/remove/{rowId}', [PurchaseController::class, 'RemoveCartItem']);
    });
    

    // Start Permision
    Route::controller(RoleController::class)->group(function () {


        Route::get('/all/permission', 'AllPermission')->name('all.permission')->middleware('permission:permission.menu'); //
        Route::get('/add/permission', 'AddPermission')->name('add.permission')->middleware('permission:permission.menu');
        Route::post('/store/permission', 'StorePermission')->name('permission.store')->middleware('permission:permission.menu');

        Route::get('/edit/permission/{id}', 'EditPermission')->name('edit.permission')->middleware('permission:permission.menu');

        Route::post('/update/permission', 'UpdatePermission')->name('permission.update')->middleware('permission:permission.menu');
        Route::get('/delete/permission/{id}', 'DeletePermission')->name('delete.permission')->middleware('permission:permission.menu');

        /// ROLE
        Route::get('/all/roles', 'AllRoles')->name('all.roles')->middleware('permission:permission.menu');
        Route::get('/add/roles', 'AddRoles')->name('add.roles')->middleware('permission:permission.menu');
        Route::post('/store/roles', 'StoreRoles')->name('roles.store')->middleware('permission:permission.menu');

        Route::get('/edit/roles/{id}', 'EditRoles')->name('edit.roles')->middleware('permission:permission.menu');
        Route::post('/update/roles', 'UpdateRoles')->name('roles.update')->middleware('permission:permission.menu');
        Route::get('/delete/roles/{id}', 'DeleteRoles')->name('delete.roles')->middleware('permission:permission.menu');

        // Role Permission
        Route::get('/add/roles/permission', 'AddRolesPermission')->name('add.roles.permission')->middleware('permission:permission.menu');
        Route::post('/role/permission/store', 'StoreRolesPermission')->name('role.permission.store')->middleware('permission:permission.menu');
        Route::get('/all/roles/permission', 'AllRolesPermission')->name('all.roles.permission')->middleware('permission:permission.menu');
        Route::get('/admin/edit/roles/{id}', 'AdminEditRoles')->name('admin.edit.roles')->middleware('permission:permission.menu');
        Route::post('/role/permission/update/{id}', 'RolePermissionUpdate')->name('role.permission.update')->middleware('permission:permission.menu');
        Route::get('/admin/delete/roles/{id}', 'AdminDeleteRoles')->name('admin.delete.roles')->middleware('permission:permission.menu');

        // 

    });
    // End Permision



    ///Admin User All Route 

    Route::controller(AdminController::class)->group(function () {

        Route::get('/all/admin', 'AllAdmin')->name('all.admin')->middleware('permission:user.menu');
        Route::get('/add/admin', 'AddAdmin')->name('add.admin')->middleware('permission:user.add');
        Route::post('/store/admin', 'StoreAdmin')->name('admin.store');
        Route::get('/edit/admin/{id}', 'EditAdmin')->name('edit.admin')->middleware('permission:user.edit');
        Route::post('/update/admin', 'UpdateAdmin')->name('admin.update');
        Route::get('/delete/admin/{id}', 'DeleteAdmin')->name('delete.admin')->middleware('permission:user.delete');



        // backup
        Route::get('/backup/now', 'BackupNow');
        Route::get('/delete/database/{getFilename}', 'DeleteDatabase');
    });


    Route::get('/search-category', [CategoryController::class, 'searchCategory'])->name('search.category');
    Route::get('/search-supplier', [SupplierController::class, 'searchSupplier'])->name('search.supplier');

    Route::get('/search-product', [ProductController::class, 'searchProduct'])->name('search.product');
    Route::get('/search-pos-products', [PosController::class, 'searchProducts']);


    Route::get('/search-customer', [CustomerController::class, 'searchCustomer'])->name('search.customer');



    // Expense
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


    // Route::post('/add-cart', [PosController::class, 'AddCart']);
    // Route::get('/all-cart-items', [PosController::class, 'AllItem']);
    // Route::get('/cart-remove/{id}', [PosController::class, 'CartRemove']);

    Route::post('/add-cart', [PosController::class, 'AddCart']);
    Route::post('/cart-update/{rowId}', [PosController::class, 'CartUpdate']);
    Route::get('/cart-remove/{rowId}', [PosController::class, 'CartRemove']);

    // End POS
    ///POS All Route 
    Route::controller(PosController::class)->group(function () {

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
    });
    // End

    // Permission
    Route::get('/search-permission', [RoleController::class, 'searchPermission'])->name('search.permission');
    Route::get('/search-roles', [RoleController::class, 'searchRoles'])->name('search.roles');

    Route::get('/search-roles-permission', [RoleController::class, 'searchRolesPermission'])->name('search.roles.permission');


    // Admin Role ACC
    Route::get('/search-admin', [AdminController::class, 'searchAdmin'])->name('search.admin');

    // Admin Role ACC
    Route::get('/search-backup', [AdminController::class, 'searchBackup'])->name('search.backup');
    Route::get('/admin/backup', [AdminController::class, 'DatabaseBackup'])->name('admin.backup');

    Route::get('/backup/download/{getFilename}', [AdminController::class, 'DownloadDatabase'])->name('backup.download');
    Route::get('/backup/delete/{getFilename}', [AdminController::class, 'DeleteBackup'])->name('backup.delete');
}); // End User Middleware





Route::post('/store-sell', [POSController::class, 'FinalInvoice'])->name('store.sell');
Route::get('/print-invoice/{id}', [POSController::class, 'PrintInvoice'])->name('print.invoice');



