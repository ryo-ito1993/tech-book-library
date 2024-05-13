@extends('layouts.admin.app')

@section('title','ホーム')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">管理者ダッシュボード</h4>
            </div>

            <div class="card-body mt-4">
                <div class="row justify-content-around mb-5">

                    <div class="col-3 me-5 mt-5">
                        <a href="{{ route('admin.users.index') }}" class="card shadow text-decoration-none list-group-item-action h-100">
                            <div class="card-header text-center">
                                <h1 class="mb-0 display-2"><i class="fa fa-users me-1"></i></h1>
                                <h5 class="mb-0">会員管理</h5>
                            </div>
                            <div class="card-body text-dark text-center">
                                <p>会員の一覧、詳細情報を確認できます</p>
                            </div>
                        </a>
                    </div>

                    <div class="col-3 me-5 mt-5">
                        <a href="{{ route('admin.reviews.index') }}" class="card shadow text-decoration-none list-group-item-action h-100">
                            <div class="card-header text-center">
                                <h1 class="mb-0 display-2"><i class="fa fa-comments me-1"></i></h1>
                                <h5 class="mb-0">レビュー管理</h5>
                            </div>
                            <div class="card-body text-dark text-center">
                                <p>レビューの一覧、詳細情報を確認できます</p>
                            </div>
                        </a>
                    </div>

                    <div class="col-3 me-5 mt-5">
                        <a href="{{ route('admin.level_categories.index') }}" class="card shadow text-decoration-none list-group-item-action h-100">
                            <div class="card-header text-center">
                                <h1 class="mb-0 display-2"><i class="fas fa-layer-group me-1"></i></h1>
                                <h5 class="mb-0">レベルカテゴリ管理</h5>
                            </div>
                            <div class="card-body text-dark text-center">
                                <p>レビューに設定できるカテゴリを管理します。</p>
                            </div>
                        </a>
                    </div>

                    <div class="col-3 me-5 mt-5">
                        <a href="{{ route('admin.categories.index') }}" class="card shadow text-decoration-none list-group-item-action h-100">
                            <div class="card-header text-center">
                                <h1 class="mb-0 display-2"><i class="fas fa-laptop-code me-1"></i></h1>
                                <h5 class="mb-0">技術カテゴリ管理</h5>
                            </div>
                            <div class="card-body text-dark text-center">
                                <p>レビューに設定できる技術カテゴリを管理します。</p>
                            </div>
                        </a>
                    </div>

                    <div class="col-3 me-5 mt-5">
                        <a href="{{ route('admin.contacts.index') }}" class="card shadow text-decoration-none list-group-item-action h-100">
                            <div class="card-header text-center">
                                <h1 class="mb-0 display-2"><i class="far fa-envelope"></i></h1>
                                <h5 class="mb-0">お問い合わせ管理</h5>
                            </div>
                            <div class="card-body text-dark text-center">
                                <p>お問い合わせの一覧、詳細情報を確認できます</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
