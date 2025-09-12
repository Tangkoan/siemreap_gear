<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth; // បញ្ជាក់ Auth class

use Carbon\Carbon;

class CustomerController extends Controller
{
    //

    public function CustomerPage(){
        $customer = Customer::latest()->get();
        return view('admin.customer.admin_customer', compact('customer'));
    }



    public function AddCustomer(){
        return view('admin.customer.add_customer');
    } // End Method

    public function EditCustomer($id){
        $customer = Customer::findOrFail($id);
        return view('admin.customer.edit_customer', compact('customer'));
    } // End Method


    public function StoreCustomer(Request $request)
    {
        

        $request->validate([
            'name' => 'required|max:200|unique:customers,name',
        ]);


        Customer::insert([
            'name' => $request->name,
            'phone' => $request->phone,
            'notes' => $request->notes,
            'address' => $request->address,
            'created_at' => Carbon::now(),
        ]);
       

        
        // Notification
        $notification = [
            'message' => __('messages.customer_inserted_successfully'),
            'alert-type' => 'success',
        ];

        return redirect()->route('all.customer')->with($notification);
       
    }
    // End

    public function CustomerUpdate(Request $request){
        $customer_id = $request->id;

        $request->validate([
            'name' => 'required|max:200|unique:customers,name,'. $customer_id,
        ]);

        Customer::findOrFail($customer_id)->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'notes' => $request->notes,
            'updated_at' => Carbon::now(), 
        ]);
       

        // Notification
        $notification = [
            'message' => __('messages.customer_update_successfully'),
            'alert-type' => 'success',
        ];

        return redirect()->route('all.customer')->with($notification);
       
    }// End Method


    

    public function DeleteCustomer($id)
{
    $customer = Customer::findOrFail($id);

    // ✅ ពិនិត្យថា customer មាន order ឬទិន្នន័យផ្សេងទៀតទេ
    $hasOrders = $customer->orders()->exists(); // បើអ្នកមាន table orders
    // $hasInvoices = $customer->invoices()->exists(); // ឧទាហរណ៍បើមាន table invoices

    if ($hasOrders) {
        
        $notification = array(
                'message' => __('messages.customer_delete_error_has_related_records_exist'),
                'alert-type' => 'error'
        );

        return redirect()->back()->with($notification);
    }

    $customer->delete();

    $notification = array(
        'message' => 'Customer Deleted Successfully',
        'alert-type' => 'success'
    );

    $notification = array(
                'message' => __('messages.customer_deleted_successfully'),
                'alert-type' => 'success'
    );
    return redirect()->back()->with($notification);
}




    public function searchCustomer(Request $request)
{
    $query = Customer::query();

    if ($request->has('search') && $request->search != '') {
        $query->where('name', 'LIKE', '%' . $request->search . '%')
        ->orWhere('phone', 'LIKE', '%' . $request->search . '%')
        ->orWhere('address', 'LIKE', '%' . $request->search . '%');
    }

    // 👉 កំណត់អោយចេញតាម created_at ថ្មីជាងគេ
    $query->orderBy('created_at', 'desc');

    $perPage = $request->perPage ?? 10; // ✅ Default = 10
    $isAll = $perPage === 'all';

    if ($isAll) {
        $customers = $query->get();
    } else {
        $customers = $query->paginate((int)$perPage);
    }

    $table = '';
    foreach ($customers as $key => $item) {
        $editBtn = '';
        $deleteBtn = '';

        // ✅ Edit Button
        if (Auth::user()->can('customer.edit')) {
            $editBtn = '
            <button class="icon-edit  transition-colors duration-200  focus:outline-none">
                <a href="' . route('edit.customer', $item->id) . '">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" 
                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652
                            L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685
                            a4.5 4.5 0 011.13-1.897l8.932-8.931zM19.5 7.125
                            M18 14v4.75A2.25 2.25 0 0115.75 21H5.25
                            A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                    </svg>
                </a>
            </button>';
        } else {
            // Disabled Edit Button (grey)
            $editBtn = '
            <button class=" text-gray-400 cursor-not-allowed" disabled title="No permission to edit">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" 
                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652
                        L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685
                        a4.5 4.5 0 011.13-1.897l8.932-8.931zM19.5 7.125
                        M18 14v4.75A2.25 2.25 0 0115.75 21H5.25
                        A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>
            </button>';
        }
        
        // ✅ Delete Button
        if (Auth::user()->can('customer.delete')) {
            $deleteBtn = '
                <button type="button" class="icon-delete text-gray-500 transition-colors duration-200 dark:hover:text-red-500 dark:text-red-500  hover:text-red-500 focus:outline-none">
                <a href="' . route('delete.customer', $item->id) . '" id="delete">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21
                            c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673
                            a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0
                            01-2.244-2.077L4.772 5.79m14.456 0
                            a48.108 48.108 0 00-3.478-.397m-12 .562
                            c.34-.059.68-.114 1.022-.165m0 0
                            a48.11 48.11 0 013.478-.397m7.5 0v-.916
                            c0-1.18-.91-2.164-2.09-2.201
                            a51.964 51.964 0 00-3.32 0
                            c-1.18.037-2.09 1.022-2.09 2.201v.916
                            m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                </a>
            </button>';
        } else {
            // Disabled Delete Button (grey)
            $deleteBtn = '
            <button type="button" class=" text-gray-400 cursor-not-allowed" disabled title="No permission to delete">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21
                        c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673
                        a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0
                        01-2.244-2.077L4.772 5.79m14.456 0
                        a48.108 48.108 0 00-3.478-.397m-12 .562
                        c.34-.059.68-.114 1.022-.165m0 0
                        a48.11 48.11 0 013.478-.397m7.5 0v-.916
                        c0-1.18-.91-2.164-2.09-2.201
                        a51.964 51.964 0 00-3.32 0
                        c-1.18.037-2.09 1.022-2.09 2.201v.916
                        m7.5 0a48.667 48.667 0 00-7.5 0" />
                </svg>
            </button>';
        }

        $table .= '
        <tr class="hover:bg-slate-50 border-b border-slate-200 dark:hover:bg-gray-700">
            <td class="p-2">' . ($key + 1) . '</td>
            <td class="p-2">' . $item->name . '</td>
            <td class="p-2"> '  . ($item->phone ?? 'null') . '</td>
            <td class="p-2"> '  . ($item->notes ?? 'null') . '</td>
            <td class="p-2"> '  . ($item->address ?? 'null') . '</td>
            <td class="p-2">' . date('d/m/Y', strtotime($item->created_at) ?? 'null') . '</td>
            <td class="px-4 py-4 text-sm whitespace-nowrap">
                    <div class="flex items-center gap-x-6">
                        ' . $editBtn . $deleteBtn . '
                    </div>
            </td>
        </tr>';
    }

    $pagination = $isAll ? '<div class="text-sm text-slate-500">Showing all results</div>' : $customers->links('pagination::tailwind')->toHtml();

    return response()->json([
        'table' => $table,
        'pagination' => $pagination
    ]);
    }
}
