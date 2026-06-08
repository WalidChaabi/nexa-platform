<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class CacheController extends Controller
{

    public function clearRouteCache()
    {
        Artisan::call('route:clear');
        return 'Route cache cleared';
    }
    //clear config cache
    public function clearConfigCache()
    {
        Artisan::call('config:cache');
        return 'Route cache cleared';
    }
    // clear application cache
    public function clearAppCache()
    {
        Artisan::call('cache:clear');
        return 'Route cache cleared';
    }

    // clear view cache
    public function clearViewCache()
    {
        Artisan::call('view:clear');
        return 'Route cache cleared';
    }
}
