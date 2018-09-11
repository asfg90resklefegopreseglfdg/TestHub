@extends('layouts.content')

@section('content')
    <div class="card card-default">
        <div class="card-header">
            <div align="center"><h3>Тест успешно создан </h3></div>
        </div>
        <test-publish :user="{{ $user }}"
                      :test="{{ $test }}"></test-publish>
    </div>
@endsection
