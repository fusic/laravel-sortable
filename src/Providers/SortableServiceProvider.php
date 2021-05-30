<?php

namespace Sortable\Providers;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Sortable\Sortable;

class SortableServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerMacro();
    }

    public function boot()
    {
        Blade::directive('sort', function ($params) {
            return "<?php echo \Sortable\Assessor::render($params); ?>";
        });
    }

    protected function registerMacro()
    {
        QueryBuilder::macro('sort', function(Sortable $search, $query = null) {
            return $search->process($this, $query);
        });

        EloquentBuilder::macro('sort', function(Sortable $search, $query = null) {
            return $search->process($this, $query);
        });
    }
}