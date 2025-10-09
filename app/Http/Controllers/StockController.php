<?php

namespace App\Http\Controllers;

use App\Models\product;
use App\Models\StockAdjustment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StockController extends Controller
{
    public function StockPage()
    {
        $stock = Product::latest()->get();

        return view('admin.stock.all_stock', compact('stock'));
    }

    public function searchStock(Request $request)
    {
        $query = Product::query();

        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('product_name', 'LIKE', '%'.$request->search.'%')
                    ->orWhere('product_code', 'LIKE', '%'.$request->search.'%')
                    ->orWhereHas('category', function ($cat) use ($request) {
                        $cat->where('category_name', 'LIKE', '%'.$request->search.'%');
                    })
                    ->orWhere('product_store', 'LIKE', '%'.$request->search.'%');
            });
        }

        $query->orderBy('created_at', 'desc');
        $perPage = $request->perPage ?? 10;
        $isAll = $perPage === 'all';

        if ($isAll) {
            $categories = $query->get();
        } else {
            $categories = $query->paginate((int) $perPage);
        }

        $table = '';
        foreach ($categories as $key => $item) {
            $adjustStockBtn = '';
            if (Auth::user()->can('stock.manage')) {
                $adjustStockBtn = '
                <button type="button"
                    class="open-modal-btn icon-edit transition-colors duration-200 dark:hover:text-blue-900 hover:text-blue-900 focus:outline-none"
                    data-product-id="'.$item->id.'"
                    data-product-name="'.htmlspecialchars($item->product_name, ENT_QUOTES).'"
                    title="Adjust Stock">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652 L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685 a4.5 4.5 0 011.13-1.897l8.932-8.931zM19.5 7.125 M18 14v4.75A2.25 2.25 0 0115.75 21H5.25 A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                    </svg>
                </button>';
            }

            $statusText = $item->status == '1' ? 'Active' : 'Disable';
            $statusBadgeClass = $item->status == '1'
                ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
                : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
            $statusDisplay = "<span class=\"px-2 py-1 text-xs rounded-md {$statusBadgeClass}\">{$statusText}</span>";

            $table .= '
            <tr class="hover:bg-slate-50 border-b border-slate-200 dark:hover:bg-gray-700">
                <td class="p-2">'.($categories->currentPage() - 1) * $categories->perPage() + $key + 1 .'</td>
                <td class="p-2">
                    <img src="'.asset($item->product_image ? $item->product_image : 'image/no_image.jpg').'" alt="Product Image" class="rounded-md" style="width: 40px; height: 40px; object-fit: cover; object-position: center;" />
                </td>
                <td class="p-2">'.$item->product_code.'</td>
                <td class="p-2">'.Str::limit($item->product_name, 25).'</td>
                <td class="p-2">'.($item->category->category_name ?? 'N/A').'</td>
                <td class="p-2">'.($item->condition->condition_name ?? 'N/A').'</td>
                <td class="p-2">$'.number_format($item->selling_price, 2).'</td>
                <td class="p-2 text-center align-middle">
                    <span class="inline-block px-3 py-1 rounded-md bg-green-600 text-white shadow-sm">'.$item->product_store.'</span>
                </td>
                <td class="p-2">'.$statusDisplay.'</td>
                <td class="px-4 py-4 text-sm whitespace-nowrap">
                    <div class="flex items-center gap-x-6">'.$adjustStockBtn.'</div>
                </td>
            </tr>';
        }

        $pagination = $isAll ? '' : $categories->links('pagination::tailwind')->toHtml();

        return response()->json(['table' => $table, 'pagination' => $pagination]);
    }

    public function adjustStock(Request $request)
    {
        if (! Auth::user()->can('stock.manage')) {
            return redirect()->back()->with(['message' => __('messages.no_permission'), 'alert-type' => 'error']);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|string|in:sale_return,purchase_return,clear_stock',
            'quantity' => 'required|integer|min:1',
            'notes' => 'required|string|max:255',
        ]);

        if ($request->type === 'sale_return') {
            $request->validate(['sale_detail_id' => 'required|exists:orderdetails,id']);

            $original_qty = DB::table('orderdetails')->where('id', $request->sale_detail_id)->value('quantity');
            $total_returned = StockAdjustment::where('type', 'sale_return')
                ->where('related_id', $request->sale_detail_id)
                ->sum('quantity');

            $returnable_qty = $original_qty - $total_returned;

            if ($returnable_qty <= 0) {
                return redirect()->back()->with(['message' => __('messages.transaction_fully_returned'), 'alert-type' => 'error']);
            }
            if ($request->quantity > $returnable_qty) {
                return redirect()->back()->with(['message' => __('messages.return_qty_exceeds_returnable', ['returnable' => $returnable_qty]), 'alert-type' => 'error']);
            }
        }

        if ($request->type === 'purchase_return') {
            $request->validate(['purchase_detail_id' => 'required|exists:purchase_details,id']);

            $original_qty = DB::table('purchase_details')->where('id', $request->purchase_detail_id)->value('quantity');
            $total_returned = StockAdjustment::where('type', 'purchase_return')
                ->where('related_id', $request->purchase_detail_id)
                ->sum('quantity');

            $returnable_qty = $original_qty - $total_returned;

            if ($returnable_qty <= 0) {
                return redirect()->back()->with(['message' => __('messages.transaction_fully_returned'), 'alert-type' => 'error']);
            }
            if ($request->quantity > $returnable_qty) {
                return redirect()->back()->with(['message' => __('messages.return_qty_exceeds_returnable', ['returnable' => $returnable_qty]), 'alert-type' => 'error']);
            }
        }

        try {

            DB::transaction(function () use ($request) {
                $product = Product::findOrFail($request->product_id);
                $before_quantity = (int) $product->product_store;
                $quantity = (int) $request->quantity;
                $action_message = '';

                switch ($request->type) {
                    case 'sale_return':
                        $product->product_store += $quantity;
                        $action_message = "Sale Return: Added $quantity unit(s) from Sale Detail ID: {$request->sale_detail_id}";
                        break;
                    case 'purchase_return':
                        $product->product_store -= $quantity;
                        $action_message = "Purchase Return: Subtracted $quantity unit(s) for Purchase Detail ID: {$request->purchase_detail_id}";
                        break;
                    case 'clear_stock':
                        $product->product_store -= $quantity;
                        $action_message = "Clear Stock: Subtracted $quantity unit(s)";
                        break;
                }

                $product->save();

                StockAdjustment::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'related_id' => in_array($request->type, ['sale_return', 'purchase_return']) ? ($request->sale_detail_id ?? $request->purchase_detail_id) : null,
                    'type' => $request->type,
                    'quantity' => $quantity,
                    'before_quantity' => $before_quantity,
                    'after_quantity' => $product->product_store,
                    'notes' => $request->notes." ($action_message)",
                ]);
            });

        } catch (Exception $e) {
            return redirect()->back()->with(['message' => __('messages.adjustment_failed').': '.$e->getMessage(), 'alert-type' => 'error']);
        }

        return redirect()->back()->with(['message' => __('messages.adjustment_success'), 'alert-type' => 'success']);
    }

    public function getReturnDetails(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|string|in:sale_return,purchase_return',
            'searchTerm' => 'nullable|string',
            'page' => 'nullable|integer',
            'pageSize' => 'nullable|integer|min:1',
        ]);

        $type = $request->type;
        $product_id = $request->product_id;
        $searchTerm = $request->searchTerm;
        $pageSize = $request->input('pageSize', 10);
        $data = null;

        if ($type === 'sale_return') {
            $returned_sum = DB::table('stock_adjustments')
                ->select('related_id', DB::raw('SUM(quantity) as total_returned'))
                ->where('type', 'sale_return')->whereNotNull('related_id')->groupBy('related_id');

            $query = DB::table('orderdetails')
                ->join('orders', 'orderdetails.order_id', '=', 'orders.id')
                ->leftJoinSub($returned_sum, 'returned', fn ($join) => $join->on('orderdetails.id', '=', 'returned.related_id'))
                ->where('orderdetails.product_id', $product_id)
                ->whereRaw('orderdetails.quantity > IFNULL(returned.total_returned, 0)') // Filter only items that can still be returned
                ->select(
                    'orderdetails.id',
                    'orderdetails.quantity as original_qty',
                    'orders.invoice_no',
                    'orders.order_date as date',
                    DB::raw('(orderdetails.quantity - IFNULL(returned.total_returned, 0)) as returnable_qty') // Calculate remaining qty
                );
            if ($searchTerm) {
                $query->where('orders.invoice_no', 'like', "%$searchTerm%");
            }
            $data = $query->orderByDesc('orders.order_date')->paginate($pageSize);

        } elseif ($type === 'purchase_return') {
            $returned_sum = DB::table('stock_adjustments')
                ->select('related_id', DB::raw('SUM(quantity) as total_returned'))
                ->where('type', 'purchase_return')->whereNotNull('related_id')->groupBy('related_id');

            $query = DB::table('purchase_details')
                ->join('purchases', 'purchase_details.purchase_id', '=', 'purchases.id')
                ->leftJoinSub($returned_sum, 'returned', fn ($join) => $join->on('purchase_details.id', '=', 'returned.related_id'))
                ->where('purchase_details.product_id', $product_id)
                ->whereRaw('purchase_details.quantity > IFNULL(returned.total_returned, 0)')
                ->select(
                    'purchase_details.id',
                    'purchase_details.quantity as original_qty',
                    'purchases.invoice_no',
                    'purchases.purchase_date as date',
                    DB::raw('(purchase_details.quantity - IFNULL(returned.total_returned, 0)) as returnable_qty')
                );
            if ($searchTerm) {
                $query->where('purchases.invoice_no', 'like', "%$searchTerm%");
            }
            $data = $query->orderByDesc('purchases.purchase_date')->paginate($pageSize);
        }

        $results = $data ? collect($data->items())->map(function ($item) use ($type) {
            return [
                'id' => $item->id,
                'text' => ($type === 'sale_return' ? 'SALE' : 'PURCHASE')." # {$item->invoice_no} | Bought: {$item->original_qty} | Returnable: {$item->returnable_qty}",
                'returnable_qty' => $item->returnable_qty,
            ];
        }) : [];

        return response()->json([
            'results' => $results,
            'pagination' => ['more' => $data ? $data->hasMorePages() : false],
        ]);
    }
}
