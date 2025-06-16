<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;






use Carbon\Carbon;
use App\Models\Order;
use App\Models\product;
use App\Models\Orderdetails;
use Gloudemans\Shoppingcart\Facades\Cart;

use DB;


class OrderController extends Controller
{
    //

    public function FinalInvoice(Request $request){

        $data = array();
        $data['customer_id'] = $request->customer_id;
        $data['order_date'] = $request->order_date;
        $data['order_status'] = $request->order_status;
        $data['total_products'] = $request->total_products;
        $data['sub_total'] = $request->sub_total;
        $data['vat'] = $request->vat;

        $data['invoice_no'] = 'EPOS'.mt_rand(10000000,99999999);
        $data['total'] = $request->total;
        $data['payment_status'] = $request->payment_status;
        $data['pay'] = $request->pay;
        $data['due'] = $request->due;
        $data['created_at'] = Carbon::now(); 

        $order_id = Order::insertGetId($data);
        $contents = Cart::content();

        $pdata = array();
        foreach($contents as $content){
            $pdata['order_id'] = $order_id;
            $pdata['product_id'] = $content->id;
            $pdata['quantity'] = $content->qty;
            $pdata['unitcost'] = $content->price;
            $pdata['total'] = $content->total;
            
            $insert = Orderdetails::insert($pdata); 

        } // end foreach


        $notification = array(
            'message' => 'Order Complete Successfully',
            'alert-type' => 'success'
        );

        Cart::destroy();

        return redirect()->route('pos')->with($notification);

    } // End Method 

    public function PendingOrder(){

        $orders = Order::where('order_status','pending')->get();
        return view('admin.order.pending_order',compact('orders'));

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
            <tr class="hover:bg-slate-50 border-b border-slate-200">
                <td class="p-4 py-5">' . ($key + 1) . '</td>
                
                

                
                <td class="p-4 py-5">' . $item['customer']['name'] . '</td>
                <td class="p-4 py-5">' . $item->order_date  . '</td>
                <td class="p-4 py-5">' . $item->payment_status  . '</td>
                <td class="p-4 py-5">' . $item->invoice_no  . '</td>
                <td class="p-4 py-5">' . $item->pay  . '</td>
                
                <td class="p-4 py-5">
                    <span class="inline-block px-3 py-1 rounded-full bg-red-100 text-red-700 font-semibold shadow-sm">
                        '. $item->order_status  .'
                    </span>
                </td>
                
                <td class="px-4 py-4 text-sm whitespace-nowrap">
                    <div class="flex items-center gap-x-6">
                
                   

                    <button type="button" class="icon-detail text-gray-500 transition-colors duration-200 dark:hover:text-red-500 dark:text-gray-300 hover:text-red-500 focus:outline-none">
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


    public function OrderStatusUpdate(Request $request){
        $order_id = $request->id;



        $product = Orderdetails::where('order_id',$order_id)->get();
        foreach($product as $item){
           Product::where('id',$item->product_id)
                ->update(['product_store' => DB::raw('product_store-'.$item->quantity) ]);
        }

     Order::findOrFail($order_id)->update(['order_status' => 'complete']);

         $notification = array(
            'message' => 'Order Done Successfully',
            'alert-type' => 'success'
        ); 

        return redirect()->route('pending.order')->with($notification);


    }// End Method 

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
            <tr class="hover:bg-slate-50 border-b border-slate-200">
                <td class="p-4 py-5">' . ($key + 1) . '</td>
                
                

                
                <td class="p-4 py-5">' . $item['customer']['name'] . '</td>
                <td class="p-4 py-5">' . $item->order_date  . '</td>
                <td class="p-4 py-5">' . $item->payment_status  . '</td>
                <td class="p-4 py-5">' . $item->invoice_no  . '</td>
                <td class="p-4 py-5">' . $item->pay  . '</td>
                
                <td class="p-4 py-5">
                    <span class="inline-block px-3 py-1 rounded-full bg-green-500 text-white font-semibold shadow-sm">
                        '. $item->order_status  .'
                    </span>
                </td>
                
                <td class="px-4 py-4 text-sm whitespace-nowrap">
                    <div class="flex items-center gap-x-6">
                
                   

                    <button type="button" class="icon-edit text-gray-500 transition-colors duration-200 dark:hover:text-red-500 dark:text-gray-300 hover:text-red-500 focus:outline-none">
                                <a href="' . route('order.details', $item->id) . '" >
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
}
