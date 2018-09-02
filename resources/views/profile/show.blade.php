@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="center-block">
                    <div class="card card-default">
                        <div class="card-header">
                            <div align="center"><h3>Профиль юзера: {{$user->username}}</h3></div>
                        </div>
                            <div class="container">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col"> Очки</th>
                                        <th scope="col"> Старт теста</th>
                                        <th scope="col"> Окончание теста</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($user->tests as $test)
                                    <tr>
                                        <td>{{ $test->name }}</td>
                                        <td>{{ $test->created_at}}</td>
                                        <td>{{ $test->updated_at}}</td>
                                    </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection