<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// ============ Test ពីរនាទីម្ដង ============== //
    // Daily DB only @ 03:11
    // រត់ Backup database ម្ដងក្នុងមួយថ្ងៃ នៅម៉ោង 3:30 PM (15:30)
        Schedule::command('backup:run --only-db --only-to-disk=google')
             ->dailyAt('17:20')
             ->withoutOverlapping();

    // Clean up old backups ម្ដងក្នុងមួយថ្ងៃ បន្ទាប់ពី backup បានជោគជ័យ
        Schedule::command('backup:clean')
            ->dailyAt('17:25') // កំណត់ cleanup (5 នាទីបន្ទាប់ពី backup)
            ->withoutOverlapping();


    // Weekly full @ Sun 02:00 → Google only
        Schedule::command('backup:run --only-to-disk=google')
            ->weeklyOn(0, '17:30')
            ->withoutOverlapping();

    // Weekly full @ Sun 02:00 → Google only
        Schedule::command('backup:clean')
            ->weeklyOn(0, '17:30')
            ->withoutOverlapping();




// ================== Code សម្រាប់ Test 2នាទីម្ដង ===================
    // // ✅ សម្រាប់តេស្តបណ្ដោះអាសន្ន
    // Schedule::command('backup:run --only-db --only-to-disk=google')->everyTwoMinutes();
    // // ✅ Cleanup សម្រាប់តេស្ត
    // Schedule::command('backup:clean')->everyTwoMinutes()->withoutOverlapping();