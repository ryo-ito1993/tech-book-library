@extends('layouts.app')

@section('title', '通知設定済書籍一覧')

@section('content')
<div class="container-fluid" id="app" style="max-width: 1200px">
    <header class="bg-primary py-2 px-4 mb-3 text-center">
        <h1 class="btn-collapse mb-0 text-white h5">通知設定済書籍一覧</h1>
    </header>
    <div class="row">
        @forelse ($books as $book)
        <div class="col-lg-3 col-md-4 col-6 mb-4">
            <div class="card bg-white me-2 shadow" style="height: 375px;">
                <a href="{{ route('user.books.show', $book->isbn) }}" class="text-decoration-none" style="display: flex; flex-direction: column; justify-content: center; align-items: center; width: 100%;">
                    <img class="card-img-top mt-1" style="max-width: 125px; object-fit: contain;" src="{{ !empty($book->thumbnail) ? html_entity_decode($book->thumbnail) : asset('images/no-image.jpeg') }}" alt="thumbnail">
                    <div class="card-body" style="flex-grow: 0;">
                        <div class="card-text">{{ Str::limit($book->title, 80, '...') }}</div>
                        <div class="card-text">
                            <small class="text-muted">
                                <span class="me-1"><i class="fas fa-user-circle"></i></span>
                                {{ $book->authors->pluck('name')->implode(', ') }}
                            </small>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    @empty
            <div class="alert alert-info text-center" role="alert">
                「通知設定済書籍一覧」はまだありません。
            </div>
        @endforelse
    </div>
    {{ $books->links('pagination::bootstrap-5') }}
</div>
@endsection
