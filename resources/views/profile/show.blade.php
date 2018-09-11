@extends('layouts.content')

@section('content')
    <div class="card card-default">
        <div class="card-header">
            <div align="center"><h3>Профиль юзера: {{$user->username}}</h3></div>
        </div>
        <div class="container">
            <div class="border-bottom">
                <p class="font-weight-normal">
                Зарегистрирован с: {{$user->created_at}}
                    <br>
                Всего тестов: {{$user->tests->count()}}</p>
            </div>
            <div class="row justify-content-center">
                @foreach($user->tests as $test)
                    <div class="col-md-4 py-2" onclick="event.preventDefault();
                                                window.location.href = '/test/{{$test->slug}}';">
                        <div class="card border-secondary card-hover">
                            <div class="card-body">
                                <div class="card-title">
                                    <h4>{{ $test->name }}</h4>
                                </div>
                                <div class="card-text clip">
                                    {{ $test->description }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
