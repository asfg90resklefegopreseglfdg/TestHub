@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="center-block">
                    <div class="card card-default">
                        <div class="card-header">
                            <div align="center"><h3>Создание теста</h3></div>
                        </div>
                        <test-create></test-create>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-default" align="center">
                    <p>Вы можете создавать тесты без регистрации, но если вы зарегистрируетесь, то легко сможете
                        управлять своими тестами и просматривать результаты.</p>
                    <p>Если вы сейчас перейдете к <a href="/register">регистрации</a>, то введенные вами
                        данные не потеряются.(пока потеряются)</p>
                    <p>Также, после создания теста вы сможете указать e-mail, чтобы получать уведомления о сдаче тестов
                        и получите ссылку для просмотра результатов.</p>
                </div>
            </div>
        </div>
    </div>
@endsection