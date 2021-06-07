<?php

namespace Sortable;

use Sortable\SortableLink;

class Accessor
{
    public static function render($params)
    {
        $sortable = new SortableLink();
        return $sortable->render($params);
    }
}
