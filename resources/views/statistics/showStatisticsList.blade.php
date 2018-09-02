@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="center-block">
                    <div class="card card-default">
                        <div class="card-header">
                            <div align="center"><h3>Статистика теста: {{$test['name']}}</h3></div>
                        </div>
                        <div class="container">
                            @if ($statistics[0])
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col"> @sortablelink('user.username', 'Юзер')</th>
                                        <th scope="col"> @sortablelink('points', 'Очки')</th>
                                        <th scope="col"> @sortablelink('created_at', 'Старт теста')</th>
                                        <th scope="col"> @sortablelink('updated_at', 'Окончание теста')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($statistics as $statistic)
                                        <tr>
                                            <td>
                                                @if (empty($statistic->user->username))
                                                    Неизвестный
                                                @else
                                                    {{$statistic->user->username}}
                                                @endif
                                            </td>
                                            <td>{{ $statistic->points }}</td>
                                            <td>{{ $statistic->created_at}}</td>
                                            <td>{{ $statistic->updated_at}}</td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p align="center">Тест еще никто не прошел</p>
                            @endif
                        </div>
                    </div>
                    {{ $statistics->appends(request()->input())->links()}}
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-default" align="center">
                    <p>Вы можете походить тесты без регистрации, но если вы зарегистрируетесь, то все ваши
                        созднные тесты и результаты пройденных тестов сохранятся.</p>
                    <p><a href="/register">Зарегистрироваться</a></p>
                </div>
            </div>
        </div>
    </div>
@endsection