@extends('layouts.content')

@section('content')
    <test-passing
            :test="{{$test}}"
            :test-name="'{{$test->name}}'"
            :test-id="{{$test->id}}"
            :questions="{{$test->questions}}"
            :slug="'{{$test->slug}}'">
    </test-passing>
@endsection

@section('sidebar')
    <div align="center">
        <test-timer
                :duration="{{$test->duration}}"
                :test-id="{{$test->id}}">
        </test-timer>
    </div>
@endsection
