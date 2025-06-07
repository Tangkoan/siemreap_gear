<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

use App\Http\Controllers\EmployeeContrlloer;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UnitController;



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


    
    Route::get('/employee/page', [EmployeeContrlloer::class, 'EmployeePage'])->name('employee.all');

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
    });
    
    
    // End Product

    // Route Brand
    Route::controller(BrandController::class)->group(function(){
        Route::get('/all/brand','BrandPage')->name('all.brand');
        Route::get('/add/brand','AddBrand')->name('add.brand');
        // Route::get('/add/employee','AddEmployee')->name('add.employee');

        Route::post('/store/brand','StoreBrand')->name('brand.store');

        Route::get('/edit/brand/{id}','EditBrand')->name('edit.brand'); // គ្រាន់តែចាប់តម្លៃអោយបានថា id = ?
        Route::post('/update/brand','UpdateBrand')->name('brand.update'); // ធ្វើការUpdate Employee

        Route::get('/delete/brand/{id}','DeleteBrand')->name('delete.brand');  // សម្រាប់ Delete (Method គឺ Post តែយើងប្រើ JSនោះទេអ្នកជំនួយក្នុងការDelete)
    });
    // End Route Brand


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


    ///Unit All Route 
    Route::controller(UnitController::class)->group(function(){
        Route::get('/all/unit','AllUnit')->name('all.unit'); 
        Route::get('/add/unit','AddUnit')->name('add.unit');
        Route::post('/store/unit','StoreUnit')->name('unit.store');
        


        Route::get('/edit/unit/{id}','EditUnit')->name('edit.unit');
        Route::post('/unit/update','UnitUpdate')->name('unit.update');

        Route::get('/delete/unit/{id}','DeleteUnit')->name('delete.unit');
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

}); // End User Middleware


Route::get('/search-category', [CategoryController::class, 'searchCategory'])->name('search.category');
Route::get('/search-brand', [BrandController::class, 'searchBrand'])->name('search.brand');
Route::get('/search-supplier', [SupplierController::class, 'searchSupplier'])->name('search.supplier');
Route::get('/search-unit', [UnitController::class, 'searchUnit'])->name('search.unit');

Route::get('/search-product', [ProductController::class, 'searchProduct'])->name('search.product');

