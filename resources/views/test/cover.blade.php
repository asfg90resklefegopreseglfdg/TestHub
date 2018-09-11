@extends('layouts.content')

@section('content')
    <div class="card card-default">
        <div class="card-header">
            <div align="center"><h3>Тест {{$test['name']}}</h3></div>
        </div>
        <test-cover :test="{{ $test }}"></test-cover>
    </div>
@endsection
