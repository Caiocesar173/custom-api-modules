<?php

namespace Caiocesar173\Modules\Providers;

use Illuminate\Support\ServiceProvider;
use Caiocesar173\Modules\Contracts\RepositoryInterface;
use Caiocesar173\Modules\Laravel\LaravelFileRepository;

class ContractsServiceProvider extends ServiceProvider
{
    /**
     * Register some binding.
     */
    public function register()
    {
        $this->app->bind(RepositoryInterface::class, LaravelFileRepository::class);
    }
}
