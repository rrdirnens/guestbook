<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\GuestbookMessageRepository;
use App\Repositories\GuestbookMessageRepositoryInterface;
use App\Services\GuestbookMessageService;
use App\Services\GuestbookMessageServiceInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(GuestbookMessageRepositoryInterface::class, GuestbookMessageRepository::class);
        $this->app->bind(GuestbookMessageServiceInterface::class, GuestbookMessageService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
