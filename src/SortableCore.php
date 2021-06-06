<?php

namespace Sortable;

use Illuminate\Support\Facades\Request;

class SortableCore
{
    private $formatedList = [];

    public function process($builder, Sortable $sortable, $query = null)
    {
        if (is_null($query)) {
            $query = Request::query();
        }

        $params = $sortable->getParams();

        $this->formatParams($params);

        $requestSortKey = $query['sort'] ?? null;
        $requestSortDirection = $query['direction'] ?? null;

        $ret = $this->checkTarget($requestSortKey, $requestSortDirection);
        if ($ret === false) {
            return $builder;
        }

        $target = $this->formatedList[$requestSortKey];
        if (is_callable($target)) {
            return $target($builder, $query, $requestSortKey, $requestSortDirection);
        } else {
            return $builder
                ->orderBy($target, $query['direction']);
        }
    }

    private function checkTarget($sortKey, $sortDirection)
    {
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

    private function formatParams($params)
    {
        foreach ($params as $key => $value) {
            if (is_int($key)) {
                $this->formatedList[$value] = $value;
            } else {
                $this->formatedList[$key] = $value;
            }
        }
    }
}
