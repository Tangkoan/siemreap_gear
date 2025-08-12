<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


// ---------------------- Test ពីរនាទីម្ដង ------------------------ //

// ✅ សម្រាប់តេស្តបណ្ដោះអាសន្ន
// Schedule::command('backup:run --only-db --only-to-disk=google')->everyTwoMinutes()->withoutOverlapping();
Schedule::command('backup:run --only-db --only-to-disk=google')->everyTwoMinutes();

// Daily DB only @ 01:00
Schedule::command('backup:run --only-db --only-to-disk=google')->dailyAt('01:00')->withoutOverlapping();


// Weekly full @ Sun 02:00 → Google only
Schedule::command('backup:run --only-to-disk=google')
    ->weeklyOn(0, '02:00')->withoutOverlapping();

// Cleanup & Monitor → Google only
// Schedule::command('backup:clean --only-to-disk=google')->dailyAt('03:00');
// Schedule::command('backup:monitor --only-to-disk=google')->dailyAt('04:00');




// ✅ Cleanup សម្រាប់តេស្ត
// Schedule::command('backup:clean')->everyTwoMinutes()->withoutOverlapping();
Schedule::command('backup:clean')->everyTwoMinutes();
