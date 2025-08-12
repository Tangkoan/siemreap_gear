<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
   public function handle(): void
    {
        // កូដនេះនឹងសរសេរពេលវេលាបច្ចុប្បន្នចូលទៅក្នុងไฟล์ log មួយ
        $logFile = storage_path('logs/test_task.log');
        $currentTime = now()->toDateTimeString() . PHP_EOL;

        file_put_contents($logFile, $currentTime, FILE_APPEND);

        $this->info('Successfully wrote to test log file.');
    }
}
