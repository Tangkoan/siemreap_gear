<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ShiftController extends Controller
{
    /**
     * ពិនិត្យមើលថាតើមានវេនកំពុងបើក (active shift) ដែរឬទេ
     */
    public function checkActiveShift()
    {
        $activeShift = Shift::where('user_id', Auth::id())
                            ->where('status', 'open')
                            ->latest('opened_at')
                            ->first();

        return response()->json([
            'status' => 'success',
            'is_open' => (bool) $activeShift,
            'shift' => $activeShift
        ]);
    }

    /**
     * បើកវេនថ្មី (Open a new shift)
     */
    public function openShift(Request $request)
    {
        $request->validate([
            'opening_cash' => 'required|numeric|min:0',
        ]);

        // ពិនិត្យម្តងទៀតថាតើមានវេនបើកឬអត់?
        if (Shift::where('user_id', Auth::id())->where('status', 'open')->exists()) {
            return response()->json(['status' => 'error', 'message' => 'You already have an active shift open.'], 400);
        }

        $shift = Shift::create([
            'user_id' => Auth::id(),
            'opening_cash' => $request->opening_cash,
            'opened_at' => Carbon::now(),
            'status' => 'open',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Shift opened successfully!',
            'shift' => $shift
        ]);
    }

    // មុខងារសម្រាប់បិទវេន (closeShift) 
    public function closeShift(Request $request)
    {
        $request->validate([
            // ត្រូវតែបញ្ចូលចំនួនលុយនៅពេលបិទ
            'closing_cash' => 'required|numeric|min:0', 
            // ស្រេចចិត្ត៖ បើមានកំណត់ចំណាំពេលបិទ
            'notes' => 'nullable|string|max:500', 
        ]);

        // ១. ស្វែងរកវេនដែលកំពុង Active សម្រាប់ User បច្ចុប្បន្ន
        $activeShift = Shift::where('user_id', Auth::id())
                            ->where('status', 'open')
                            ->latest('opened_at')
                            ->first();

        if (!$activeShift) {
            return response()->json([
                'status' => 'error', 
                'message' => 'No active shift found to close.'
            ], 404);
        }
        
        // ២. អាប់ដេតទិន្នន័យបិទវេន
        $activeShift->update([
            'closing_cash' => $request->closing_cash,
            'closed_at' => Carbon::now(),
            'notes' => $request->notes,
            'status' => 'closed',
        ]);

        // ៣. (ស្រេចចិត្ត) ដំណើរការ Logic សម្រាប់ផ្ទៀងផ្ទាត់សាច់ប្រាក់
        // អ្នកអាចបន្ថែម Logic ស្មុគស្មាញនៅទីនេះដើម្បីប្រៀបធៀប
        // 'opening_cash' + 'sales_total' - 'expenses' ជាមួយនឹង 'closing_cash'។
        // ដោយសារយើងមិនទាន់មាន Sales Total ក្នុង Shift Table ទេ យើងនឹងរំលងត្រង់នេះសិន។

        return response()->json([
            'status' => 'success',
            'message' => 'Shift closed successfully! Thank you for your service.',
            'shift' => $activeShift
        ]);
    }
}