<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

use App\Http\Controllers\EmployeeContrlloer;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\Backend\ExpenseController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\RoleController;



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

require __DIR__.'/auth.php';



// ចំណុចចាប់ផ្ដើមបន្ថែម web ថ្មី ដែលក្នុងLaravel គេតែងហៅថា Route បើនិយាយទៅប្រៀបដូច URL ផងដែរ ដោយសារយើងសរសេរវាជា (/) ដើម្បីជាផ្លូវពីកន្ឡែងនេះទៅកន្លែងថ្មី
Route::get('/admin/logout', [AdminController::class, 'AdminDestroy'])->name('admin.logout');

//Admin
Route::middleware(['auth'])->group(callback: function(){
    Route::get('/admin/profile', [AdminController::class, 'AdminProfile'])->name('admin.admin_profile_view');
    Route::post('/admin/profile/store', [AdminController::class, 'AdminProfileStore'])->name('admin.profile.store');
    Route::get('/change/password', [AdminController::class, 'ChangePassword'])->name('change.password');
    Route::post('/update/password', [AdminController::class, 'UpdatePassword'])->name('update.password');


    // Route Employee
    Route::controller(EmployeeContrlloer::class)->group(function(){
        // Route::get('/all/employee','AllEmployee')->name('all.employee');
        Route::get('/add/employee','AddEmployee')->name('add.employee');
        // Route::get('/add/employee','AddEmployee')->name('add.employee');

        Route::post('/store/employee','StoreEmployee')->name('employee.store');

        Route::get('/edit/employee/{id}','EditEmployee')->name('edit.employee'); // គ្រាន់តែចាប់តម្លៃអោយបានថា id = ?
        Route::post('/update/employee','UpdateEmployee')->name('employee.update'); // ធ្វើការUpdate Employee

        Route::get('/delete/employee/{id}','DeleteEmployee')->name('delete.employee');  // សម្រាប់ Delete (Method គឺ Post តែយើងប្រើ JSនោះទេអ្នកជំនួយក្នុងការDelete)
    });
    // End Route Employee


    
    // Route::get('/employee/page', [EmployeeContrlloer::class, 'EmployeePage'])->name('employee.all');

    Route::get('/customer/page', [CustomerController::class, 'CustomerPage'])->name('customer.all');


    // Start Product
    Route::controller(ProductController::class)->group(function(){


        Route::get('/product/page','ProductPage')->name('all.product');

        Route::get('/product/details/{id}','DetailProduct')->name('detail.product');

        Route::get('/product/barcode/{id}','BarcodeProduct')->name('barcode.product');

        // Route::get('/all/product','ProductPage')->name('all.product');
        Route::get('/add/product','AddProduct')->name('add.product');
        // Route::get('/add/employee','AddEmployee')->name('add.employee');

        Route::post('/store/product','StoreProduct')->name('product.store');

        Route::get('/edit/product/{id}','EditProduct')->name('edit.product'); // គ្រាន់តែចាប់តម្លៃអោយបានថា id = ?
        Route::post('/update/product','UpdateProduct')->name('product.update'); // ធ្វើការUpdate Employee

        Route::get('/delete/product/{id}','DeleteProduct')->name('delete.product');  // សម្រាប់ Delete (Method គឺ Post តែយើងប្រើ JSនោះទេអ្នកជំនួយក្នុងការDelete)



        // Import Export Product
        Route::get('/import/product','ImportProduct')->name('import.product');
        Route::get('/export','Export')->name('export');

        Route::post('/import','Import')->name('import');
    });
    // End Product

    


    ///Category All Route 
    Route::controller(CategoryController::class)->group(function(){
        Route::get('/all/category','AllCategory')->name('all.category'); 
        Route::get('/add/category','AddCategory')->name('add.category');
        Route::post('/store/category','StoreCategory')->name('category.store');
        


        Route::get('/edit/category/{id}','EditCategory')->name('edit.category');
        Route::post('/category/update','CategoryUpdate')->name('category.update');

        Route::get('/delete/category/{id}','DeleteCategory')->name('delete.category');
    });
    // End


   




    // supplier All Route 
    Route::controller(SupplierController::class)->group(function(){
        Route::get('/all/supplier','SupplierPage')->name('all.supplier'); 
        Route::get('/add/supplier','AddSupplier')->name('add.supplier');
        Route::post('/store/supplier','StoreSupplier')->name('supplier.store');  


        Route::get('/edit/supplier/{id}','EditSupplier')->name('edit.supplier');
        Route::post('/supplier/update','SupplierUpdate')->name('supplier.update');

        Route::get('/delete/supplier/{id}','DeleteSupplier')->name('delete.supplier');
    });
    // End



    // Customer All Route 
    Route::controller(CustomerController::class)->group(function(){
        Route::get('/all/customer','CustomerPage')->name('all.customer'); 
        Route::get('/add/customer','AddCustomer')->name('add.customer');
        Route::post('/store/customer','StoreCustomer')->name('customer.store');  


        Route::get('/edit/customer/{id}','EditCustomer')->name('edit.customer');
        Route::post('/customer/update','CustomerUpdate')->name('customer.update');

        Route::get('/delete/customer/{id}','DeleteCustomer')->name('delete.customer');
    });
    // End


    ///Expense All Route 
    Route::controller(ExpenseController::class)->group(function(){

        Route::get('/add/expense','AddExpense')->name('add.expense');
        Route::post('/store/expense','StoreExpense')->name('expense.store');
        Route::get('/today/expense','TodayExpense')->name('today.expense');


        Route::get('/edit/expense/{id}','EditExpense')->name('edit.expense');
        Route::post('/update/expense','UpdateExpense')->name('expense.update');

        Route::get('/month/expense','MonthExpense')->name('month.expense');
        Route::get('/year/expense','YearExpense')->name('year.expense');
    });
    // End

    

     ///POS All Route 
     Route::controller(PosController::class)->group(function(){

        Route::get('/page/pos','PosPage')->name('pos');
        Route::post('/add-cart','AddCart');
        Route::get('/allitem','AllItem');
        Route::post('/cart-update/{rowId}','CartUpdate');
        Route::get('/cart-remove/{rowId}','CartRemove');

        Route::post('/create-invoice','CreateInvoice');


        Route::post('/create-invoice-pos','CreateInvoiceVI');
        



        //
        Route::post('/final-invoice','FinalInvoice');



        Route::get('/api/products-for-pos', 'getProductsForPos')->name('api.products.pos');



    });
    // End



    ///Order All Route Add commentMore actions
    Route::controller(OrderController::class)->group(function(){

        Route::post('/final-invoice','FinalInvoice');
        Route::get('/pending/order','PendingOrder')->name('pending.order');
        Route::get('/order/details/{order_id}','OrderDetails')->name('order.details');

        Route::post('/order/status/update','OrderStatusUpdate')->name('order.status.update');

        Route::get('/complete/order','CompleteOrder')->name('complete.order');

        // Stock
        Route::get('/stock','StockManage')->name('all.stock');


        
        // PDF Complete Order
        Route::get('/order/invoice-download/{order_id}','OrderInvoice');
        


    
    
    });




    // Start Product
    Route::controller(PurchaseController::class)->group(function(){


        Route::get('/purchase/page','PurchasePage')->name('all.purchase');
        Route::get('/purchase/add','AddPurchase')->name('add.purchase');


        Route::post('/purchase-create','store.purchase');
        Route::get('/pending/purchase','PendingPurchase')->name('pending.purchase');
        
    });
    // End Product



    // Start Permision
    Route::controller(RoleController::class)->group(function(){


        Route::get('/all/permission','AllPermission')->name('all.permission');
        Route::get('/add/permission','AddPermission')->name('add.permission');
        Route::post('/store/permission','StorePermission')->name('permission.store');

        Route::get('/edit/permission/{id}','EditPermission')->name('edit.permission');

        Route::post('/update/permission','UpdatePermission')->name('permission.update');
        Route::get('/delete/permission/{id}','DeletePermission')->name('delete.permission');

        /// ROLE
        Route::get('/all/roles','AllRoles')->name('all.roles');
        Route::get('/add/roles','AddRoles')->name('add.roles');
        Route::post('/store/roles','StoreRoles')->name('roles.store');

        Route::get('/edit/roles/{id}','EditRoles')->name('edit.roles');
        Route::post('/update/roles','UpdateRoles')->name('roles.update');
        Route::get('/delete/roles/{id}','DeleteRoles')->name('delete.roles');

        // Role Permission
        Route::get('/add/roles/permission','AddRolesPermission')->name('add.roles.permission');



        // 
        
    });
    // End Permision






}); // End User Middleware


Route::get('/search-category', [CategoryController::class, 'searchCategory'])->name('search.category');
Route::get('/search-supplier', [SupplierController::class, 'searchSupplier'])->name('search.supplier');

Route::get('/search-product', [ProductController::class, 'searchProduct'])->name('search.product');

Route::get('/search-customer', [CustomerController::class, 'searchCustomer'])->name('search.customer');



// Expense
Route::get('/search-today', [ExpenseController::class, 'searchToday'])->name('search.today');

Route::get('/search-month', [ExpenseController::class, 'searchMonth'])->name('search.month');

Route::get('/search-year', [ExpenseController::class, 'searchYear'])->name('search.year');

Route::get('/search-order', [OrderController::class, 'searchOrder'])->name('search.order');
Route::get('/search-comlete-order', [OrderController::class, 'searchCompleteOrder'])->name('search.complete_order');




Route::get('/get-products', [PosController::class, 'getProductsByCategory']);
Route::post('/add-cart', [PosController::class, 'AddCart']);
Route::get('/search-products', [ProductController::class, 'search']);
Route::get('/search-purchase', [PurchaseController::class, 'search']);








// Get Price Product( puying_price shwo in fill purchse_price)
// routes/web.php
Route::get('/get-product-price/{id}', [PurchaseController::class, 'getProductPrice']);






// Permission
Route::get('/search-permission', [RoleController::class, 'searchPermission'])->name('search.permission');
Route::get('/search-roles', [RoleController::class, 'searchRoles'])->name('search.roles');