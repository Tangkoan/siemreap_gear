<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema; // កុំភ្លេច Import Schema

class DatabaseImportController extends Controller
{
    /**
     * បង្ហាញ Form សម្រាប់ Upload ไฟล์
     */
    public function showForm()
    {
        return view('database.import'); // យើងនឹងបង្កើត View នេះនៅជំហានបន្ទាប់
    }

    /**
     * ទទួលនិងដំណើរការไฟล์ .sql ដែលបាន Upload
     */
    public function handleImport(Request $request)
    {
        
        // 1. Validate request
        $request->validate([
            'database_file' => 'required|file|mimes:sql,txt', // បន្ថែម ,txt នៅត្រង់នេះ
        ]);

        // 1. Validate request
        // $request->validate([
        //     'database_file' => 'required|file|mimes:sql',
        // ]);

        // 2. Clear cache ដើម្បីជៀសវាងបញ្ហាពីข้อมูลចាស់
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');

        try {
            // 3. ទទួលបាន Path របស់ไฟล์
            $sql_file_path = $request->file('database_file')->getRealPath();

            // 4. អានเนื้อหาពីไฟล์ SQL
            $sql = file_get_contents($sql_file_path);

            // 5. ដំណើរការ SQL statement ទាំងអស់
            DB::unprepared($sql);

            return redirect()->back()->with('success', 'ការ Import Database បានជោគជ័យ!');

        } catch (\Exception $e) {
            // ប្រសិនបើមានបញ្ហា កើតឡើង
            return redirect()->back()->with('error', 'មានបញ្ហាក្នុងការ Import Database: ' . $e->getMessage());
        }
    }
}