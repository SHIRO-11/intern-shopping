<header class="mb-4">
    {{--  管理者のみ  --}}
    @can('admin-only')
    <nav class="navbar navbar-expand-md navbar-dark shadow-sm" style="background-color:#b8b8b8;">
        <h1><a class="navbar-brand" href="/">管理画面</a></h1>

        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#nav-bar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="nav-bar">
            <ul class="navbar-nav mr-auto"></ul>
            <ul class="navbar-nav">
                {{--  検索  --}}
                <form class="form-inline" action="search" method="get">
                    <input name="search" class="form-control mr-sm-2" type="search" placeholder="Search"
                        aria-label="Search">
                    <button class="btn btn-sm btn-outline-light my-2 my-sm-0" type="submit">Search</button>
                </form>

                {{--  ドロップダウン  --}}
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">{{ Auth::user()->name }}</a>
                    <ul class="dropdown-menu dropdown-menu-right">
                        {{-- ログアウトへのリンク --}}
                        <li class="dropdown-item">{!! link_to_route('logout.get', 'ログアウト') !!}</li>
                        <li class="dropdown-item"><a href="{{route('admin.products.create')}}">商品作成</a></li>
                        <li class="dropdown-item"><a href="{{route('order_panel')}}">注文管理画面</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    {{--  全ユーザー  --}}
    @elsecan('user-higher')
    <nav class="navbar navbar-expand-md navbar-dark shadow-sm" style="background-color:#2ea6ff;">
        <h1><a class="navbar-brand" href="/">ショッピングサイト</a></h1>

        {{--  ハンバーガーメニュー  --}}
        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#nav-bar">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{--  ナビゲーションバー  --}}
        <div class="collapse navbar-collapse" id="nav-bar">
            <ul class="navbar-nav mr-auto"></ul>
            <ul class="navbar-nav">
                {{--  認証中ユーザー  --}}
                @auth
                    {{--  検索  --}}
                    <form class="form-inline" action="search" method="get">
                        <input name="search" class="form-control mr-sm-2" type="search" placeholder="Search"
                            aria-label="Search">
                        <button class="btn btn-sm btn-outline-light my-2 my-sm-0" type="submit">Search</button>
                    </form>

                    {{--  普通のナビゲーションアイテム  --}}
                    <li class="nav-item"><a href="{{route('products.cart')}}" class="nav-link">カート</a></li>

                    {{--  ドロップダウン  --}}
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">{{ Auth::user()->name }}</a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li class="dropdown-item"><a href="{{route('products.likes')}}">お気に入り</a></li>
                            <li class="dropdown-divider"></li>
                            {{-- ログアウトへのリンク --}}
                            <li class="dropdown-item">{!! link_to_route('logout.get', 'ログアウト') !!}</li>
                        </ul>
                    </li>
                @endauth

                {{--  ゲストユーザー  --}}
                @guest
                    {{-- 新規登録 --}}
                    <li class="nav-item">{!! link_to_route('register', '登録', [], ['class' => 'nav-link']) !!}</li>
                    {{-- ログインページへのリンク --}}
                    <li class="nav-item">{!! link_to_route('login', 'ログイン', [], ['class' => 'nav-link']) !!}</li>
                @endguest
            </ul>
        </div>
    </nav>
    @endcan
    @guest
    <nav class="navbar navbar-expand-md navbar-dark shadow-sm" style="background-color:#2ea6ff;">
        <h1><a class="navbar-brand" href="/">ショッピングサイト</a></h1>

        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#nav-bar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="nav-bar">
            <ul class="navbar-nav mr-auto"></ul>
            <ul class="navbar-nav">
                {{-- 新規登録 --}}
                <li class="nav-item">{!! link_to_route('register', '登録', [], ['class' => 'nav-link']) !!}</li>
                {{-- ログインページへのリンク --}}
                <li class="nav-item">{!! link_to_route('login', 'ログイン', [], ['class' => 'nav-link']) !!}</li>
            </ul>
        </div>
    </nav>
    @endguest
</header>