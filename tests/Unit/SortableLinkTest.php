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

    /**
     * testGenerateQuery
     *
     * @see Illuminate\Foundation\Testing\Concerns\MakesHttpRequests::call()
     * @dataProvider generateQueryProvider
     * @param  array $parameters
     * @param  array $baseKey
     * @param  string $exceptedQuery
     * @return void
     */
    public function testGenerateQuery($parameters, $baseKey, $exceptedQuery)
    {
        // create request by GET method
        $this->call('get', '/', $parameters);

        $result = $this->doMethod(new SortableLink(), 'generateQuery', $baseKey);

        $this->assertEquals($exceptedQuery, $result);
    }

    /**
     * generateQueryProvider
     *
     * @return array
     */
    public function generateQueryProvider(): array
    {
        return [
            // void baseKey
            [
                [],
                [null],
                // query genereated by nextSort()
                '?sort=&direction=asc'
            ],
            [
                [],
                [''],
                '?sort=&direction=asc'
            ],
            // single query
            [
                ['user_id' => 1],
                [''],
                '?user_id=1&sort=&direction=asc'
            ],
            [
                ['user_id' => 1],
                ['user_id'],
                '?user_id=1&sort=user_id&direction=asc'
            ],
            // multi query
            [
                ['user_id' => 1, 'status' => 2],
                [''],
                '?user_id=1&status=2&sort=&direction=asc'
            ],
            [
                ['user_id' => 1, 'status' => 2, 'id' => 3],
                ['user_id'],
                '?user_id=1&status=2&id=3&sort=user_id&direction=asc'
            ],
            // nested query
            // considering array in query strings
            [
                ['user_id' => [1, 2, 3]],
                [''],
                '?'
                    // considering encoding "[" => "%5B" and "]" => %5D, without "=" => %3D
                    // user_id[0]=1&user_id[1]=2&user_id[2]=3
                    . 'user_id%5B0%5D=1&user_id%5B1%5D=2&user_id%5B2%5D=3'
                    . '&sort=&direction=asc'
            ],
            [
                [
                    'user_id' => [4, 5],
                    'id' => [11, 22],
                    'status' => 1,
                ],
                ['user_id'],
                '?'
                    // user_id[0]=4&user_id[1]=5
                    . 'user_id%5B0%5D=4&user_id%5B1%5D=5'
                    // &id[0]=11&id[1]=22
                    . '&id%5B0%5D=11&id%5B1%5D=22'
                    . '&status=1'
                    . '&sort=user_id&direction=asc'
            ],
        ];
    }
}
