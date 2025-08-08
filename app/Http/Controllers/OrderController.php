<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;






    use Carbon\Carbon;
    use App\Models\Order;
    use App\Models\product;
    use App\Models\Orderdetails;
    use Gloudemans\Shoppingcart\Facades\Cart;

    use Illuminate\Support\Facades\DB; // ✅ ត្រូវប្រាកដថាបាន use DB Facad

    // PDF 
    use Barryvdh\DomPDF\Facade\Pdf;



    class OrderController extends Controller
    {

    public function PendingPreOrders()
        {
            
            $preOrderItems = OrderDetails::with(['order.customer', 'product'])
                                ->where('item_status', 'pre_ordered')
                                ->orderBy('created_at', 'asc')
                                ->get();

            return view('admin.order.pending_pre_orders', compact('preOrderItems'));
    }
        
        //
    public function PendingDue(){

            $alldue = Order::where('due','>','0')
            ->orderBy('id','DESC')->get();
            return view('admin.order.pending_due',compact('alldue'));
    }// End Method 

        

        
        
        
        
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


    // OrderController.php
    public function searchOrder(Request $request)
        {
            // ✅ Eager load ទាំង customer និង orderdetails ដើម្បី​បង្កើន​ល្បឿន
            $query = Order::with('customer', 'orderdetails') 
                            ->where('order_status', 'pending');

            if ($request->has('search') && $request->search != '') {
                $query->where(function ($q) use ($request) {
                    $q->whereHas('customer', function ($cat) use ($request) {
                        $cat->where('name', 'LIKE', '%' . $request->search . '%');
                    })
                    ->orWhere('invoice_no', 'LIKE', '%' . $request->search . '%');
                });
            }
            $query->orderBy('created_at', 'desc');

            $perPage = $request->perPage ?? 10;
            $isAll = $perPage === 'all';

            if ($isAll) {
                $orders = $query->get();
            } else {
                $orders = $query->paginate((int)$perPage);
            }

            $table = '';
            foreach ($orders as $key => $item) {

                // ✅ START: បង្កើតប៊ូតុង Print Invoice ថ្មី
                $printButton = '
                <button type="button" 
                        class="print-invoice-btn text-gray-500 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200 focus:outline-none" 
                        title="Print Invoice" 
                        data-order-id="' . $item->id . '">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.061c1.24 0 2.25-1.01 2.25-2.25V6.75a2.25 2.25 0 0 0-2.25-2.25H5.625a2.25 2.25 0 0 0-2.25 2.25v9a2.25 2.25 0 0 0 2.25 2.25h1.06" />
                    </svg>
                </button>';
                // ✅ END

                // ពិនិត្យ​មើល​ថា​តើ​ក្នុង Order នេះ​មាន​ទំនិញ pre-order ដែរ​ឬទេ
                $isPreOrder = $item->orderdetails->contains('item_status', 'pre_ordered');

                // បង្កើត Badge ដោយ​ផ្អែក​លើ​លក្ខខណ្ឌ​ខាង​លើ
                if ($isPreOrder) {
                    $orderTypeBadge = '<span class="inline-block px-3 py-1 rounded-md bg-blue-600 dark:bg-blue-900 text-white font-semibold shadow-sm">Pre-Order</span>';
                } else {
                    $orderTypeBadge = '<span class="inline-block px-3 py-1 rounded-md bg-green-600 dark:bg-green-900 text-white font-semibold shadow-sm">Sale</span>';
                }
                
                // --- 👇 កូដថ្មីទី១៖ បង្កើតប៊ូតុង "Pay" លុះត្រាតែមានទឹកប្រាក់ជំពាក់ ---
                $payButton = '';
                if ($item->due > 0) {
                    $payButton = '
                    <button type="button" class="icon-edit dark:hover:text-green-900  hover:text-green-900 text-gray-500 transition-colors duration-200   focus:outline-none">
                        <a href="' . route('order.paydue.due', $item->id) . '" >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                            </svg>
                        </a>
                    </button>';
                }

                $table .= '
                <tr class="hover:bg-slate-50 border-b border-slate-200 dark:hover:bg-gray-700">
                    <td class="p-4 py-5">' . ($key + 1) . '</td>
                    <td class="p-4 py-5">' . $item['customer']['name'] . '</td>
                    <td class="p-4 py-5">' . $item->order_date  . '</td>
                    <td class="p-4 py-5">' . $item->payment_status  . '</td>
                    <td class="p-4 py-5">' . $item->invoice_no  . '</td>
                    <td class="p-4 py-5">' . $item->total  . '$</td>
                    <td class="p-4 py-5">' . $item->pay  . '$</td>
                    
                    <td class="p-4 py-5">
                        <span class="inline-block px-3 py-1 rounded-md ' . ($item->due > 0 ? 'bg-red-500 dark:bg-red-900 dark:text-white text-white' : 'bg-gray-500') . ' text-white text-xs font-semibold shadow-sm">
                            '. $item->due .'$
                        </span>
                    </td>
                    
                    <td class="px-2 py-1 text-xs font-semibold text-center align-middle">
                        <span class="inline-block px-3 py-1 rounded-md bg-red-500  dark:bg-red-900 dark:text-white text-white">
                            '. $item->order_status  .'
                        </span>
                    </td>

                    <td class="px-2 py-1 text-xs font-semibold text-center align-middle">
                       
                        <span class="inline-block">
                        
                             ' . $orderTypeBadge . '
                        </span>
                    </td>
                    
                    <td class="px-4 py-4 text-sm whitespace-nowrap">
                        <div class="flex items-center gap-x-6">
                            <button type="button" class="icon-detail dark:hover:text-green-900  hover:text-green-900 text-gray-500 transition-colors duration-200   focus:outline-none">
                                <a href="' . route('order.details', $item->id) . '" >
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </a>
                            </button>

                            ' . $payButton . '
                            ' . $printButton . ' 
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


    public function OrderDetailsDue($order_id){

            $order = Order::where('id',$order_id)->first();

            $orderItem = Orderdetails::with('product')->where('order_id',$order_id)->orderBy('id','DESC')->get();
            return view('admin.order.order_details_due',compact('order','orderItem'));

    }// End Method 



        
    public function OrderStatusUpdate(Request $request){
        $order_id = $request->id;
        $order = Order::findOrFail($order_id);

        // ✅ បើសិនថា due > 0 ត្រូវតែបាន Confirm មកពី client
        if ($order->due > 0 && !$request->has('confirm_due')) {
            $notification = array(
                'message' => __('messages.due_amount_remaining_confirmation_required'),
                'alert-type' => 'warning'
            );
            return redirect()->route('pending.order')->with($notification);
        }

        $orderProducts = Orderdetails::where('order_id', $order_id)->get();

        foreach ($orderProducts as $item) {
            $product = Product::find($item->product_id);

            if ($product->product_store >= $item->quantity) {
                $product->decrement('product_store', $item->quantity);
            } else {
                $notification = array(
                    'message' => __('messages.stock_not_enough_for_the_product') . ' ' . $product->product_name,
                    'alert-type' => 'error'
                );
                return redirect()->route('pending.order')->with($notification);
            }
        }

        $order->update(['order_status' => 'complete']);

        $notification = array(
            'message' => __('messages.order_done_successfully'),
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
                    $q->whereHas('customer', function ($cat) use ($request) {
                        $cat->where('name', 'LIKE', '%' . $request->search . '%');
                    })
                    ->orWhere('invoice_no', 'LIKE', '%' . $request->search . '%');
                });
            }

            // 👉 កំណត់អោយចេញតាម created_at ថ្មីជាងគេ
            $query->orderBy('created_at', 'desc');

            $perPage = $request->perPage ?? 10; // ✅ Default = 10
            $isAll = $perPage === 'all';

          

            if ($isAll) {
                $orders = $query->get(); // ✅ Use query result with filter
            } else {
                $orders = $query->paginate((int)$perPage);
            }

            $table = '';
            foreach ($orders as $key => $item) {

                // ✅ START: បង្កើតប៊ូតុង Print សម្រាប់ Complete Order
                $printCompleteButton = '
                <button type="button" 
                        class="print-complete-invoice-btn text-gray-500 hover:text-blue-600" 
                        title="Print Complete Invoice" 
                        data-order-id="' . $item->id . '">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.061c1.24 0 2.25-1.01 2.25-2.25V6.75a2.25 2.25 0 0 0-2.25-2.25H5.625a2.25 2.25 0 0 0-2.25 2.25v9a2.25 2.25 0 0 0 2.25 2.25h1.06" />
                    </svg>
                </button>';
                // ✅ END

                $table .= '
                <tr class="hover:bg-slate-50 border-b border-slate-200 dark:hover:bg-gray-700">
                    <td class="p-4 py-5">' . ($key + 1) . '</td>
                    
                    

                    
                    <td class="p-4 py-5">' . $item['customer']['name'] . '</td>
                    <td class="p-4 py-5">' . $item->order_date  . '</td>
                    <td class="p-4 py-5">' . $item->payment_status  . '</td>
                    <td class="p-4 py-5">' . $item->invoice_no  . '</td>
                    <td class="p-4 py-5">' . $item->pay  . '</td>
                    
                   


                    <td class="py-1 text-xs font-semibold text-center align-middle">
                        <span class="inline-block px-3 py-1 rounded-md bg-green-600  dark:bg-green-600 dark:text-white text-white">
                            '. $item->order_status  .'
                        </span>
                    </td>

                    <td class="px-4 py-4 text-sm whitespace-nowrap">
                        <div class="flex items-center gap-x-6">
                            ' . $printCompleteButton . '
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
                    

                    <td class="text-xs font-semibold text-center align-middle">
                        <span class="inline-block px-3 py-1 rounded-md bg-gray-500  dark:text-white text-white">
                            ' . $item->total  . ' $
                        </span>
                    </td>

                    <td class="  text-xs font-semibold text-center align-middle">
                            <span class="inline-block px-3 py-1 rounded-md bg-green-600   dark:text-white text-white">
                                '. $item->pay  .' $
                            </span>
                        
                        
                    </td>
                    
                    <td class=" text-xs font-semibold text-center align-middle">
                            <span class="inline-block px-3 py-1 rounded-md bg-red-500  dark:text-white text-white">
                                '. $item->due  .' $
                            </span>
                    </td>
                    
                    <td class="px-4 py-4 text-sm whitespace-nowrap">
                        <div class="flex items-center gap-x-6">
                    
                    

                        <button type="button" class="icon-detail dark:hover:text-green-900  hover:text-green-900 text-gray-500 transition-colors duration-200   focus:outline-none">
                                    <a href="' . route('order.details.due', $item->id) . '" >
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
                'message' => __('messages.due_amount_updated_successfully'),
                'alert-type' => 'success'
            ); 

            return redirect()->route('pending.order')->with($notification);
            
            



    }// End Method 

    // Printe OrderPending
    public function getInvoiceHtml($id)
    {
      
        $order = Order::with('customer', 'orderdetails.product')->find($id);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        // បង្កើត HTML សម្រាប់ Invoice ដោយប្រើ Blade View
        $html = view('admin.invoice.order_pending._template', compact('order'))->render();

        return response()->json(['html' => $html]);
    }

     // ✅ START: បង្កើត Function ថ្មីសម្រាប់ Complete Invoice
    public function getCompleteInvoiceHtml($id)
    {
        $order = Order::with('customer', 'orderdetails.product')->find($id);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        // បង្កើត HTML ដោយហៅ View ថ្មីដែលយើងបានបង្កើត
        $html = view('admin.invoice.order_complete._template', compact('order'))->render();

        return response()->json(['html' => $html]);
    }
    // ✅ END
        
    }
