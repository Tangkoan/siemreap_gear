<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExchangeRate;
use Carbon\Carbon;

class ExchangeRateController extends Controller
{
    // แสดงหน้า List នៃអត្រាទាំងអស់
    public function index()
    {
        $rates = ExchangeRate::latest()->get();
        return view('admin.exchange_rate.index', compact('rates'));
    }

    // បង្ហាញ Form សម្រាប់បន្ថែមអត្រាថ្មី
    public function create()
    {
        return view('admin.exchange_rate.create');
    }

    // រក្សាទុកអត្រាថ្មី
    public function store(Request $request)
    {
        $request->validate([
            'rate_date' => 'required|date|unique:exchange_rates,rate_date',
            'rate_khr' => 'required|numeric|min:0',
        ]);

        // ធ្វើឱ្យ Rate ចាស់ៗทั้งหมดกลายเป็น inactive
        ExchangeRate::where('is_active', true)->update(['is_active' => false]);

        // បង្កើត Rate ថ្មី
        ExchangeRate::create([
            'rate_date' => $request->rate_date,
            'rate_khr' => $request->rate_khr,
            'is_active' => true, // Rate ថ្មីจะ active ជានិច្ច
        ]);

        $notification = [
            'message' => 'New Exchange Rate Created Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->route('exchange-rates.index')->with($notification);
    }

    // ลบអត្រា
    public function destroy($id)
    {
        ExchangeRate::findOrFail($id)->delete();
        $notification = [
            'message' => 'Exchange Rate Deleted Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($notification);
    }
}