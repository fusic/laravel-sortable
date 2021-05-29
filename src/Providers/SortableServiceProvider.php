<?php

namespace Sortable\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class SortableServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Blade::directive('sort', function ($params) {
            return "<?php echo \Sortable\Sortable::render($params); ?>";
        });
    }
}