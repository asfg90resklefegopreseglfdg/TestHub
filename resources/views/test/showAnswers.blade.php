@extends('layouts.app')

@section('content')
    <test-show-answers :test-name="'{{$test['name']}}'"
                             :questions="{{$test->questions}}"></test-show-answers>
@endsection