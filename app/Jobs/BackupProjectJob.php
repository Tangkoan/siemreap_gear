<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use ZipArchive;

class BackupProjectJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // ទីតាំងដើមរបស់ Project
            $sourcePath = base_path();

            // ទីតាំងសម្រាប់រក្សាទុកไฟล์ Backup
            $destinationPath = storage_path('app/project-backups/');
            
            // បង្កើត Folder បើវាមិនទាន់មាន
            File::ensureDirectoryExists($destinationPath);

            // បង្កើតឈ្មោះไฟล์ Zip ដែលមានតែមួយគត់
            $filename = 'project-backup-' . date('Y-m-d-His') . '.zip';
            $zipFilePath = $destinationPath . $filename;

            // បង្កើត instance របស់ ZipArchive
            $zip = new ZipArchive();
            if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
                throw new \Exception("Cannot open <$zipFilePath>");
            }

            // បង្កើត Recursive Iterator
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($sourcePath, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::LEAVES_ONLY
            );

            // Folder និងไฟล์ដែលត្រូវមិនអើពើ (Exclude)
            $excludePaths = [
                storage_path(),       // មិន Backup storage ทั้งหมด
                base_path('vendor'),  // មិន Backup vendor
                base_path('node_modules'), // មិន Backup node_modules
                $zipFilePath,         // មិន Backup ตัวไฟล์ Zip ខ្លួនឯង
                base_path('.git'),
                base_path('.env'),
            ];

            foreach ($files as $name => $file) {
                // មិនយក Directory
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($sourcePath) + 1);

                    // ពិនិត្យមើលថា path នេះមិនស្ថិតនៅក្នុងรายการ exclude
                    $shouldExclude = false;
                    foreach ($excludePaths as $excludePath) {
                        if (strpos($filePath, $excludePath) === 0) {
                            $shouldExclude = true;
                            break;
                        }
                    }

                    if (!$shouldExclude) {
                        $zip->addFile($filePath, $relativePath);
                    }
                }
            }

            $zip->close();
            Log::info("Project backup created successfully: {$filename}");

        } catch (\Exception $e) {
            Log::error("Project backup failed: " . $e->getMessage());
        }
    }
}
