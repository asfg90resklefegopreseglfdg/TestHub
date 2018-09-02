@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="center-block">
                        <div class="card card-default">
                            <div class="card-header">
                                <div align="center"><h3>Тесты</h3></div>
                            </div>
                            <tests-list></tests-list>
                        </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-default" align="center">
                    <h2> О сайте</h2>
                    <p>Тест хаб - это сайт, который позволят легко создавать тесты и просматривать их стату в удобном
                        интерфейсе. Для создания и прохождения тестов не требует регистрация, но если вы зарегитесь то
                        легко сможете управлять своими тестами.</p>

                </div>
            </div>
        </div>
    </div>
@endsection
