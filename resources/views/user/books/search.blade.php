@extends('layouts.app')

@section('title', '本の検索')

@section('content')
<div class="container-fluid" style="max-width: 1200px">
    <header class="bg-info py-3 px-4 mb-3 text-center">
        <h1 class="btn-collapse mb-0 text-white h4">
            技術書検索
        </h1>
    </header>
    <form action="{{ route('user.books.search') }}" method="GET" class="my-2 mb-3">
        <input type="text" name="title" class="form-control bg-white mb-1" placeholder="タイトル" value="{{ old('title', $title) }}">
        <input type="text" name="author" class="form-control bg-white mb-1" placeholder="著者名" value="{{ old('author', $author) }}">
        <input type="text" name="isbn" class="form-control bg-white mb-1" placeholder="ISBN10またはISBN13(ともにハイフンなし)" value="{{ old('isbn', $isbn) }}">

        <button class="btn btn-primary" type="submit">検索</button>
    </form>
    <div class="row">
        @forelse ($books as $book)
            <div class="col-lg-3 col-md-4 col-6 mb-4">
                <div class="card me-2" style="height: 375px;">
                    <a href="{{ route('user.books.show', $book['isbn']) }}" class="text-decoration-none" style="display: flex; flex-direction: column; justify-content: center; align-items: center; width: 100%;">
                        <img class="card-img-top mt-1" style="max-width: 125px; object-fit: contain;" src="{{ $book['thumbnail'] }}" alt="thumbnail">
                        <div class="card-body" style="flex-grow: 0;">
                            <div class="card-text">{{ $book['title'] }}</div>
                            <div class="card-text">
                                <small class="text-muted">
                                    <span class="me-1"><i class="fas fa-user-circle"></i></span>
                                    {{ implode(', ', $book['authors']) }}
                                </small>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        @empty
            @if ($hasSearched)
                <p>検索結果が見つかりませんでした。</p>
            @endif
        @endforelse
    </div>

</div>
@endsection
