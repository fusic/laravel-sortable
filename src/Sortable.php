<?php

namespace Sortable;

use Illuminate\Support\Facades\Request;

class Sortable
{
    private $formatedList = [];

    public function process($builder, $query = null)
    {
        if (is_null($query)) {
            $query = Request::query();
        }

        $this->formatParams();

        $ret = $this->checkTarget($query);
        if ($ret === false) {
            return $builder;
        }

        return $builder
            ->orderBy($query['sort'], $query['direction']);
    }

    private function checkTarget(array $query)
    {
        $sortKey = $query['sort'] ?? null;
        $sortDirection = $query['direction'] ?? null;
        if ($sortKey === null | $sortDirection === null) {
            return false;
        }

        if (!isset($this->formatedList[$sortKey])) {
            return false;
        }
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            return false;
        }

        return true;
    }

    private function formatParams()
    {
        foreach ($this->params as $key => $value)
        {
            if (is_int($key)) {
                $this->formatedList[$value] = $value;
            } else {
                $this->formatedList[$key] = $value;
            }
        }
    }
}