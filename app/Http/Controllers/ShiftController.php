<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller; // ត្រូវប្រាកដថាបាន Import Controller មេ
use App\Models\Order; // <--- ✅ សូមបន្ថែមបន្ទាត់នេះ

class ShiftController extends Controller
{
    // បង្ហាញ Form បើកវេន
    public function showOpenForm()
    {
        // ពិនិត្យមើលថាតើអ្នកប្រើនេះមានវេនបើករួចហើយឬនៅ
        $existingShift = Shift::where('user_id', Auth::id())
                                ->where('status', 'open')
                                ->first();

        if ($existingShift) {
            // បើមានវេនបើកហើយ បញ្ជូនទៅផ្ទាំង POS តែម្តង
            return redirect()->route('pos'); 
        }

        // ត្រូវប្រាកដថា View នេះមាន
        return view('admin.shift.open_form');
    }

    // ដំណើរការបើកវេន
    public function openShift(Request $request)
    {
        $request->validate(['starting_cash' => 'required|numeric|min:0']);

        $shift = Shift::create([
            'user_id' => Auth::id(),
            'start_time' => now(),
            'starting_cash' => $request->starting_cash,
            'status' => 'open',
        ]);

        // **សំខាន់ណាស់៖** រក្សាទុក ID របស់វេនដែលកំពុង Active ទៅក្នុង Session
        $request->session()->put('active_shift_id', $shift->id);

        return redirect()->route('pos'); // បញ្ជូនទៅផ្ទាំង POS
    }

    // បង្ហាញ Form បិទវេន
    public function showCloseForm(Request $request)
    {
        $shiftId = $request->session()->get('active_shift_id');
        if (!$shiftId) {
             return redirect()->route('shift.open.form')->with('error', 'No active shift found.');
        }
        
        $shift = Shift::findOrFail($shiftId);

        // គណនាយอดលក់ក្នុងវេននេះ
        // ឥឡូវនេះ "Order" នឹងដំណើរការ
        $sales = Order::where('shift_id', $shiftId);

        // ⚠️ សំខាន់៖ សូមប្រាកដថាអ្នកប្រើ 'payment_status' ឱ្យត្រូវនឹង Column ក្នុងតារាង 'orders'
        // $totalCash = $sales->clone()->where('payment_status', 'cash')->sum('total');
        $totalCash = $sales->clone()->whereIn('payment_status', ['Cash', 'HandCash','QrScan'])->sum('total');
        $totalCard = $sales->clone()->where('payment_status', 'card')->sum('total');
        $totalQR = $sales->clone()->where('payment_status', 'qr')->sum('total');

        $expectedCash = $shift->starting_cash + $totalCash;

        // ហៅ View សម្រាប់បិទវេន
        return view('admin.shift.close_shift_form', compact(
            'shift', 
            'totalCash', 
            'totalCard', 
            'totalQR', 
            'expectedCash'
        ));
    }

    // ដំណើរការបិទវេន
    public function closeShift(Request $request)
    {
        $request->validate(['actual_cash' => 'required|numeric|min:0']);
        
        $shiftId = $request->session()->get('active_shift_id');
        $shift = Shift::findOrFail($shiftId);

        // គណនាយอดលក់ម្តងទៀត
        $sales = Order::where('shift_id', $shiftId);
        // $totalCash = $sales->clone()->where('payment_status', 'cash')->sum('total');
        $totalCash = $sales->clone()->whereIn('payment_status', ['Cash', 'HandCash','QrScan'])->sum('total');
        $totalCard = $sales->clone()->where('payment_status', 'card')->sum('total');
        $totalQR = $sales->clone()->where('payment_status', 'qr')->sum('total');

        $expectedCash = $shift->starting_cash + $totalCash;
        $difference = $request->actual_cash - $expectedCash;

        // Update វេន (Shift)
        $shift->update([
            'end_time' => now(),
            'ending_cash' => $request->actual_cash,
            'total_sales_cash' => $totalCash,
            'total_sales_card' => $totalCard,
            'total_sales_qr' => $totalQR,
            'difference' => $difference,
            'status' => 'closed',
        ]);

        // បញ្ចប់ Session របស់វេន
        $request->session()->forget('active_shift_id');

        // បញ្ជូនទៅផ្ទាំង Dashboard
        return redirect()->route('dashboard')->with('success', 'Shift closed successfully! Difference: ' . $difference);
    }
}