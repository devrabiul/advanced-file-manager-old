<?php

namespace Devrabiul\AdvancedFileManager;

use Illuminate\Support\ServiceProvider;

class AdvancedFileManagerServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->register(FileManagerConfigServiceProvider::class);
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->registerPublishing();
        }
    }

    private function registerPublishing(): void
    {
        $this->publishes([
            __DIR__ . '/../config/advanced-file-manager.php' => config_path('advanced-file-manager.php'),
        ]);
    }

}
