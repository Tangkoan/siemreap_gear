<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Shift;
use Illuminate\Support\Facades\Auth; // <-- 1. បន្ថែម Use Auth

class CheckActiveShift
{
    public function handle(Request $request, Closure $next): Response
    {
        // ពិនិត្យមើល Session ជាមុន
        $shiftId = $request->session()->get('active_shift_id');

        if (! $shiftId) {
            // ✅ START: Logic កែប្រែ
            // បើគ្មាន Session, សាកល្បងពិនិត្យ Database រកវេនចាស់ (Orphaned Shift)
            $existingShift = Shift::where('user_id', Auth::id())
                                    ->where('status', 'open')
                                    ->first();
            
            if ($existingShift) {
                // បើរកឃើញវេនចាស់, រក្សាទុកវាចូល Session វិញ ហើយអនុញ្ញាតឱ្យចូល
                $request->session()->put('active_shift_id', $existingShift->id);
                return $next($request); 
            }

            // បើគ្មាន Session ហើយក៏គ្មានវេនចាស់ក្នុង DB ដែរ -> ទើបបញ្ជូនទៅ Form បើកវេន
            return redirect()->route('shift.open.form')
                   ->with('error', 'Please open your shift before accessing POS.');
            // ✅ END: Logic កែប្រែ
        }

        // បើមាន Session, ពិនិត្យមើលថាវា Valid (មិនទាន់ Close)
        $shift = Shift::find($shiftId);
        if (!$shift || $shift->status == 'closed') {
            $request->session()->forget('active_shift_id');
            return redirect()->route('shift.open.form')->with('error', 'Your previous shift was closed. Please open a new one.');
        }

        // គ្រប់យ៉ាងត្រឹមត្រូវ, អនុញ្ញាតឱ្យចូល
        return $next($request);
    }
}