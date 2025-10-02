<?php
// app/Http/Middleware/CheckShift.php (បង្កើតដោយខ្លួនឯង)

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Shift;
use Symfony\Component\HttpFoundation\Response;

class CheckShift
{
    public function handle(Request $request, Closure $next): Response
    {
        $activeShift = Shift::where('user_id', Auth::id())
                            ->where('status', 'open')
                            ->latest('opened_at')
                            ->first();

        if (!$activeShift) {
            // បញ្ជូនទៅកាន់ Dashboard វិញ ឬទំព័រផ្សេងទៀត
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Please open a shift before accessing POS.'], 403);
            }
            // ប្រើ Session flash ដើម្បីបង្ហាញសារ
            session()->flash('error', 'You must open a shift (Open Shift) to start a sales transaction.');
            return redirect()->route('dashboard'); 
        }

        return $next($request);
    }
}