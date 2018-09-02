@extends('layouts.app')

@section('content')

                        <test-passing :test="{{$test}}"
                                      :test-name="'{{$test->name}}'"
                                      :duration="{{$test->duration}}"
                                      :test-id="{{$test->id}}"
                                      :questions="{{$test->questions}}"
                                      :slug="'{{$test->slug}}'">
                        </test-passing>

@endsection