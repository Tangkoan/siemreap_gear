<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\informationshop; // <-- Import Model
use Illuminate\Support\Facades\Schema; // <-- Import Schema
use Illuminate\Support\Facades\Cache; // <-- 1. បន្ថែមទីនេះ


class ComposerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (Schema::hasTable('informationshops')) {
                
                // 2. កែ logic នេះ
                $shopInfo = Cache::remember('shopInfo', 3600, function () { // 3600 វិនាទី = 1 ម៉ោង
                    return informationshop::first();
                });

                $view->with('shopInfo', $shopInfo);
            } else {
                $view->with('shopInfo', null);
            }
        });
    }

    public function register(): void
    {
        //
    }
}