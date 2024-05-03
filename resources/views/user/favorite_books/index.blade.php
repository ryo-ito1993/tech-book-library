@extends('layouts.app')

@section('title', '読みたい一覧')

@section('content')
<div class="container-fluid" style="max-width: 1200px">

    <header class="bg-info py-3 px-4 mb-3 text-center">
        <h1 class="btn-collapse mb-0 text-white h4">
            読みたい一覧
        </h1>
    </header>

    {{-- @if ($->isEmpty())
        <div class="alert alert-info" role="alert">
            読みたい登録はありません。
        </div>
    @endif --}}
    <div class="row">
        {{-- @foreach ($favoriteVideos as $video) --}}
        @for ($i = 0; $i < 10; $i++)
            <div class="col-lg-3 col-md-4 col-6 mb-4">
                <div class="card me-2" style="height: 375px;">
                    <a href="#" class="text-decoration-none" style="display: flex; flex-direction: column; justify-content: center; align-items: center; width: 100%;">
                        <img class="card-img-top mt-1" style="max-width: 125px; object-fit: contain;" src="https://books.google.com/books/content?id=NfggEAAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api" alt="thumbnail">
                        <div class="card-body" style="flex-grow: 0;">
                            <div class="card-text">動かして学ぶ！Laravel開発入門</div>
                            <div class="card-text">
                                <small class="text-muted">
                                    <span class="me-1"><i class="fas fa-user-circle"></i></span>
                                    山崎 大助
                                </small>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        @endfor



        {{-- @endforeach --}}
    </div>
</div>
@endsection
