<?php

return [
    'pos' => 'POS',
    'my_account' => 'My Account',
    'change_password' => 'Change Password',
    'logout' => 'Logout',
    'signed_in_as' => 'Signed in as',
    'administrator' => 'Administrator',
    'no_stock_alerts' => 'No stock alerts at the moment. All good!',
    'failed_to_load_notifications' => 'Failed to load notifications. Please try again later.',
    'loading_alerts' => 'Loading alerts...',

    // Sidebar Menu Keys
    'dashboard' => 'Dashboard',
    'category' => 'Category',
    'product' => 'Product',
    'customer' => 'Customer',
    'supplier' => 'Supplier',
    'purchase' => 'Purchase',
    'order' => 'Order',
    'permission' => 'Permission',
    'report' => 'Report',
    'user' => 'User',
    'backup' => 'Backup',

    // របស់ដែលប្រើរួម
    'table_created' => 'Created',
    'table_action' => 'Action',
    'search' => 'Search',
    'show' => 'Show',
    'save'=> 'Save',
    'notes' => 'Notes',
    'email' => 'Email',
    'phone' => 'Phone',
    'name' => 'Name',
    'image' => 'Image',

    // Category page
        'product_category' => 'Product Category',
        'add_category' => 'Add Category',
        'search_for_category' => 'Search for Category',
        'table_no' => 'N<sup>o</sup>',
        'table_category_name' => 'Category Name',
        'table_category_slug' => 'Category Slug',
        // Add Category
        'category_name' => "Category Name",
         // Edit Category
        'edit_category' => 'Edit Category',
    // Notification Messages
    'category_inserted_successfully' => 'Category Inserted Successfully',
    'category_updated_successfully' => 'Category Updated Successfully',
    'category_deleted_successfully' => 'Category Deleted Successfully',
    'category_delete_error_has_products' => 'Cannot delete category. There are products associated with it.',
    
// ==================== Customer ============================
        // List Customer
        'add_customer' => 'Add Customer',
        'customer_name' => 'Customer Name',
        'table_customer_name' => 'Customer Name',
        'table_customer_phone' => 'Customer Phone',
        // Edit
        'edit_customer' => 'Edit Customer',
        
        'customer_inserted_successfully' => 'Customer Inserted Successfully',
        'customer_update_successfully' => 'Customer Update Successfully',
        'customer_delete_error_has_related_records_exist' => 'Cannot delete Customer. There are orders associated with it.',

// ===================== Supplier =======================================
        // List Supplier
        'add_supplier' => 'Add Supplier',
        'edit_supplier'=> 'Edit Supplier',
        // Notification
        'supplier_inserted_successfully'=> 'Supplier Inserted Successfully',
        'supplier_updated_successfully'=> 'Supplier Updated Successfully',
        'supplier_can_not_delete' => 'Cannot delete supplier. There are purchase associated with it.!',
        'supplier_delete_successfully' => 'Delete Supplier Successfully!',

// ===================== Product ========================================
        // List Product
            'product_code'=> 'Product Code',
            'product_name'=> 'Product Name',
            'price'=> 'Price',
            'inventory'=> 'Inventory',
            // Button List Product
            'import'=>'Import',
            'export'=>'Export',
            'add_product'=>'Add Product',
            'select_category'=> 'Select Category',
            'select_supplier'=> 'Select Supplier',
            'details'=> 'Details',
            'choose_file' => 'Choose File',
            'buy_price' => 'Buy Price',
            'stock_alert' => 'Stock Alert',
            // jQuery Validation Messages
            'please_enter_product_name' => 'Please Enter Product Name',
            'please_select_category' => 'Please Select Category',
            'please_select_supplier' => 'Please Select Supplier',
            'please_enter_price_selling_price' => 'Please Enter Price Selling Price',
            'please_enter_inventory' => 'Please Enter Inventory',
            'please_enter_stock_alert' => 'Please Enter Stock Alert',
            'please_enter_buying_price' => 'Please Enter Buying Price',
            // Message
            'product_inserted_successfully' => 'Product Inserted Successfully',
            'product_updated_successfully' => 'Product Updated Successfully',
            'cannot_delete_product' => 'Cannot delete product. It is used in Purchase or Orders.',
            'product_deleted_successfully' => 'Product Deleted Successfully',
            // Edit Page
            'eidt_product'=> 'Edit Product',
            // BarCode
            'barcode'=> 'Barcode',
            // Product View
            'product_details'=> 'Product Details',
            'import_product'=> 'Import Product',
            'download_excell'=> 'Download Excell',
            'excell_file_import'=> 'Excell File Import',
            'upload'=> 'Upload',

            
// ===================== Purchase ========================================
        // Purchase Pending
        'pending_purchase_orders'=> 'Pending Purchase Orders',
        'create_purchase'=>'Create Purchase',
        'supplier_name'=>'Supplier Name',
        'purchase_date'=>'Purchase Date',
        'payment'=>'Payment',
        'invoice'=>'Invoice',
        'pay'=>'Pay',
        'status'=>'Status',
        // Purchase Details
        'purchase_details'=>'Purchase Details',
        'payment_status'=> 'Payment Status',
        'paid_amount'=> 'Paid Amount',
        'due_amount'=> 'Due Amount',
        'no'=>'No',
        'qty'=>'QTY',
        'subtotal'=>'Subtotal',
        'total'=>'Total',
        'complete_purchase'=>'Complete Purchase',
        // Create Purchase
        'all_category' => 'All Category',
        'purchase_cart' => 'Purchase Cart',
        'total_payable'=> 'Total Payable',
        'payment_method'=> 'Payment Method',
        'pay_now'=> 'Pay Now',
        'discount'=> 'Discount',
        'select_payment'=> 'Select Payment',
        'please_select_payment_status'=>'Please Select Payment Method',
        'input_pay_now'=>'Please Endter Customer Pay',
        // Notification
        'purchase_completed_successfully' => 'Purchase Complete Successfully',
        'purchase_done_successfully' => 'Purchase Done Successfully',
        'due_amount_updated_successfully' => 'Due Amount Updated Successfully',
        'discount_cannot_exceed_subtotal' => 'Discount cannot exceed subtotal',
        // Purchase Complete Page
        'complete_purchases'=>'Complete Purchase',
        // Purchase Due Page
        'purchase_pending_due'=> 'Purchase Pending Due',
        'due'=>'Due',
        'purchase_pay_due_amount'=> 'Purchase Pay Due Amount',

// =========================== Order ===========================
        // Pending Orders
        'pending_orders'=> 'Pending Orders',
        'order_date'=> 'Order Date',
        'order_invoice'=> "Order Ivoice",
        'complete_order'=> 'Complete Order',
        'complete_orders'=> 'Complete Orders',
        // Pending Due Page
        'pending_due'=> 'Pending Due',
        // pay_due_amount Page
        'pay_due_amount'=> 'Pay Due Amount',
        'order_details'=> 'Order Details',
        // Notification 
        'order_complete_successfully' => 'Order Complete Successfully',
        'stock_not_enough_for_the_product' => 'Stock Not enough for the product :',
        'order_done_successfully' => 'Order Done Successfully',
        
// ============================= POS =========================================
        // Pos page
        'product_items'=>'Product Items',
        'pay_nows'=> 'Pay Now',
        'no_items_in_cart'=>'No items in cart.',
        'select_customer'=>'Select Customer',
        'please_select_customer'=> 'Please Select Customer',
        // Notification
        'you_mout_add_product_to_cart' => 'You must add product to cart!',
        'not_enough_stock_for_product'=>'Not Enough Stock For Product',
        'something_went_wrong'=>'Something Went Wrong!',
    
// ============ Permision =========================================
        // Pemision Page
        'permissions'=>'Permission',
        'permission_name'=>'Permission Name',
        'group_name'=>'Group Name',
        'add_permission'=> 'Add Permission',
        'select_group_name'=> 'Select Group Name',
        'edit_permission'=>'Edit Permission',
        // Validateion
        'please_enter_permission_name'=>'Please Enter Permission Name',
        'please_select_group_name'=>'Please Select Group Name',
        // Notification
        'permission_added_successfully' => 'Permission Added Successfully',
        'permission_updated_successfully' => 'Permission Updated Successfully',
        'permission_deleted_successfully' => 'Permission Deleted Successfully',
        'permission_name_already_exists'=>'Pemission Name Already',


        // 'message' => 'Role Updated Successfully',
        // 'message' => 'Role Deleted Successfully',
        // 'message' => 'Role Added Successfully',
        // 'message' => 'Role Permission Added Successfully',
        // 'message' => 'Role Permission Updated Successfully',
        // 'message' => 'Role Permission Deleted Successfully',
    
// ======================= Role ===========================
        // List Role
        'roles'=> 'Roles',
        'add_roles'=>'Add Roles',
        'roles_name'=>'Role Name',
        // Validation
        'please_enter_a_role_name'=>'Please enter a role name',
        'roles_name_already_exists'=> 'Roles Name Already Exists',
        // Edit Roles Page
        'edit_roles'=>'Edit Roles',
        // Notification
        'role_updated_successfully' => 'Role Updated Successfully',
        'cannot_delete_this_roles_have_user_use_this_role' => 'Cannot Delete This Roles, Have User Use This Role!!!',
        'role_deleted_successfully' => 'Role Deleted Successfully',
        'role_added_successfully' => 'Role Added Successfully',

// =================== Add Roles Permission ================================
        // Add Roles Permission Page
        'add_roles_permission'=>'Add Roles Permission',
        'all_permission'=>'All Permissions',
        'select_roles'=> 'Select Roles',
        'please_select_role_name'=> 'Please Select Role Name',
        'purchases'=> 'Purchases',
        'orders'=> 'Orders',
        // Customer
        'customer.menu' => 'customer.menu',
        'customer.all' => 'customer.all',
        'customer.add' => 'customer.add',
        'customer.edit' => 'customer.edit',
        'customer.delete' => 'customer.delete',

        // pos
        'pos.menu' => 'pos.menu',

        // supplier
        'supplier.menu' => 'supplier.menu',
        'supplier.all' => 'supplier.all',
        'supplier.add' => 'supplier.add',
        'supplier.edit' => 'supplier.edit',
        'supplier.delete' => 'supplier.delete',

        // category
        'category.menu' => 'category.menu',
        'category.all' => 'category.all',
        'category.add' => 'category.add',
        'category.edit' => 'category.edit',
        'category.delete' => 'category.delete',

        // product
        'product.menu' => 'product.menu',
        'product.all' => 'product.all',
        'product.add' => 'product.add',
        'product.edit' => 'product.edit',
        'product.delete' => 'product.delete',
        'product.import' => 'product.import',
        'product.export' => 'product.export',
        'product.details' => 'product.details',
        'product.barcode' => 'product.barcode',

        // order
        'order.menu' => 'order.menu',
        'order.complete' => 'order.complete',
        'order.pending' => 'order.pending',
        'order.pending.due' => 'order.pending.due',

        // role
        'role.menu' => 'role.menu',

        // purchase
        'purchase.menu' => 'purchase.menu',
        'purchase.complete' => 'purchase.complete',
        'purchase.pending.due' => 'purchase.pending.due',
        'purchase.pending' => 'purchase.pending',
        'purchase.add' => 'purchase.add',

        // user
        'user.menu' => 'user.menu',
        'user.all' => 'user.all',
        'user.add' => 'user.add',
        'user.edit' => 'user.edit',
        'user.delete' => 'user.delete',

        // permission
        'permission.menu' => 'permission.menu',

        // backup
        'backup.menu' => 'backup.menu',

        // reporte
        'reporte.menu' => 'reporte.menu',
        'reporte.purchase' => 'reporte.purchase',
        'reporte.expense' => 'reporte.expense',
        'reporte.sale' => 'reporte.sale',
        'reporte.stock' => 'reporte.stock',
        
        // Role And Permission
        'permission_roler_already' => 'This role already has permissions assigned. Please edit it instead.',
        'role_permission_added_successfully' => 'Role Permission Added Successfully',
        'role_permission_updated_successfully'=> 'Role Permission Updated Successfylly',
        'cannot_delete_this_role' => 'Cannot delete this role. It is assigned to',
        'all_roles_permission'=> 'All Roles Permission',
        'add_role_in_permission'=> 'Add Role In Permission',

// ========================= Report ====================================================
        // Order Report
        'orders_report'=>'Orders Report',
        'by_day'=>'By Day',
        'by_month'=>'By Month',
        'by_year'=> 'By Year',
        'total_revenue'=>'Total Revenue',
        'total_orders'=>'Total Orders',
        'items_sold'=>'Items Sold',
        'avg_order_value'=>'Avg. Order Value',
        'date'=>'date',
        'amount'=>'Amount',
        'total_amount'=>'Total Amount',

        // Purchase Report
        'purchase_report'=> 'Purchase Report',
        'total_spending'=>'Total Spending',
        'total_purchases'=>'Total Purchases',
        'items_purchased'=>'Items Purchased',
        'avg_purchase_value'=>'Avg. Purchase Value',

        // Stock Report
        'stock_movement_report'=>'Stock Movement Report',
        'stock_movement_for'=>'Stock Movement for',
        'total_stock_in'=>'Total Stock In',
        'total_stock_out'=>'Total Stock Out',
        'opening_stock'=>'Opening Stock',
        'stock_in'=>'Stock In',
        'stock_out'=>'Stock Out',
        'closing_stock'=>'Closing Stock',
        'type_stock'=>'Type',

        // Income & Outcome
        'income_expense_report'=>'Income & Expense Report',
        'start_date'=>'Start Date',
        'end_date'=>'End Date',
                // Month
                'start_month'=>'Start Month',
                'end_month'=>'End Month',
                // year
                'start_year'=>'Start Year',
                'end_year'=>'End Year',
        'total_expenses'=>'Total Expenses',
        'profit_loss'=>'Profit / Loss',
        'income_details'=>'Income Details (Sales)',
        'expense_details'=>'Expense Details',
        'day'=> 'Day',
        'month'=> 'Month',
        'year'=> 'Year',        
        'total_expense'=>'Total Expense',
        'report_for'=>'Report for',
        

// ====================== User =======================
        // List user
        'all_user'=>'All User',
        'add_user'=>'Add User',
        // Add User
        'password'=>'Password',
        'edit_user'=>'Edit User',
        'user_updated_successfully' => 'User Updated Successfully',
        'logout_successfully' => 'Logout Successfully',
        'login_successfully' => 'Login Successfully',
        'profile_updated_successfully' => 'Profile Updated Successfully',
        'old_password_doest_match' => 'Old Password Donest Match!!!',
        'password_change_success' => 'Password Change Success',
        'new_user_created_successfully'    => 'New User Created Successfully',
        'user_delete_successfully' => 'User Deleted Successfully',

        // Validate add & edit
        'user_name'=>'Please Enter Username',
        'please_enter_password'=>'Please Enter Password',
        'please_enter_rolesl'=>'Please Select Roles',
        'please_enter_email'=>'Please Enter Email',
        'please_enter_phone'=>'Please Enter Phone',
        
// ================== Bakcup Page ======================
        // Backup DB
        'backup_management'=>'Backup Management',
        'file_name'=>'File Name',
        'size'=>'SIZE',
        'path'=>'PATH',
        'action'=>'Action',
        'backup_database_now'=>'Backup Database Now',
        'database'=>'Database',
        'backup_project_file_now'=>'Backup Project File Now',
        'project'=>'Project',
        			
// ==================== Stock Alert =====================
        'in_stock'=>'In Stock',
        'alert_threshold'=>'Alert Threshold',

     
// ================== Profile ============================
        'personal_info'=> 'Personal Info',
        'user_profile_image'=> 'User Profile Image',
        'no_file_chosse'=> 'No File Chosse',
        'chosse_file'=> 'Chosse File',
        'edit'=> 'Edit',
// =============== Change Password =======================
        'old_password'=>'Old Password',
        'new_password'=>'New Password',
        'confirm_password'=>'Confirm Password',




        

];