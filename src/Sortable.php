<?php

namespace Sortable;

use Illuminate\Support\Facades\Request;

class Sortable
{
    protected $params = [];

    public function getParams(): array
    {
        return $this->params;
    }
}
