@extends('layouts.content')

@section('content')
    <div class="card card-default">
        <div class="card-header">
            <div align="center"><h3>Ваш результат по тесту: {{$statistic->test->name}}</h3></div>
        </div>
        <div class="container">
            @if ($statistic->test->passing_public)
                <div>
                    <a href="/stats/test/{{$statistic->test->id}}">
                        Посмотреть статистику по тесту
                    </a>
                </div>
            @endif
            @if ($statistic->test->answers_public)
                <div>
                    <a href="/stats/test/{{$statistic->test->id}}">
                        Посмотреть правильные ответы
                    </a>
                </div>
            @endif
        </div>
        <br>
        <table class="table">
            <thead>
            <tr>
                <th scope="col"> Очки</th>
                <th scope="col"> Старт теста</th>
                <th scope="col"> Окончание теста</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>{{ $statistic->points }}</td>
                <td>{{ $statistic->created_at}}</td>
                <td>{{ $statistic->updated_at}}</td>
            </tr>
            </tbody>
        </table>
    </div>
@endsection
