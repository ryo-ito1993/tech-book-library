@extends('layouts.app')

@section('title', 'レビューリスト')

@section('content')
<div class="container-fluid" style="max-width: 1200px">
    <header class="bg-info py-3 px-4 mb-3 text-center">
        <h1 class="mb-0 text-white h4">レビューリスト</h1>
    </header>

    <div class="row">
        {{-- 仮のデータです。本来はデータベースから取得したデータをforeachで回す --}}
        @foreach(range(1, 5) as $index)
            <div class="col-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <img src="https://books.google.com/books/content?id=NfggEAAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api" alt="thumbnail" class="img-fluid me-3" style="height: 150px; object-fit: contain; width: auto;">
                            <div class="flex-grow-1">
                                <div class="card-text text-muted mb-1"><span class="me-2">2024/04/23</span>ユーザー名{{$index}}さんのレビュー</div>
                                <h5 class="card-title">動かして学ぶ！Laravel開発入門{{$index}}</h5>
                                <div class="card-text">
                                    <small class="text-muted">
                                        <span class="me-1"><i class="fas fa-user-circle"></i></span>
                                        山崎 大助
                                    </small>
                                </div>
                                <div class="card-text mt-1">
                                    評価:
                                    @for($i = 0; $i < 5; $i++)
                                        <span class="star-rating">{{ $i < 3 ? '★' : '☆' }}</span>
                                    @endfor
                                </div>
                                <div class="card-text mt-1">カテゴリー: <span class="badge bg-secondary">Laravel</span> <span class="badge bg-secondary">初級者向け</span></div>
                                <p class="card-text mt-2">ここにレビュー本文が入ります。この本について詳細なレビューが書かれます。長さに応じて内容が展開されます。</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
