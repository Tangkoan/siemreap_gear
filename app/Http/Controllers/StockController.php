<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\product;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth; // បញ្ជាក់ Auth class
use Illuminate\Support\Str;

class StockController extends Controller
{
    //

    public function StockPage(){
       
        $stock = Product::latest()->get();
        return view('admin.stock.all_stock', compact('stock'));
    }// End Method

    public function searchStock(Request $request)
            {
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
                    
                    
                    

                    // ✅ View Button
                    if (Auth::user()->can('product.details')) {
                        $viewBtn = '
                        <button class="icon-detail dark:hover:text-green-900  hover:text-green-900  transition-colors duration-200  focus:outline-none">
                            <a href="' . route('detail.product', $item->id) . '" >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                            class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                            </a>
                        </button>';
                    } else {
                        // Disabled View Button (grey)
                        $viewBtn = '
                        <button type="button" class=" text-gray-400 cursor-not-allowed" disabled title="No permission to delete">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                            class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </button>';
                    }


                    // ✅ Delete Button
                    if (Auth::user()->can('product.delete')) {
                        $deleteBtn = '
                        <button class="icon-delete  transition-colors duration-200 dark:hover:text-red-900  hover:text-red-900 focus:outline-none">
                            <a href="' . route('delete.product', $item->id) . '" id="delete">
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
                        <td class="p-4 py-5">' . ($key + 1) . '</td>
                        
                        <td class="p-4 py-5">
                            <img src="' . asset($item->product_image ? $item->product_image : 'upload/no_image.jpg') . '" alt="Product Image" class="rounded-md" style="width: 40px; height: 40px; object-fit: cover; object-position: center;" />
                        </td>

                        <td class="p-4 py-5">' . $item->product_code . '</td>
                        <td class="p-4 py-5">' . Str::limit($item->product_name, 13) . '</td>

                        <td class="p-4 py-5">' . $item['category']['category_name'] . '</td>
                        <td class="p-4 py-5">' . $item['condition']['condition_name'] . '</td>
                        <td class="p-4 py-5">' . $item->selling_price.'$'  . '</td>
                        
                        <td class="p-4 py-5 text-center align-middle">
                            <span class="inline-block px-3 py-1 rounded-md bg-green-600 text-white  shadow-sm
                                        ">
                                '. $item->product_store  .'
                            </span>
                        
                        
                        </td>

                        <td class="p-4 py-5">' . $statusDisplay . '</td>
                        

                      
                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                            <div class="flex items-center gap-x-6">
                                ' . $editBtn . $barcodeBtn . $viewBtn . $deleteBtn . '
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
    
}
