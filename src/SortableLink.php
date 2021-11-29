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

        $query = Request::query();

        if (isset($params['default']) && isset($params['asc']) && isset($params['desc'])) {
            // Render a sort link with passed DOM template
            // Templates should be passed as $params['default'], $params['asc'], $params['desc']
            $link = $this->generateLinkWithDom($url, $params, $query);
        } else {
            $link = $this->generateLink($url, $params, $query);
        }

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
                // チェックボックス等で配列でクエリに入る場合を考慮
                if (is_array($item)) {
                    $arr = [];
                    foreach ($item as $k => $v) {
                        $arr[] = sprintf("%s=%s", urlencode($key . '['.$k.']'), urlencode($v));
                    }

                    return implode('&', $arr);
                } else {
                    return sprintf("%s=%s", urlencode($key), urlencode($item));
                }
            })
            ->toArray();

        if (count($queryStringList) > 0) {
            $generatedQuery = '?' . implode('&', $queryStringList);
        } else {
            $generatedQuery = '';
        }

        return $generatedQuery;
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

    private function generateLinkWithDom($url, $params, $query)
    {
        if (isset($query['sort']) && $params['key'] === $query['sort']) {
            $link = sprintf("<a href=\"%s\" class=\"sort-key\">%s</a>", $url, $params[$query['direction']]);
        } else {
            $link = sprintf("<a href=\"%s\" class=\"sort-key\">%s</a>", $url, $params['default']);
        }

        return $link;
    }

    private function generateLink($url, $params, $query)
    {
        if (isset($query['sort']) && $params['key'] === $query['sort']) {
            // if the item is a sort criterion, the direction is given to the class.
            $link = sprintf("<a href=\"%s\" class=\"sort-key %s\">%s</a>", $url, $query['direction'], $params['title']);
        } else {
            $link = sprintf("<a href=\"%s\" class=\"sort-key\">%s</a>", $url, $params['title']);
        }

        return $link;
    }
}
