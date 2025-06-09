<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expense;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    //

    public function AddExpense(){

        return view('admin.expense.add_expense');

    } // End Method 

    public function StoreExpense(Request $request){

        Expense::insert([

            'details' => $request->details,
            'amount' => $request->amount,
            'month' => $request->month,
            'year' => $request->year,
            'date' => $request->date,
            'created_at' => Carbon::now(), 
        ]);


            $notification = array(
            'message' => 'Expense Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification); 

    } // End Method 


    public function TodayExpense(){

        $date = date("d-m-Y");
        $today = Expense::where('date',$date)->get();
        return view('admin.expense.today_expense',compact('today'));

    } // End Method 


    public function EditExpense($id){

        $expense = Expense::findOrFail($id);
        return view('admin.expense.edit_expense',compact('expense'));

    }// End Method 


    public function UpdateExpense(Request $request){

        $expense_id = $request->id;

        Expense::findOrFail($expense_id)->update([

            'details' => $request->details,
            'amount' => $request->amount,
            'month' => $request->month,
            'year' => $request->year,
            'date' => $request->date,
            'created_at' => Carbon::now(), 
        ]);


            $notification = array(
            'message' => 'Expense Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('today.expense')->with($notification); 

    }// End Method 


    public function MonthExpense(){

        $month = date("F");
        $monthexpense = Expense::where('month',$month)->get();
        return view('admin.expense.month_expense',compact('monthexpense'));

    }// End Method


    public function YearExpense(){

         $year = date("Y");
        $yearexpense = Expense::where('year',$year)->get();
        return view('admin.expense.year_expense',compact('yearexpense'));

    }// End Method



    public function searchToday(Request $request)
{
    $query = Expense::query();

    if ($request->has('search') && $request->search != '') {
        $query->where('details', 'LIKE', '%' . $request->search . '%')
        ->orWhere('month', 'LIKE', '%' . $request->search . '%')
        ->orWhere('year', 'LIKE', '%' . $request->search . '%');
    }

    // 👉 កំណត់អោយចេញតាម created_at ថ្មីជាងគេ
    $query->orderBy('created_at', 'desc');

    $perPage = $request->perPage ?? 10; // ✅ Default = 10
    $isAll = $perPage === 'all';

    if ($isAll) {
        $expenses = $query->get();
    } else {
        $expenses = $query->paginate((int)$perPage);
    }

    $table = '';
    foreach ($expenses as $key => $item) {
        $table .= '
        <tr class="hover:bg-slate-50 border-b border-slate-200">
            <td class="p-4 py-5">' . ($key + 1) . '</td>
            <td class="p-4 py-5">' . $item->details . '</td>
            <td class="p-4 py-5"> '  . ($item->amount ?? 'null') . '</td>
            <td class="p-4 py-5"> '  . ($item->month ?? 'null') . '</td>
            <td class="p-4 py-5"> '  . ($item->year ?? 'null') . '</td>
            <td class="p-4 py-5">' . date('d/m/Y', strtotime($item->created_at) ?? 'null') . '</td>
            <td class="px-4 py-4 text-sm whitespace-nowrap">
                <div class="flex items-center gap-x-6">
                   
                
                
                <button class="icon-edit text-gray-500 transition-colors duration-200 dark:hover:text-yellow-500 dark:text-gray-300 hover:text-yellow-500 focus:outline-none">
                            <a href="' . route('edit.expense', $item->id) . '" >
                              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 ">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                              </svg>
                            </a>
                </button>
                          
               
                
                
                
                
                </div>

                
            </td>
        </tr>';
    }

    $pagination = $isAll ? '<div class="text-sm text-slate-500">Showing all results</div>' : $expenses->links('pagination::tailwind')->toHtml();

    return response()->json([
        'table' => $table,
        'pagination' => $pagination
    ]);
    }







    public function searchMonth(Request $request)
{
    $query = Expense::query();

    if ($request->has('search') && $request->search != '') {
        $query->where('details', 'LIKE', '%' . $request->search . '%')
        ->orWhere('month', 'LIKE', '%' . $request->search . '%')
        ->orWhere('year', 'LIKE', '%' . $request->search . '%');
    }

    // 👉 កំណត់អោយចេញតាម created_at ថ្មីជាងគេ
    $query->orderBy('created_at', 'desc');

    $perPage = $request->perPage ?? 10; // ✅ Default = 10
    $isAll = $perPage === 'all';

    if ($isAll) {
        $expenses = $query->get();
    } else {
        $expenses = $query->paginate((int)$perPage);
    }

    $table = '';
    foreach ($expenses as $key => $item) {
        $table .= '
        <tr class="hover:bg-slate-50 border-b border-slate-200">
            <td class="p-4 py-5">' . ($key + 1) . '</td>
            <td class="p-4 py-5">' . $item->details . '</td>
            <td class="p-4 py-5"> '  . ($item->amount ?? 'null') . '</td>
            <td class="p-4 py-5"> '  . ($item->month ?? 'null') . '</td>
            <td class="p-4 py-5">' . date('d/m/Y', strtotime($item->created_at) ?? 'null') . '</td>
            <td class="px-4 py-4 text-sm whitespace-nowrap">
                <div class="flex items-center gap-x-6">
                   
                
                
                <button class="icon-edit text-gray-500 transition-colors duration-200 dark:hover:text-yellow-500 dark:text-gray-300 hover:text-yellow-500 focus:outline-none">
                            <a href="' . route('edit.expense', $item->id) . '" >
                              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 ">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                              </svg>
                            </a>
                </button>
                          
               
                
                
                
                
                </div>

                
            </td>
        </tr>';
    }

    $pagination = $isAll ? '<div class="text-sm text-slate-500">Showing all results</div>' : $expenses->links('pagination::tailwind')->toHtml();

    return response()->json([
        'table' => $table,
        'pagination' => $pagination
    ]);
    }

    public function searchYear(Request $request)
{
    $query = Expense::query();

    if ($request->has('search') && $request->search != '') {
        $query->where('details', 'LIKE', '%' . $request->search . '%')
        ->orWhere('month', 'LIKE', '%' . $request->search . '%')
        ->orWhere('year', 'LIKE', '%' . $request->search . '%');
    }

    // 👉 កំណត់អោយចេញតាម created_at ថ្មីជាងគេ
    $query->orderBy('created_at', 'desc');

    $perPage = $request->perPage ?? 10; // ✅ Default = 10
    $isAll = $perPage === 'all';

    if ($isAll) {
        $expenses = $query->get();
    } else {
        $expenses = $query->paginate((int)$perPage);
    }

    $table = '';
    foreach ($expenses as $key => $item) {
        $table .= '
        <tr class="hover:bg-slate-50 border-b border-slate-200">
            <td class="p-4 py-5">' . ($key + 1) . '</td>
            <td class="p-4 py-5">' . $item->details . '</td>
            <td class="p-4 py-5"> '  . ($item->amount ?? 'null') . '</td>
            <td class="p-4 py-5"> '  . ($item->year ?? 'null') . '</td>
            <td class="p-4 py-5">' . date('d/m/Y', strtotime($item->created_at) ?? 'null') . '</td>
            <td class="px-4 py-4 text-sm whitespace-nowrap">
                <div class="flex items-center gap-x-6">
                   
                
                
                <button class="icon-edit text-gray-500 transition-colors duration-200 dark:hover:text-yellow-500 dark:text-gray-300 hover:text-yellow-500 focus:outline-none">
                            <a href="' . route('edit.expense', $item->id) . '" >
                              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 ">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                              </svg>
                            </a>
                </button>
                          
               
                
                
                
                
                </div>

                
            </td>
        </tr>';
    }

    $pagination = $isAll ? '<div class="text-sm text-slate-500">Showing all results</div>' : $expenses->links('pagination::tailwind')->toHtml();

    return response()->json([
        'table' => $table,
        'pagination' => $pagination
    ]);
    }
}
