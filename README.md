# Laravel-Sortable
Easily generate a link to switch the sort state for `<table>` and so on.

## Install
Add this repository to `composer.json` then `$ composer install`.
```
"repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:fusic/laravel-sortable.git"
        }
    ]
```

Register the Service Provider to `config/app.php`.
```
'providers' => [
    ...
    /*
    * Package Service Providers...
    */
    Sortable\Providers\SortableServiceProvider::class,
    ...
]
```

## Usage
### Create Sortable class
Execute artisan `make:sortable` command.

```
$ php artisan make:sortable EmployeeSortable
```

### Settings
Write sort keys in the created `Sortable` class.

```
<?php

namespace App\Sort;

use Sortable\Sortable;

class EmployeeSortable extends Sortable
{
    public function __construct()
    {
        $this->params = [  // write here!
            'employee_number',
            'name' => 'Employees.fullname',  // URL parameter => column name
            'birthday' => function ($builder, $query, $sortKey, $sortDirection) {
                // write custom 'order by' condition
                return $builder->orderBy('foo', 'desc');
            },
        ];
    }
}
```

### Insert a Sortable object to the query

```
$employees = Employees::query()
    ->where('foo', 'baz')
    /** what you want... **/
    ->sort(new EmployeeSortable())
    /** ... **/
    ;
```

This will add the sort condition to the query. `Sortable` can be used in both Eloquent and Query Builder.

### Use @sort directive in the blade
#### Generate a simple link
```
@sort(['title' => 'Name', 'key' => 'name', 'url' => '#'])
```

This `@sort` directive will generate a link to switch the sort state like this:

```
<a href="https://www.example.com/index?sort=name&direction=asc" class="sort-key">Name</a>
```

The state switches in the order of ascending, descending, and unsorting, the generated links follow the order.

Usage example:

```
<table>
    <thead>
        <tr>
            <th>@sort(['title' => 'Number', 'key' => 'employee_number', 'url' => '#'])</th>
            <th>@sort(['title' => 'Name', 'key' => 'name', 'url' => '#'])</th>
            <th>@sort(['title' => 'Birthday', 'key' => 'birthday', 'url' => '#'])</th>
        </tr>
    </thead>
    <tbody>
        @foreach($employees as $employee)
        <tr>
            <td>{{ $employee->employee_number }}</td>
            <td>{{ $employee->fullname }}</td>
            <td>{{ $employee->birthday->format('Y/m/d') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
```

#### Generate a link with custom DOM template
Passing the DOM templates corresponding to each sort state to `@sort` directive, `Sortable` will generate links according to it.

```
@sort([
    'key' => 'name',
    'default' => '<span class="text-black">Name</span>',
    'asc' => '<span class="text-red asc">Name↑</span>',
    'desc' => '<span class="text-blue desc">Name↓</span>',
])
```

This will generate a custom link:

```
<a href="https://www.example.com/index?sort=name&direction=asc" class="sort-key"><span class="text-red asc">Name↑</span></a>
```