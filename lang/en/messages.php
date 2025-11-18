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
    'setting' => 'Setting',
    'stock' => 'Stock',
    'pending' => 'Pending',
    'complete' => 'Complete',
    'pending_due_sale' => 'Peding Due',
    'all_roles' => 'All Roles',
    'roles_in_permission'=> 'Roles In Permission',
    'all_roles_in_permission'=> 'All Roels In Permission',

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
    'all' => 'All',
    'action'=>'Action',

    // Category page
        'product_category' => 'Add Category',
        'edit_product' => 'Edit Product',
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
        'customer_address' => 'Customer Address',
        'customer_phone'=> 'Customer Phone',
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
        // Purchase Modal
        'add_new_supplier'=> 'Add New Supplier',
        'cancel'=> 'Cancel',
        'errors'=> 'Error',
        'add_new_product' => 'Add New Product',
        'cost' => 'Cost',
        'invoice_no'=> 'Invoice',
        'enter_invoice_no'=> 'Enter Invoice',


        

// =========================== Order ==========================='

        // ផ្សេងៗ
        'payment_exceeds_due_amount'=> 'Payment Exceeds Due Amount',
        'payment_successful'=> 'Payment Successfull',
        'product_not_found' => 'Product Not Found',

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
        // Modal Add New Customer
        'add_new_customer' => 'Add New Customer',
        'address'=> 'Address',

        'order_type'=> 'Order Type',
    
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
        'permission_in_use_error' => 'Permission Can not delete,User use this permission',


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
        'sale_report'=> 'Sale',
        'purchases_report'=> 'Purchase',
        'stock_report'=> 'Stock',
        'incom_outcome_report'=> 'Income & Outcome',
        
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
        
         'a_t'=>'Adjustment Type',
         'quantity'=> 'Quantity',
         'notes_reason'=> 'Notes (Reason)',
         

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
        'no_stock_alert'=>'No stock alerts at the moment. All good!',



// ======================= Condition =========================
        'condition_name' => 'Condtion Name',
        'condition' => 'Condition',
        'add_condition' => 'Add Condition',
        'edit_condition' => 'Edit Condition',

         // Notification 
        'condition_updated_successfully' => 'Condition Updted Successfully',
        'condition_inserted_successfully' => 'Condition Inserted Successfully',
        'condition_delete_error_has_products' => 'Condition Delete Can Not Delete, have Product Use This Condition',
        'condition_deleted_successfully'=> 'Condition Deleted Successfully',
        'please_select_condition'=> 'Please Select Condition',
        // Validate
        'select_condition'=> 'Select Condition',
        
       

// ========================= Setting =========================s
        'information_shop'=> 'Information Shop',
        'information_invoice' => 'Information Invoice',

// ================================ ចំណុចរាយរង​======================
        'no_sales_data_abailable'=> 'No sales data available.',
        'no_expense_data'=> 'No expense data available for this period.',
        'no_purchases'=> 'No purchases found for this period.',
        'no_order' => 'No orders found',
        'total_order'=> 'Total Order',
        'close'=> 'Close',
        'shop_name_kh'=> 'Shop Name (KH)',
        'shop_name_en'=> 'Shop Name (EN)',
        'terms_and_condition'=> 'Terms and Condition',
        'logo'=> 'Logo',
        'choose'=> 'Choose',
        'total_pre_orders'=> 'TOTAL PRE-ORDERS',

        'condition.all'=> 'condition.all',
        'condition.add'=> 'condition.add',
        'condition.edit'=> 'condition.edit',
        'condition.delete'=> 'condition.delete',
        'order.pending.pre.order'=> 'order.pending.pre.order',
        'setting.menu'=> 'seting.menu',
        'stock.menu'=> 'stock.menu',


        'today_is_revenue'=> 'Today\'s Revenue',
        'this_year_is_revenue'=>'This Year\'s Revenue',
        'vs_yesterday'=>'vs yesterday',
        'total_for'=>'Total for',
        'all_time_completed'=>'All-time completed',
        'sales_trend_last_30_days'=>'Sales Trend (Last 30 Days)',
        'top5_best_selling_products'=>'Top 5 Best-Selling Products',
        'monthly_sales_trend'=>'Monthly Sales Trend',
        'order_distribution'=>'Order Distribution',


        //
        'are_uor_sure_to_complete_this_order'=>'Are you sure to complete this order?',
        'this_due_is' => 'This Due Is',
        'confrim' => 'Confrim',
        'due_amount_remaining_confirmation_required'=> 'Due Amout Remaining Confirmation Required',

// ============================= Stock Controller =========================================
        
        'no_permission' => 'You do not have permission to adjust stock.',
        'adjustment_success' => 'Stock adjusted successfully!',
        'adjustment_failed' => 'Stock Adjustment Failed! ', // Note the space at the end
        'invalid_type' => 'Invalid stock adjustment type provided.',
        
        // Sale Return Errors
        'return_sales_fail' => 'Cannot process Sale Return: Product has no prior sales records.',
        'return_qty_exceeds_sold' => 'Sale Return Failed: Requested quantity (:requested) exceeds total quantity sold (:sold).',
        
        // Purchase Return Errors
        'return_purchase_fail' => 'Cannot process Purchase Return: Product has no prior purchase records.',
        'return_qty_exceeds_purchased' => 'Purchase Return Failed: Requested quantity (:requested) exceeds total quantity purchased (:purchased).',
        'insufficient_stock_pr' => 'Purchase Return Failed. Requested quantity (:requested) is more than current stock (:current).',
        
        // Clear Stock Errors
        'insufficient_stock_cs' => 'Clear Stock Failed. Requested quantity (:requested) is more than current stock (:current).',
    
        'select_adj_type'=> 'Select Adjustment Type',
        'adjust_stock_for'=> 'Adjustment Stock For',
        'search_transaction_by_invoice'=> 'Seach Transaction By Invoice',
        'select_sale_transaction'=>'Select Sale Transaction',
        'select_purchase_transaction'=>'Select Purchase Transaction',

        'set_today_s_exchange_rate' => 'Set Today\'s Exchange Rate',
        'f_r_f_e'=>'Fetch Rate from MEF',
        'o_e_m'=>'Or enter manually',
        'please_enter' => 'Please Enter',
        

        'payment_exceeds_due_amount' => 'The payment amount exceeds the amount due.',
        'enter_valid_amount' => 'Please enter a valid amount.',
        'payment_cannot_exceed_due' => 'Payment cannot exceed the due amount', // Message without the value

        'stock_not_enough'=>'Not enough stock for',
        'only'=> 'Only',
        'items_left'=>'item(s) left.',
        'cannot_mix_sale' => 'You cannot sell normal products and Pre-Order items at the same time. Please create a quotation or separate the purchase.',

        // Import DB 
        'title' => 'Import Database',
        'subtitle' => 'Please select a backed-up .sql file to restore your database.',
        'success_title' => 'Success!',
        'error_title' => 'Error!',
        'form_label' => 'SQL File (.sql)',
        'upload_click' => 'Click to upload',
        'upload_drag' => 'or drag and drop',
        'upload_file_type' => 'File Type: .SQL',
        'warning_title' => 'Warning!',
        'warning_message' => 'This import process will overwrite all existing data with data from the backup file. Please ensure you have a recent backup of the current data before proceeding.',
        'submit_button' => 'Start Import',
        'selected_file_label' => 'Selected file:',

        'alert_no_file_title' => 'No File Selected',
        'alert_no_file_text' => 'Please select an SQL file before proceeding.',


        'import_db' => 'Import Database',



// =============================  Open Shift   =============================
        'open_shift' => 'Open Shift',
        'close_shift' => 'Close Shift',
        'open_new_shift' => 'Open New Shift',
        'start_daily_sales_session' => 'Start your daily sales session.',
        'start_your_shift' => 'Start Your Shift',
        'enter_starting_cash_prompt' => 'Please enter the starting cash amount in your cash drawer.',
        'starting_cash_usd' => 'Starting Cash (USD)',
        'start_shift_and_go_to_pos' => 'Start Shift & Go to POS',
        'end_of_shift_reconciliation' => 'End of Shift Reconciliation',
    
        'verify_cash_prompt' => 'Verify your cash sales against the counted amount before closing the shift.',
        'system_calculation' => 'System Calculation',
        'starting_cash_label' => '1. Starting Cash',
        'total_cash_sales_label' => '2. Total Cash Sales',
        'expected_cash_label' => 'Expected Cash in Drawer (1+2)',
        'non_cash_totals_label' => 'Non-Cash Totals (For Reference)',
        'total_card_sales_label' => 'Total Card Sales:',
        'total_qr_sales_label' => 'Total QR Sales:',
        'cashier_declaration_label' => 'Cashier Declaration',
        'actual_cash_label' => 'Actual Cash Counted',
        'actual_cash_placeholder' => 'Enter the total cash amount you counted',
        'warning' => 'Warning',
        'close_shift_warning' => 'Please double-check your cash count. Once a shift is closed, it cannot be reopened.',
        'confirm_close_shift_btn' => 'Confirm & Close Shift',

        // ... all your other keys ...
    'shift_report' => 'Shift Report',
    'review_cash_handling_accuracy' => 'Review cash handling accuracy and discrepancies.',
    'filter_by_cashier' => 'Filter by Cashier',
    'all_cashiers' => 'All Cashiers',
    'filter' => 'Filter',
    'reset' => 'Reset',
    'cashier_honesty_summary' => 'Cashier Honesty Summary',
    'summary_desc' => 'Total difference (Short/Over) for all selected shifts, grouped by cashier.',
    'short' => 'Short',
    'over' => 'Over',
    'perfect' => 'Perfect',
    'no_data_for_summary' => 'No data for summary.',
    'cashier' => 'Cashier',
    'shift_duration' => 'Shift Duration',
    'expected_cash' => 'Expected Cash',
    'actual_cash' => 'Actual Cash',
    'difference' => 'Difference',
    'no_shifts_found_criteria' => 'No shifts found matching the selected criteria.',


    'expense' => 'Expense',
    'add_expense' => 'Add Expense',
//     ថ្ងៃខែចំណាយ

    'table_no' => 'No',
    'expense_date' => 'Expense Date',
    'expense_type' => 'Type',
    'description' => 'Description',
    'amount' => 'Amount',
    'recorder' => 'Recorder',
    'table_action' => 'Action',


    'expense_categories' => 'Expense Category',
    'payrools' => 'Payroll',
    'employees' => 'Emplyees',


    
    'category_name' => 'Category Name',
    'description' => 'Description',
    'usage_count' => 'Usage Count',
    'table_action' => 'Action',
    'add_expense_category' => 'Add Expense Category',
    'save' => 'Save',
    'cancel' => 'Cancel',
    'search' => 'Search',
    'show' => 'Show',
    'all' => 'All',
    'expense_category' => 'Expense Category',
    'edit_expense_category' => 'Edit Expense Category',
    'position'=> 'Position',
    'basic_salary'=> 'Basic Salary',
    'salary_status'=> 'Salry Status',

    'add_employee'=> 'Add Employee',
    'day_for_work'=> 'Day Start Work',
    'edit_employee'=> 'Edit Employee',
    'payrool'=> 'Payroll',

    'payroll_report'=> 'Payroll Report',

    'sart_month'=> 'Start Month',
    'to_month'=> 'End Month',

    'net_salary'=> 'Net Salary',
    'total_payments'=> 'Total Payments',
    'report_for'=> 'Report For : ',


    'payment_day'=> 'Payment Day',
    'for_month'=> 'For Month',
    'bonus'=> 'Bonus',

    'net_salary_th'=> 'Net Salary',

    'stock_insufficient_for_return' => 'Current stock (:stock) is insufficient for this return.',
    'cannot_clear_stock_exceeds' => 'Cannot clear stock. Input quantity exceeds current stock (:stock).',

    

];