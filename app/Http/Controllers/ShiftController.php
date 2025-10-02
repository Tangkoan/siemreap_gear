<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\ExchangeRate; // ✅ Import ExchangeRate Model

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
            'opening_cash_usd' => 'required|numeric|min:0',
            'opening_cash_khr' => 'required|numeric|min:0',
            'exchange_rate' => 'required|integer|min:1', // ត្រូវតែមាន Rate
        ]);

        // ពិនិត្យម្តងទៀតថាតើមានវេនបើកឬអត់?
        if (Shift::where('user_id', Auth::id())->where('status', 'open')->exists()) {
            return response()->json(['status' => 'error', 'message' => 'You already have an active shift open.'], 400);
        }
        
        // ពិនិត្យមើល Exchange Rate ដែលកំពុង Active
        $currentRate = ExchangeRate::where('is_active', true)->latest()->first();
        if (!$currentRate || (int)$currentRate->rate_khr != (int)$request->exchange_rate) {
            // ដើម្បីធានាថា Exchange Rate ដែល User បញ្ជូនមក គឺត្រូវនឹង Rate កំពុង Active
            return response()->json(['status' => 'error', 'message' => 'Exchange rate mismatch. Please refresh and try again.'], 400);
        }

        $shift = Shift::create([
            'user_id' => Auth::id(),
            'opening_cash_usd' => $request->opening_cash_usd,
            'opening_cash_khr' => $request->opening_cash_khr,
            'exchange_rate' => $request->exchange_rate, // រក្សាទុក Rate ពេលបើកវេន
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
            'closing_cash_usd' => 'required|numeric|min:0', // ✅ NEW
            'closing_cash_khr' => 'required|numeric|min:0', // ✅ NEW
            'notes' => 'nullable|string|max:500', 
        ]);

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
        
        $activeShift->update([
            'closing_cash_usd' => $request->closing_cash_usd, // ✅ UPDATED
            'closing_cash_khr' => $request->closing_cash_khr, // ✅ UPDATED
            'closed_at' => Carbon::now(),
            'notes' => $request->notes,
            'status' => 'closed',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Shift closed successfully! Thank you for your service.',
            'shift' => $activeShift
        ]);
    }
}