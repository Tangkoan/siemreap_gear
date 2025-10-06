<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\product;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use App\Models\StockAdjustment;
use Illuminate\Support\Facades\DB;
use Exception;

class StockController extends Controller
{
    //
    public function StockPage(){
        
        $stock = Product::latest()->get();
        return view('admin.stock.all_stock', compact('stock'));
    }// End Method

    public function searchStock(Request $request)
    {
        // ទុក function នេះដូចដើម មិនមានការកែប្រែ
        $query = Product::query();

        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('product_name', 'LIKE', '%' . $request->search . '%')
                ->orWhere('product_code', 'LIKE', '%' . $request->search . '%')
                ->orWhereHas('category', function ($cat) use ($request) {
                        $cat->where('category_name', 'LIKE', '%' . $request->search . '%');
                        })
                ->orWhere('product_store', 'LIKE', '%' . $request->search . '%');
            });
        }

        // 👉 កំណត់អោយចេញតាម created_at ថ្មីជាងគេ
        $query->orderBy('created_at', 'desc');

        $perPage = $request->perPage ?? 10; // ✅ Default = 10
        $isAll = $perPage === 'all';

        if ($isAll) {
            $categories = $query->get();
        } else {
            $categories = $query->paginate((int)$perPage);
        }

        $table = '';
        foreach ($categories as $key => $item) {
            $editBtn = '';
            $deleteBtn = '';
            $barcodeBtn = '';
            $viewBtn = '';
            $adjustStockBtn = ''; // បង្កើតตัวแปรថ្មី

            // ✅ Adjust Stock Button
            if (Auth::user()->can('stock.manage')) { // អ្នកអាចបង្កើត Permission ថ្មី
                $adjustStockBtn = '
                <button type="button"
                    class="open-modal-btn icon-edit  transition-colors duration-200 dark:hover:text-blue-900  hover:text-blue-900 focus:outline-none"
                    data-product-id="' . $item->id . '"
                    data-product-name="' . htmlspecialchars($item->product_name, ENT_QUOTES) . '"
                    title="Adjust Stock">
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
            } else {
                $adjustStockBtn = '
                <button type="button" class="text-gray-400 cursor-not-allowed" disabled title="No permission">
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
            
            // ✅ START: កូដ​ថ្មី​សម្រាប់​បង្ហាញ​តែ​អក្សរ Status
            $statusText = $item->status == '1' ? 'Active' : 'Disable';
            $statusBadgeClass = $item->status == '1'
                ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
                : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';

            $statusDisplay = <<<HTML
                <span class="px-2 py-1 text-xs  rounded-md {$statusBadgeClass}">
                    {$statusText}
                </span>
            HTML;
            // ✅ END
            
            $table .= '
            <tr class="hover:bg-slate-50 border-b border-slate-200 dark:hover:bg-gray-700">
                <td class="p-2 ">' . ($key + 1) . '</td>
                
                <td class="p-2">
                    <img src="' . asset($item->product_image ? $item->product_image : 'upload/no_image.jpg') . '" alt="Product Image" class="rounded-md" style="width: 40px; height: 40px; object-fit: cover; object-position: center;" />
                </td>

                <td class="p-2 ">' . $item->product_code . '</td>
                <td class="p-2 ">' . Str::limit($item->product_name, 13) . '</td>

                <td class="p-2 ">' . $item['category']['category_name'] . '</td>
                <td class="p-2 ">' . $item['condition']['condition_name'] . '</td>
                <td class="p-2 ">' . $item->selling_price.'$'  . '</td>
                
                <td class="p-2  text-center align-middle">
                    <span class="inline-block px-3 py-1 rounded-md bg-green-600 text-white  shadow-sm
                                 ">
                        '. $item->product_store  .'
                    </span>
                </td>

                <td class="p-2 ">' . $statusDisplay . '</td>
                
                <td class="px-4 py-4 text-sm whitespace-nowrap">
                    <div class="flex items-center gap-x-6">
                        ' . $adjustStockBtn . $editBtn . $barcodeBtn . $viewBtn . $deleteBtn . '
                    </div>
                </td>
            </tr>';
        }

        $pagination = $isAll ? '<div class="text-sm text-slate-500">Showing all results</div>' : $categories->links('pagination::tailwind')->toHtml();

        return response()->json([
            'table' => $table,
            'pagination' => $pagination
        ]);
    }

    // មុខងារសម្រាប់កែសម្រួលស្តុក (Adjust Stock)
    public function adjustStock(Request $request)
    {
        // 1. ត្រួតពិនិត្យ Permission
        if (!Auth::user()->can('stock.manage')) {
            $notification = [
                // ប្រើ Key: stock.no_permission
                'message' => __('messages.no_permission'), 
                'alert-type' => 'error'
            ];
            return redirect()->back()->with($notification);
        }

        // ត្រួតពិនិត្យ validation messages នឹងប្រើ default របស់ Laravel ឬ custom របស់អ្នក
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|string|in:sale_return,purchase_return,clear_stock', 
            'quantity' => 'required|integer|min:1',
            'notes' => 'required|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $product = Product::findOrFail($request->product_id);
                $before_quantity = (int)$product->product_store;
                $quantity = (int)$request->quantity;
                $product_id = $product->id;

                $new_quantity = $before_quantity;
                $action_message = '';
                $error_message = ''; // កំណត់ error message ជា Key

                switch ($request->type) {
                    case 'sale_return':
                        $total_sold = DB::table('orderdetails')
                                        ->where('product_id', $product_id)
                                        ->sum('quantity');
                                    
                        if ($total_sold == 0) {
                            // Key: return_sales_fail
                            $error_message = __('messages.return_sales_fail');
                            throw new Exception($error_message);
                        }
                        
                        if ($quantity > $total_sold) {
                            // Key: return_qty_exceeds_sold
                            $error_message = __('messages.return_qty_exceeds_sold', [
                                'requested' => $quantity, 
                                'sold' => $total_sold
                            ]);
                            throw new Exception($error_message);
                        }

                        $new_quantity = $before_quantity + $quantity;
                        $action_message = 'Sale Return: Added ' . $quantity . ' unit(s)';
                        break;
                        
                    case 'purchase_return':
                        $total_purchased = DB::table('purchase_details')
                                        ->where('product_id', $product_id)
                                        ->sum('quantity');

                        if ($total_purchased == 0) {
                            // Key: return_purchase_fail
                            $error_message = __('messages.return_purchase_fail');
                            throw new Exception($error_message);
                        }
                        
                        if ($quantity > $total_purchased) {
                            // Key: return_qty_exceeds_purchased
                            $error_message = __('messages.return_qty_exceeds_purchased', [
                                'requested' => $quantity, 
                                'purchased' => $total_purchased
                            ]);
                            throw new Exception($error_message);
                        }
                        
                        $new_quantity = $before_quantity - $quantity;
                        if ($new_quantity < 0) {
                            // Key: insufficient_stock_pr
                            $error_message = __('messages.insufficient_stock_pr', [
                                'requested' => $quantity, 
                                'current' => $before_quantity
                            ]);
                            throw new Exception($error_message);
                        }
                        
                        $action_message = 'Purchase Return: Subtracted ' . $quantity . ' unit(s)';
                        break;

                    case 'clear_stock':
                        $new_quantity = $before_quantity - $quantity;
                        
                        if ($new_quantity < 0) {
                            // Key: insufficient_stock_cs
                            $error_message = __('messages.insufficient_stock_cs', [
                                'requested' => $quantity, 
                                'current' => $before_quantity
                            ]);
                            throw new Exception($error_message);
                        }
                        
                        $action_message = 'Clear Stock: Subtracted ' . $quantity . ' unit(s)';
                        break;
                        
                    default:
                        // Key: invalid_type
                        $error_message = __('messages.invalid_type');
                        throw new Exception($error_message);
                }
                
                // 4. Update ស្តុកថ្មី
                $product->product_store = $new_quantity;
                $product->save(); 

                // 5. បង្កើតប្រវត្តិការកែតម្រូវ
                StockAdjustment::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'type' => $request->type, 
                    'quantity' => $quantity,
                    'before_quantity' => $before_quantity,
                    'after_quantity' => $product->product_store,
                    'notes' => $request->notes . ' (' . $action_message . ')',
                ]);
            });
        } catch (Exception $e) {
            $notification = [
                // Key: adjustment_failed
                'message' => __('messages.adjustment_failed') . $e->getMessage(),
                'alert-type' => 'error'
            ];
            return redirect()->back()->with($notification);
        }

        $notification = [
            // Key: adjustment_success
            'message' => __('messages.adjustment_success'),
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($notification);
    }
}