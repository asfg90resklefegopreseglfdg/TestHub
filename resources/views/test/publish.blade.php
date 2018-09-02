@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="center-block">
                    <div class="card card-default">
                        <div class="card-header">
                            <div align="center"><h3>Тест успешно создан </h3></div>
                        </div>
                        <test-publish :user="{{ $user }}"
                                      :test="{{ $test }}"></test-publish>
                    </div>
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