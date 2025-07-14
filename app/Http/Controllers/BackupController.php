<?php

// Make sure to use the correct namespace for your controller
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Exception;


// Backup
use File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Finder\SplFileInfo;

class BackupController extends Controller
{
    /**
     * យក Disk ដែលបានកំណត់សម្រាប់ផ្ទុក Backup ពី config។
     */
    private function getBackupDisk()
    {
        $diskName = config('backup.backup.destination.disks')[0];
        return Storage::disk($diskName);
    }

    /**
     * យកឈ្មោះ Folder ដែលផ្ទុក File Backup (ជាធម្មតាជាឈ្មោះ App)។
     */
    private function getBackupFolderName()
    {
        return str_replace(' ', '-', config('app.name'));
    }

    /**
     * បង្ហាញទំព័រ Backup (ទិន្នន័យនឹងត្រូវបានផ្ទុកដោយ AJAX)។
     */
    public function databaseBackup()
    {
        // ឥឡូវនេះគ្រាន់តែ return view ប៉ុណ្ណោះ។ AJAX នឹងទទួលខុសត្រូវក្នុងការផ្ទុកទិន្នន័យ។
        return view('admin.backup.db_backup');
    }

    /**
     * จัดการ AJAX request សម្រាប់ការស្វែងរក និងបែងចែកទំព័រ។
     */
    public function searchBackups(Request $request)
    {
        $disk = $this->getBackupDisk();
        $folderName = $this->getBackupFolderName();
        $search = $request->search;

        $allFiles = collect($disk->files($folderName))
            ->filter(function ($file) {
                return pathinfo($file, PATHINFO_EXTENSION) === 'zip';
            })
            ->map(function ($file) use ($disk) {
                return new SplFileInfo($disk->path($file), '', '');
            })
            ->sortByDesc(function ($file) {
                return $file->getMTime();
            });

        if ($search) {
            $allFiles = $allFiles->filter(function ($file) use ($search) {
                return stripos($file->getFilename(), $search) !== false;
            });
        }

        $perPage = $request->perPage ?? 10;
        $isAll = $perPage === 'all';
        $currentPage = $request->page ?? 1;

        $paginatedFiles = $isAll ? $allFiles->values() : $allFiles->forPage($currentPage, $perPage)->values();
        $startIndex = $isAll ? 0 : ($currentPage - 1) * $perPage;

        $table = '';
        if ($paginatedFiles->isEmpty()) {
            $table = '<tr><td colspan="5" class="text-center p-5 text-slate-500">No backup files found.</td></tr>';
        } else {
            foreach ($paginatedFiles as $key => $file) {
                $filename = $file->getFilename();
                $sizeInBytes = $file->getSize();
                $size = $sizeInBytes > (1024 * 1024)
                    ? number_format($sizeInBytes / (1024 * 1024), 2) . ' MB'
                    : number_format($sizeInBytes / 1024, 2) . ' KB';
                
                $path = $file->getPath();
                
                // ✅ [កែសម្រួល] បានប្តូរឈ្មោះ parameter ទៅជា 'getFilename' ដើម្បីឱ្យត្រូវនឹង Route
                $downloadUrl = route('backup.download', ['getFilename' => $filename]);
                $deleteUrl = route('backup.delete', ['getFilename' => $filename]);

                $table .= '
                    <tr class="hover:bg-slate-50 dark:hover:bg-gray-700 border-b border-slate-200 dark:border-gray-700">
                        <td class="p-4 py-5 font-semibold text-sm text-slate-800 dark:text-gray-200">' . ($startIndex + $key + 1) . '</td>
                        <td class="p-4 py-5 text-sm text-black dark:text-gray-200">' . $filename . '</td>
                        <td class="p-4 py-5 text-sm text-black dark:text-gray-200">' . $size . '</td>
                        <td class="p-4 py-5 text-sm text-black dark:text-gray-200">' . $path . '</td>
                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                            <div class="flex items-center gap-x-2">
                                <a href="' . $downloadUrl . '" class="icon-download inline-flex items-center px-3 py-1 text-white text-sm rounded-md transition-colors duration-200" title="Download">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" /></svg>
                                </a>
                                <a href="' . $deleteUrl . '" id="delete" class="icon-delete inline-flex items-center px-3 py-1 text-white text-sm rounded-md transition-colors duration-200" title="Delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                </a>
                            </div>
                        </td>
                    </tr>';
            }
        }

        $pagination = ''; // Your JS handles pagination links, so we just provide info.
        

        return response()->json(['table' => $table, 'pagination' => $pagination]);
    }

    /**
     * ចាប់ផ្តើមដំណើរការ Backup ដោយដាក់ចូលក្នុង Queue។
     */
    public function backupNow()
    {
        try {
            Artisan::queue('backup:run');
            Log::info('Database backup job has been queued successfully.');
            return redirect()->back()->with('start_backup_check', true);
        } catch (\Exception $e) {
            Log::error('Failed to queue the backup job: ' . $e->getMessage());
            $notification = [
                'message' => 'Failed to start backup process. Please check the system logs.',
                'alert-type' => 'error'
            ];
            return redirect()->back()->with($notification);
        }
    }

    /**
     * ពិនិត្យមើលស្ថានភាពរបស់ Backup Job (សម្រាប់ AJAX Polling)។
     */
    public function getBackupStatus()
    {
        $disk = $this->getBackupDisk();
        $folderName = $this->getBackupFolderName();
        $files = $disk->files($folderName);

        if (empty($files)) {
            return response()->json(['status' => 'pending']);
        }

        $latestFile = collect($files)->sortByDesc(function ($file) use ($disk) {
            return $disk->lastModified($file);
        })->first();

        if (!$latestFile) {
            return response()->json(['status' => 'pending']);
        }

        $lastModified = $disk->lastModified($latestFile);

        if (time() - $lastModified < 15) {
            return response()->json([
                'status' => 'completed',
                'message' => 'Database Backup Successfully!',
                'alert-type' => 'success'
            ]);
        }

        return response()->json(['status' => 'pending']);
    }

    /**
     * ទាញយក (Download) File Backup ដែលបានជ្រើសរើស។
     * @param string $getFilename ✅ [កែសម្រួល] បានប្តូរឈ្មោះ parameter
     */
    public function downloadBackup($getFilename)
    {
        $disk = $this->getBackupDisk();
        $folderName = $this->getBackupFolderName();
        $filePath = $folderName . '/' . $getFilename;

        if ($disk->exists($filePath)) {
            return $disk->download($filePath);
        }
        abort(404, 'File not found.');
    }

    /**
     * លុប File Backup ដែលបានជ្រើសរើស។
     * @param string $getFilename ✅ [កែសម្រួល] បានប្តូរឈ្មោះ parameter
     */
    public function deleteBackup($getFilename)
    {
        $disk = $this->getBackupDisk();
        $folderName = $this->getBackupFolderName();
        $filePath = $folderName . '/' . $getFilename;

        if ($disk->exists($filePath)) {
            $disk->delete($filePath);
            $notification = [
                'message' => 'Backup file deleted successfully!',
                'alert-type' => 'success'
            ];
            return redirect()->back()->with($notification);
        }

        $notification = [
            'message' => 'File not found!',
            'alert-type' => 'error'
        ];
        return redirect()->back()->with($notification);
    }
}
