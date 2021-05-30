<?php

namespace Sortable\Database;

class Sortable
{
    public function process($builder, $query = null)
    {
        dump($query);
        dd($builder);
        if (is_null($query)) {
            $query = Request::query();
        }
    }
}