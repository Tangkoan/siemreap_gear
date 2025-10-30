<?php

namespace App\Http\Controllers;

use App\Jobs\BackupProjectJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Finder\SplFileInfo;

class BackupController extends Controller
{
    // private function getBackupDisk()
    // {
    //     $diskName = config('backup.backup.destination.disks')[0];
    //     return Storage::disk($diskName);
    // }
    private function getBackupDisk()
    {
        return Storage::disk('backups'); // ⬅️ បញ្ជាក់ local disk តែម្ខាង
    }

    private function getBackupFolderName()
    {
        return str_replace(' ', '-', config('app.name'));
    }

    public function databaseBackup()
    {
        return view('admin.backup.db_backup');
    }

    public function searchBackups(Request $request)
    {
        $disk = $this->getBackupDisk();
        $folderName = $this->getBackupFolderName();
        $search = $request->search;

        $allFiles = collect($disk->files($folderName))
            ->filter(fn ($file) => pathinfo($file, PATHINFO_EXTENSION) === 'zip')
            ->map(fn ($file) => new SplFileInfo($disk->path($file), '', ''))
            ->sortByDesc(fn ($file) => $file->getMTime());

        if ($search) {
            $allFiles = $allFiles->filter(fn ($file) => stripos($file->getFilename(), $search) !== false);
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
                    ? number_format($sizeInBytes / (1024 * 1024), 2).' MB'
                    : number_format($sizeInBytes / 1024, 2).' KB';

                $path = $file->getPath();
                $downloadUrl = route('backup.download', ['getFilename' => $filename]);
                $deleteUrl = route('backup.delete', ['getFilename' => $filename]);

                $table .= '
                    <tr class=" border-b border-primary">
                        <td class="p-2  text-sm text-defalut">'.($startIndex + $key + 1).'</td>
                        <td class="p-2 text-sm text-defalut">'.$filename.'</td>
                        <td class="p-2 text-sm text-defalut">'.$size.'</td>
                        <td class="p-2 text-sm text-defalut">'.$path.'</td>
                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                            <div class="flex items-center gap-x-2">
                                <a href="'.$downloadUrl.'" class="icon-download inline-flex items-center px-3 py-1 text-white text-sm rounded-md transition-colors duration-200" title="Download">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" /></svg>
                                </a>
                                <a href="'.$deleteUrl.'" id="delete" class="icon-delete inline-flex items-center px-3 py-1 text-white text-sm rounded-md transition-colors duration-200" title="Delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                </a>
                            </div>
                        </td>
                    </tr>';
            }
        }

        $pagination = '';
        if (! $isAll) {
            $pagination = '<div class="text-sm text-slate-500 p-4">Showing '.$paginatedFiles->count().' of '.$allFiles->count().' results.</div>';
        }

        return response()->json(['table' => $table, 'pagination' => $pagination]);
    }

    // ============ Fuction នេះសម្រាប់ Backup ដោយដៃគឺចូលតែ Local Drive ទេមិនចូល Google Dirve ឡើយ
    public function backupNow()
    {
        try {
            // បញ្ជូន​ការ Backup មូលដ្ឋាន​ទិន្នន័យ​ទៅ​គ្រប់​ទីតាំង​ដែល​បាន​កំណត់
            Artisan::queue('backup:run', [
                '--only-db' => true,
                '--disable-notifications' => true,
            ]);

            Log::info('Database backup job queued to all configured disks.');

            return back()->with('start_backup_check', true);
        } catch (\Exception $e) {
            Log::error('Failed to queue DB backup: '.$e->getMessage());

            return back()->with(['notification' => ['message' => 'Failed to start database backup.', 'alert-type' => 'error']]);
        }
    }
    // ==================================== Function នេះគឺដំណើរការ Backup ដោយដៃប៉ុន្ដែគឺចូលទាំង Local Drive និង Google Drive ========================
    // public function backupNow()
    //     {
    //         try {
    //             Artisan::call('backup:run', [
    //                 '--only-db'               => true,
    //                 '--disable-notifications' => true,
    //             ]);

    //             // ស្វែងរកឯកសារ .zip ថ្មីបំផុតនៅលើ local
    //             $diskLocal   = Storage::disk('backups');
    //             $folderName  = str_replace(' ', '-', config('app.name'));
    //             $files       = collect($diskLocal->files($folderName))
    //                             ->filter(fn($f) => str_ends_with($f, '.zip'))
    //                             ->sortByDesc(fn($f) => $diskLocal->lastModified($f));
    //             $latest      = $files->first();

    //             if ($latest) {
    //                 $stream = $diskLocal->readStream($latest);
    //                 Storage::disk('google')->put(basename($latest), $stream);
    //                 if (is_resource($stream)) fclose($stream);
    //             }

    //             return back()->with('start_backup_check', true);
    //         } catch (\Exception $e) {
    //             Log::error('Failed to run+upload DB backup: '.$e->getMessage());
    //             return back()->with(['notification' => ['message' => 'Failed to start database backup.', 'alert-type' => 'error']]);
    //         }
    //     }

    public function getBackupStatus()
    {
        $disk = $this->getBackupDisk();
        $folderName = $this->getBackupFolderName();
        $files = $disk->files($folderName);

        if (empty($files)) {
            return response()->json(['status' => 'pending']);
        }

        $latestFile = collect($files)->sortByDesc(fn ($file) => $disk->lastModified($file))->first();

        if (! $latestFile) {
            return response()->json(['status' => 'pending']);
        }

        $lastModified = $disk->lastModified($latestFile);
        if (time() - $lastModified < 15) {
            return response()->json(['status' => 'completed', 'message' => 'Database Backup Successfully!', 'alert-type' => 'success']);
        }

        return response()->json(['status' => 'pending']);
    }

    public function backupProject()
    {
        try {
            BackupProjectJob::dispatch();
            Log::info('Project backup job has been queued successfully.');

            return redirect()->back()->with('start_project_backup_check', true);
        } catch (\Exception $e) {
            Log::error('Failed to queue the project backup job: '.$e->getMessage());

            return redirect()->back()->with(['notification' => ['message' => 'Failed to start project backup.', 'alert-type' => 'error']]);
        }
    }

    public function getProjectBackupStatus()
    {
        $disk = Storage::disk('local');
        $folderPath = 'project-backups';
        $files = $disk->files($folderPath);

        if (empty($files)) {
            return response()->json(['status' => 'pending']);
        }

        $latestFile = collect($files)->sortByDesc(fn ($file) => $disk->lastModified($file))->first();

        if (! $latestFile) {
            return response()->json(['status' => 'pending']);
        }

        $lastModified = $disk->lastModified($latestFile);
        if (time() - $lastModified < 60) {
            return response()->json(['status' => 'completed', 'message' => 'Project Backup Successfully!', 'alert-type' => 'success']);
        }

        return response()->json(['status' => 'pending']);
    }

    public function downloadBackup($getFilename)
    {
        $disk = $this->getBackupDisk();
        $folderName = $this->getBackupFolderName();
        $filePath = $folderName.'/'.$getFilename;

        if ($disk->exists($filePath)) {
            return $disk->download($filePath);
        }
        abort(404, 'File not found.');
    }

    public function deleteBackup($getFilename)
    {
        $disk = $this->getBackupDisk();
        $folderName = $this->getBackupFolderName();
        $filePath = $folderName.'/'.$getFilename;

        if ($disk->exists($filePath)) {
            $disk->delete($filePath);

            return redirect()->back()->with(['notification' => ['message' => 'Backup file deleted!', 'alert-type' => 'success']]);
        }

        return redirect()->back()->with(['notification' => ['message' => 'File not found!', 'alert-type' => 'error']]);
    }

    // Backup Project
    /**
     * ✅ [ថ្មី] ស្វែងរក និងបង្ហាញបញ្ជី Project Backups សម្រាប់ AJAX
     */
    public function searchProjectBackups(Request $request)
    {
        $disk = Storage::disk('local');
        $folderName = 'project-backups';
        $search = $request->search;

        $allFiles = collect($disk->files($folderName))
            ->filter(fn ($file) => pathinfo($file, PATHINFO_EXTENSION) === 'zip')
            ->map(fn ($file) => new SplFileInfo($disk->path($file), '', ''))
            // ✅ [កែសម្រួល] បានប្តូរពី sortByDesc ទៅ sortBy ដើម្បីឱ្យលេខរៀងចាប់ពី 1, 2, 3...
            ->sortBy(fn ($file) => $file->getMTime());

        if ($search) {
            $allFiles = $allFiles->filter(fn ($file) => stripos($file->getFilename(), $search) !== false);
        }

        $table = '';
        if ($allFiles->isEmpty()) {
            $table = '<tr><td colspan="5" class="text-center p-5 text-slate-500">No project backup files found.</td></tr>';
        } else {
            foreach ($allFiles as $key => $file) {
                $filename = $file->getFilename();
                $size = number_format($file->getSize() / (1024 * 1024), 2).' MB';
                $path = $file->getPath();
                $downloadUrl = route('backup.project.download', ['filename' => $filename]);
                $deleteUrl = route('backup.project.delete', ['filename' => $filename]);

                $table .= '
                    <tr class="hover:bg-slate-50 dark:hover:bg-gray-700 border-b border-slate-200 dark:border-gray-700">
                        <td class="p-2  text-sm text-defalut">'.($key + 1).'</td>
                        <td class="p-2 text-sm text-defalut">'.$filename.'</td>
                        <td class="p-2 text-sm text-defalut">'.$size.'</td>
                        <td class="p-2 text-sm text-defalut">'.$path.'</td>
                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                            <div class="flex items-center gap-x-2">
                                <a href="'.$downloadUrl.'" class="icon-download inline-flex items-center px-3 py-1 text-white text-sm rounded-md transition-colors duration-200" title="Download"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" /></svg></a>
                                <a href="'.$deleteUrl.'" id="delete" class="icon-delete inline-flex items-center px-3 py-1 text-white text-sm rounded-md transition-colors duration-200" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg></a>
                            </div>
                        </td>
                    </tr>';
            }
        }

        return response()->json(['table' => $table]);
    }

    /**
     * ✅ [ថ្មី] Download  Project Backup
     */
    public function downloadProjectBackup($filename)
    {
        $disk = Storage::disk('local');
        $filePath = 'project-backups/'.$filename;

        if ($disk->exists($filePath)) {
            return $disk->download($filePath);
        }
        abort(404, 'File not found.');
    }

    /**
     * ✅ [ថ្មី] លុប Project Backup
     */
    public function deleteProjectBackup($filename)
    {
        $disk = Storage::disk('local');
        $filePath = 'project-backups/'.$filename;

        if ($disk->exists($filePath)) {
            $disk->delete($filePath);

            return redirect()->back()->with(['notification' => ['message' => 'Project backup file deleted!', 'alert-type' => 'success']]);
        }

        return redirect()->back()->with(['notification' => ['message' => 'File not found!', 'alert-type' => 'error']]);
    }
}
