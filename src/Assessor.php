<?php

namespace Sortable;

use Sortable\Requests\SortableLink;

class Assessor
{
    public static function render($params)
    {
        $sortable = new SortableLink();
        return $sortable->render($params);
    }
}
