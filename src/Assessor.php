<?php

namespace Sortable;

use Sortable\Requests\Sortable;

class Assessor
{
    public static function render($params)
    {
        $sortable = new Sortable();
        return $sortable->render($params);
    }
}