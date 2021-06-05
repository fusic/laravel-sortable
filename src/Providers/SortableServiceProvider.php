<?php

namespace Sortable\Providers;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Sortable\Console\Commands\SortableGenerator;
use Sortable\Sortable;
use Sortable\SortableCore;

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
        $this->registerCommand();
    }

    public function boot()
    {
        Blade::directive('sort', function ($params) {
            return "<?php echo \Sortable\Assessor::render($params); ?>";
        });
    }

    protected function registerMacro()
    {
        QueryBuilder::macro('sort', function (Sortable $sortable, $query = null) {
            $core = new SortableCore();
            return $core->process($this, $sortable, $query);
        });

        EloquentBuilder::macro('sort', function (Sortable $sortable, $query = null) {
            $core = new SortableCore();
            return $core->process($this, $sortable, $query);
        });
    }

    protected function registerCommand()
    {
        $this->app->singleton('make.sortable', function ($app) {
            return new SortableGenerator($app['files']);
        });

        $this->commands([
                            'make.sortable'
                        ]);
    }
}
