<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;






use Carbon\Carbon;
use App\Models\Order;
use App\Models\product;
use App\Models\Orderdetails;
use Gloudemans\Shoppingcart\Facades\Cart;

// PDF 
use Barryvdh\DomPDF\Facade\Pdf;

use DB;


class OrderController extends Controller
{
    //
    public function PendingDue(){

        $alldue = Order::where('due','>','0')
        ->orderBy('id','DESC')->get();
        return view('admin.order.pending_due',compact('alldue'));
    }// End Method 

    // public function FinalInvoice(Request $request){






    //     $rtotal = $request->total;
    //     $rpay = $request->pay;
    //     $mtotal = $rtotal - $rpay;

    //     $data = array();
    //     $data['customer_id'] = $request->customer_id;
    //     $data['order_date'] = $request->order_date;
    //     $data['order_status'] = $request->order_status;
    //     $data['total_products'] = $request->total_products;
    //     $data['sub_total'] = $request->sub_total;
    //     $data['vat'] = $request->vat;

    //     $data['invoice_no'] = 'EPOS'.mt_rand(10000000,99999999);
    //     $data['total'] = $request->total;
    //     $data['payment_status'] = $request->payment_status;
    //     $data['pay'] = $request->pay;
    //      $data['due'] = $mtotal;
    //     $data['due'] = $request->due;
    //     $data['created_at'] = Carbon::now(); 

    //     $order_id = Order::insertGetId($data);
    //     $contents = Cart::content();

    //     $pdata = array();
    //     foreach($contents as $content){
    //         $pdata['order_id'] = $order_id;
    //         $pdata['product_id'] = $content->id;
    //         $pdata['quantity'] = $content->qty;
    //         $pdata['unitcost'] = $content->price;
    //         $pdata['total'] = $content->total;
            
    //         $insert = Orderdetails::insert($pdata); 

    //     } // end foreach


    //     $notification = array(
    //         'message' => 'Order Complete Successfully',
    //         'alert-type' => 'success'
    //     );

    //     Cart::destroy();

    //     return redirect()->route('pos')->with($notification);

    // } // End Method 

    

    
    
    public function FinalInvoice(Request $request){

        $rtotal = $request->total;
        $rpay = $request->pay;
        $mtotal = $rtotal - $rpay;
    
        $data = [];
        $data['customer_id'] = $request->customer_id;
        $data['order_date'] = $request->order_date;
        $data['order_status'] = $request->order_status;
        $data['total_products'] = $request->total_products;
        $data['sub_total'] = $request->sub_total;
        $data['vat'] = $request->vat;
    
        $data['invoice_no'] = 'SR GEAR' . mt_rand(10000000, 99999999);
        $data['total'] = $rtotal;
        $data['payment_status'] = $request->payment_status;
        $data['pay'] = $rpay;
        $data['due'] = $mtotal; // ✅ តែម្ដង!
        $data['created_at'] = Carbon::now();
    
        $order_id = Order::insertGetId($data);
    
        $contents = Cart::content();
    
        foreach($contents as $content){
            $pdata = [];
            $pdata['order_id'] = $order_id;
            $pdata['product_id'] = $content->id;
            $pdata['quantity'] = $content->qty;
            $pdata['unitcost'] = $content->price;
            $pdata['total'] = $content->total;
    
            Orderdetails::insert($pdata);
        }
    
        $notification = [
            'message' => 'Order Complete Successfully',
            'alert-type' => 'success'
        ];
    
        Cart::destroy();
    
        return redirect()->route('pos')->with($notification);
    }
    
    
    
    public function PendingOrder(){

        $orders = Order::where('order_status','pending')->get();
        return view('admin.order.pending_order',compact('orders'));

    }// End Method 


    // Print Invoice IN PDF
    public function OrderInvoice($order_id){

        $order = Order::where('id',$order_id)->first();

       $orderItem = Orderdetails::with('product')->where('order_id',$order_id)->orderBy('id','DESC')->get();

       $pdf = Pdf::loadView('admin.invoice.order_invoice', compact('order','orderItem'))->setPaper('a4')->setOption([
               'tempDir' => public_path(),
               'chroot' => public_path(),

       ]);
        return $pdf->download('invoice.pdf');

   }// End Method 











    public function searchOrder(Request $request)
    {
        $query = Order::where('order_status', 'pending'); // ✅ មិនប្រើ get()

        // $query = Order::query();

        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->WhereHas('customer', function ($cat) use ($request) {
                        $cat->where('name', 'LIKE', '%' . $request->search . '%');
                        });
            });
        }

        // 👉 កំណត់អោយចេញតាម created_at ថ្មីជាងគេ
        $query->orderBy('created_at', 'desc');

        $perPage = $request->perPage ?? 10; // ✅ Default = 10
        $isAll = $perPage === 'all';

        // if ($isAll) {
        //     // $orders = $query->get();
        //     $orders = Order::where('order_status','pending')->get();;
        // } else {
        //     $orders = $query->paginate((int)$perPage);
        // }

        if ($isAll) {
            $orders = $query->get(); // ✅ Use query result with filter
        } else {
            $orders = $query->paginate((int)$perPage);
        }

        $table = '';
        foreach ($orders as $key => $item) {
            $table .= '
            <tr class="hover:bg-slate-50 border-b border-slate-200 dark:hover:bg-gray-700">
                <td class="p-4 py-5">' . ($key + 1) . '</td>
                
                

                
                <td class="p-4 py-5">' . $item['customer']['name'] . '</td>
                <td class="p-4 py-5">' . $item->order_date  . '</td>
                <td class="p-4 py-5">' . $item->payment_status  . '</td>
                <td class="p-4 py-5">' . $item->invoice_no  . '</td>
                <td class="p-4 py-5">' . $item->pay  . '</td>
                
                <td class="p-4 px-4 py-5 text-center align-middle">
                        <span class="inline-block px-3 py-1 rounded-md bg-red-500 text-white font-semibold shadow-sm">

                        '. $item->order_status  .'
                    </span>
                </td>
                
                <td class="px-4 py-4 text-sm whitespace-nowrap">
                    <div class="flex items-center gap-x-6">
                
                   

                    <button type="button" class="icon-detail dark:hover:text-green-900  hover:text-green-900 text-gray-500 transition-colors duration-200   focus:outline-none">
                                <a href="' . route('order.details', $item->id) . '" >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                    class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                </a>
                    </button>
                       
                    
                    
                    
                    
                    </div>

                    
                </td>
            </tr>';
        }

        $pagination = $isAll ? '<div class="text-sm text-slate-500">Showing all results</div>' : $orders->links('pagination::tailwind')->toHtml();

        return response()->json([
            'table' => $table,
            'pagination' => $pagination
        ]);
    }


    public function OrderDetails($order_id){

        $order = Order::where('id',$order_id)->first();

        $orderItem = Orderdetails::with('product')->where('order_id',$order_id)->orderBy('id','DESC')->get();
        return view('admin.order.order_details',compact('order','orderItem'));

    }// End Method 


    // public function OrderStatusUpdate(Request $request){
    //     $order_id = $request->id;



    //     $product = Orderdetails::where('order_id',$order_id)->get();
    //     foreach($product as $item){
    //        Product::where('id',$item->product_id)
    //             ->update(['product_store' => DB::raw('product_store-'.$item->quantity) ]);
    //     }

        

    //  Order::findOrFail($order_id)->update(['order_status' => 'complete']);

    //      $notification = array(
    //         'message' => 'Order Done Successfully',
    //         'alert-type' => 'success'
    //     ); 

    //     return redirect()->route('pending.order')->with($notification);


    // }// End Method 

    
    public function OrderStatusUpdate(Request $request){
        $order_id = $request->id;
    
        $orderProducts = Orderdetails::where('order_id', $order_id)->get();
    
        foreach ($orderProducts as $item) {
            $product = Product::find($item->product_id);
    
            if ($product->product_store >= $item->quantity) {
                // Stock គ្រប់គ្រាន់ -> កាត់ Stock
                $product->decrement('product_store', $item->quantity);
            } else {
                // Stock មិនគ្រប់គ្រាន់ -> បោះ message error
                $notification = array(
                    'message' => 'Stock Not enough for the product: ' . $product->product_name,
                    'alert-type' => 'error'
                );
                return redirect()->route('pending.order')->with($notification);
            }
        }
    
        // បន្ទាប់ពីកាត់ stock សម្រេច -> update status order
        Order::findOrFail($order_id)->update(['order_status' => 'complete']);
    
        $notification = array(
            'message' => 'Order Done Successfully',
            'alert-type' => 'success'
        );
    
        return redirect()->route('pending.order')->with($notification);
    }
    
    public function StockManage(){

        $product = Product::latest()->get();
        return view('admin.stock.all_stock',compact('product'));
    
    }// End Method 


    public function CompleteOrder(){

        $orders = Order::where('order_status','complete')->get();
        return view('admin.order.order_complete',compact('orders'));

    }// End Method 


    public function searchCompleteOrder(Request $request)
    {
        $query = Order::where('order_status', 'complete'); // ✅ មិនប្រើ get()

        // $query = Order::query();

        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->WhereHas('customer', function ($cat) use ($request) {
                        $cat->where('name', 'LIKE', '%' . $request->search . '%');
                        });
            });
        }

        // 👉 កំណត់អោយចេញតាម created_at ថ្មីជាងគេ
        $query->orderBy('created_at', 'desc');

        $perPage = $request->perPage ?? 10; // ✅ Default = 10
        $isAll = $perPage === 'all';

        // if ($isAll) {
        //     // $orders = $query->get();
        //     $orders = Order::where('order_status','pending')->get();;
        // } else {
        //     $orders = $query->paginate((int)$perPage);
        // }

        if ($isAll) {
            $orders = $query->get(); // ✅ Use query result with filter
        } else {
            $orders = $query->paginate((int)$perPage);
        }

        $table = '';
        foreach ($orders as $key => $item) {
            $table .= '
            <tr class="hover:bg-slate-50 border-b border-slate-200 dark:hover:bg-gray-700">
                <td class="p-4 py-5">' . ($key + 1) . '</td>
                
                

                
                <td class="p-4 py-5">' . $item['customer']['name'] . '</td>
                <td class="p-4 py-5">' . $item->order_date  . '</td>
                <td class="p-4 py-5">' . $item->payment_status  . '</td>
                <td class="p-4 py-5">' . $item->invoice_no  . '</td>
                <td class="p-4 py-5">' . $item->pay  . '</td>
                
                <td class="p-4 py-5 text-center align-middle">
                    
                        <span class="inline-block px-3 py-1 rounded-md bg-green-500 text-white font-semibold shadow-sm">
                            '. $item->order_status  .'
                        </span>
                    
                    
                </td>
                
                <td class="px-4 py-4 text-sm whitespace-nowrap">
                    <div class="flex items-center gap-x-6">
                
                   

                    <button type="button" class="icon-edit dark:hover:text-blue-900  hover:text-blue-900 text-gray-500 transition-colors duration-200  focus:outline-none">
                                <a href="' .  url('order/invoice-download/' . $item->id)  . '" >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                    </svg>

                                </a>
                    </button>
                       
                    
                    
                    
                    
                    </div>

                    
                </td>
            </tr>';
        }

        $pagination = $isAll ? '<div class="text-sm text-slate-500">Showing all results</div>' : $orders->links('pagination::tailwind')->toHtml();

        return response()->json([
            'table' => $table,
            'pagination' => $pagination
        ]);
    }


    // Pending Due
    public function searchPendingDue(Request $request)
    {
        $query = Order::where('due', '>' ,0); 


        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->WhereHas('customer', function ($cat) use ($request) {
                        $cat->where('name', 'LIKE', '%' . $request->search . '%');
                        });
            });
        }

        // 👉 កំណត់អោយចេញតាម created_at ថ្មីជាងគេ
        $query->orderBy('created_at', 'desc');

        $perPage = $request->perPage ?? 10; // ✅ Default = 10
        $isAll = $perPage === 'all';

        // if ($isAll) {
        //     // $orders = $query->get();
        //     $orders = Order::where('order_status','pending')->get();;
        // } else {
        //     $orders = $query->paginate((int)$perPage);
        // }

        if ($isAll) {
            $orders = $query->get(); // ✅ Use query result with filter
        } else {
            $orders = $query->paginate((int)$perPage);
        }

        $table = '';
        foreach ($orders as $key => $item) {
            $table .= '
            <tr class="hover:bg-slate-50 border-b border-slate-200 dark:hover:bg-gray-700">
                <td class="p-4 py-5">' . ($key + 1) . '</td>
                
                

                
                <td class="p-4 py-5">' . $item['customer']['name'] . '</td>
                <td class="p-4 py-5">' . $item->order_date  . '</td>
                <td class="p-4 py-5">' . $item->payment_status  . '</td>
                <td class="p-4 py-5">
                <span class="inline-block px-3 py-1 rounded-md bg-gray-400 text-white font-semibold shadow-sm">
                            ' . $item->total  . ' $
                        </span>
                    
                
                </td>

                <td class="p-4 py-5 ">
                    
                        <span class="inline-block px-3 py-1 rounded-md bg-red-500 text-white font-semibold shadow-sm">
                            '. $item->pay  .' $
                        </span>
                    
                    
                </td>
                
                <td class="p-4 py-5">
                    
                        <span class="inline-block px-3 py-1 rounded-md bg-green-500 text-white font-semibold shadow-sm">
                            '. $item->due  .' $
                        </span>
                    
                    
                </td>
                
                <td class="px-4 py-4 text-sm whitespace-nowrap">
                    <div class="flex items-center gap-x-6">
                
                   

                    <button type="button" class="icon-detail dark:hover:text-green-900  hover:text-green-900 text-gray-500 transition-colors duration-200   focus:outline-none">
                                <a href="' . route('order.details', $item->id) . '" >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                    class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                </a>
                    </button>

                    <button type="button" class="icon-edit dark:hover:text-green-900  hover:text-green-900 text-gray-500 transition-colors duration-200   focus:outline-none">
                                <a href="' . route('order.paydue.due', $item->id) . '" >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                                </svg>

                                </a>
                    </button>


                    
                       
                    
                    
                    
                    
                    </div>

                    
                </td>
            </tr>';
        }

        $pagination = $isAll ? '<div class="text-sm text-slate-500">Showing all results</div>' : $orders->links('pagination::tailwind')->toHtml();

        return response()->json([
            'table' => $table,
            'pagination' => $pagination
        ]);
    }

    public function payDueModel(Request $request, $id){
        $paydue = Order::findOrFail($id);
        

        return view('admin.order.order_payduepage',compact('paydue',));

    }

    public function UpdateDue(Request $request){
        $order_id = $request->id;
        $due_amount = $request->due;
        $pay_amount = $request->pay;

        $allorder = Order::findOrFail($order_id);
        $maindue = $allorder->due;
        $maindpay = $allorder->pay;
 
        $paid_due = $maindue - $due_amount;
        $paid_pay = $maindpay + $due_amount;

        Order::findOrFail($order_id)->update([
            'due' => $paid_due,
            'pay' => $paid_pay, 
        ]);

         $notification = array(
            'message' => 'Due Amount Updated Successfully',
            'alert-type' => 'success'
        ); 

        return redirect()->route('pending.due')->with($notification);


    }// End Method 

    
}
