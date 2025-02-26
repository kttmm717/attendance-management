<header class="header">
    <div class="header__container">
        <div class="header__logo">
            <a href=""><img src="{{asset('img/coachtech-logo.svg')}}"></a>
        </div>
        @if(!in_array(Route::currentRouteName(), ['register', 'login', 'verification.notice', 'admin.login']))
        <nav>
            <ul>
                @isset(Auth::user()->role)
                    @if(Auth::user()->role === 'staff')
                        <li><a href="/staff">勤怠</a></li>
                        <li><a href="/attendance/list">勤怠一覧</a></li>
                        <li><a href="/stamp_correction_request/list">申請</a></li>
                        <li>
                            <form action="{{route('logout')}}" method="post">
                                @csrf
                                <button>ログアウト</button>
                            </form>
                        </li>
                    @else
                        <li><a href="/admin">勤怠一覧</a></li>
                        <li><a href="/admin/staff/list">スタッフ一覧</a></li>
                        <li><a href="/stamp_correction_request/list">申請一覧</a></li>
                        <li>
                            <form action="{{route('logout')}}" method="post">
                                @csrf
                                <button>ログアウト</button>
                            </form>
                        </li>
                    @endif
                @endisset
            </ul>
        </nav>
        @endif
    </div>
</header>