@extends('layouts.app')

@section('layoutContent')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 col-lg-8">
                @yield('content')
            </div>
            <div class="col-md-12 col-lg-3">
                @if ( request()->getRequestUri() !== '/test/create' and preg_match("@\/test\/.+\/questions@", request()->getRequestUri()) != 1)
                    <a href="{{ route('test.create') }}" class="col-xl-12 btn btn-success py-3 mb-1"> Создать тест </a>
                @endif
                <div class="card">
                    @guest
                        <div class="car-body py-2" align="center">
                            @php $a = rand(1,2) @endphp
                            @if ($a === 1)
                                <p>Тест хаб это сайт, который позволят легко создавать тесты и просматривать статистику их
                                    прохождения.
                                    Для создания и прохождения тестов не требуется регистрация.</p>
                            @elseif ($a === 2)
                                <p>Вы можете походить тесты без регистрации, но если вы зарегистрируетесь, то все ваши
                                    созднные тесты и результаты пройденных тестов сохранятся.</p>
                            @endif
                            @yield('sidebar')
                        </div>
                    @else
                        <a class="text-secondary" href="{{ route('profile.show', Auth::user()->username) }}">
                            <div class="card-header" align="center">
                                <h3>{{Auth::user()->username}}</h3>
                            </div>
                        </a>
                        <div class="list-group ">
                            <a href="#" class="list-group-item d-flex justify-content-between align-items-center list-group-item-action">
                                Мои тесты
                                <span class="badge badge-primary badge-pill">0</span>
                            </a>
                            <a href="#" class="list-group-item d-flex justify-content-between align-items-center list-group-item-action">
                                Мои прохождения
                            </a>
                        </div>
                        @yield('sidebar')
                    @endguest
                </div>
            </div>
        </div>
    </div>
@endsection

