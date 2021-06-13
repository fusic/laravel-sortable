<?php
namespace Tests\Unit;

use Illuminate\Support\Facades\Request;
use Sortable\SortableLink;
use Tests\TestCase;

class SortableLinkTest extends TestCase
{
    protected function setUp() :void
    {
        parent::setUp();
        // $parameters = ['sort' => 'user_id', 'direction' => 'asc'];
        // Request::replace($parameters);
    }

    /**
     * test_value_filter
     *
     * @dataProvider nextSortProvider
     */
    public function test_nextsort($data, $excepted)
    {
        $link = new SortableLink();
        $ret = $this->doMethod($link, 'nextSort', $data);
        $this->assertEquals($excepted, $ret);
    }

    /**
     * isPlusメソッドのテストデータ
     */
    function nextSortProvider(): array {
        return [
            [
                ['baseKey' => 'user_id', 'sort' => 'user_id', 'direction' => 'asc'],
                ['sort' => 'user_id', 'direction' => 'desc']
            ],
            [
                ['baseKey' => 'user_id', 'sort' => 'user_id', 'direction' => 'desc'],
                []
            ],
            [
                ['baseKey' => 'user_id', 'sort' => null, 'direction' => null],
                ['sort' => 'user_id', 'direction' => 'asc']
            ],
            [
                ['baseKey' => 'user_id', 'sort' => 'example', 'direction' => 'asc'],
                ['sort' => 'user_id', 'direction' => 'asc']
            ],
            [
                ['baseKey' => 'user_id', 'sort' => 'example', 'direction' => 'desc'],
                ['sort' => 'user_id', 'direction' => 'asc']
            ]
        ];
    }
}
