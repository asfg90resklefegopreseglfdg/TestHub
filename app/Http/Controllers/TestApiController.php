<?php

namespace App\Http\Controllers;

use App\Test;
use Illuminate\Http\Request;

class TestApiController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getTestNames()
    {
        $testNamesAndTags = Test::select('id', 'name', 'slug')
            ->sortable()
            ->with(['tags:tag'])
            ->withCount(['statistics' => function($query) {
                $query->where('test_complete', true);
            }])
            ->simplePaginate(5);

        return response($testNamesAndTags);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $search = '%' . $request->input('search') . '%';
        $foundData = Test::select('id', 'name', 'slug')
            ->where('name', 'like', $search)
            ->orWhere('id', 'like', $search)
            ->sortable()
            ->with('tags:tag')
            ->withCount(['statistics' => function($query) {
                $query->where('test_complete', true);
            }])
            ->simplePaginate(5);
        return response($foundData);
    }
}
