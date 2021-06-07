<?php

namespace Sortable;

use Illuminate\Support\Facades\Request;

class SortableLink
{
    public function render($params)
    {
        $base = Request::getBasePath();
        $path = Request::getPathInfo();
        $full = sprintf("%s%s", $base, $path);

        $queryString = $this->generateQuery($params['key']);
        $url = sprintf("%s%s", $full, $queryString);

        $link = sprintf("<a href=\"%s\">%s</a>", $url, $params['title']);
        return $link;
    }

    private function generateQuery($baseKey)
    {
        $query = Request::query();
        unset($query['sort'], $query['direction']);

        $sort = Request::get('sort');
        $direction = Request::get('direction');


        $sortList = $this->nextSort($baseKey, $sort, $direction);

        $list = $query + $sortList;

        $queryStringList = collect($list)
            ->map(function ($item, $key) {
                return sprintf("%s=%s", urlencode($key), urlencode($item));
            })
            ->toArray();

        return '?' . implode('&', $queryStringList);
    }

    private function nextSort($baseKey, $sort, $direction)
    {
        $default = [
            'sort' => $baseKey,
            'direction' => 'asc'
        ];
        if ($sort === null || $direction === null) {
            return $default;
        }

        // @todo : カラム名のバリデーション
        if (!in_array($direction, ['asc', 'desc'])) {
            return $default;
        }

        if ($baseKey !== $sort) {
            return $default;
        }

        $sort = [];
        switch ($direction) {
            case 'asc':
                $sort['sort'] = $baseKey;
                $sort['direction'] = 'desc';
                break;
        }

        return $sort;
    }
}
