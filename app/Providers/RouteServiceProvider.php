<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;

class RouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Register custom middleware
        $router = $this->app['router'];
        $router->aliasMiddleware('admin', AdminMiddleware::class);
    }
}
