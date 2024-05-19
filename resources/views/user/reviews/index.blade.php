@extends('layouts.app')

@section('title', 'レビューリスト')

@section('content')
<div class="container-fluid" style="max-width: 1200px">
    <header class="bg-primary py-2 px-4 mb-3 text-center">
        <h1 class="mb-0 text-white h5">レビューリスト</h1>
    </header>

    @if ($reviews->isEmpty())
        <div class="alert alert-info">レビューはまだありません。</div>
    @endif
    <div class="row">
        @foreach ($reviews as $review)
            <div class="col-12 mb-3">
                <div class="card bg-white">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <a href="{{ route('user.books.show', $review->book->isbn) }}" class="text-decoration-none">
                                <img src="{{ !empty($review->book->thumbnail) ? html_entity_decode($review->book->thumbnail) : asset('images/no-image.jpeg') }}" alt="thumbnail" class="img-fluid me-3" style="height: 175px; object-fit: contain; width: auto;">
                            </a>
                            <div class="flex-grow-1">
                                <div class="card-text text-muted mb-3">
                                    <span class="me-2">{{ $review->created_at->format('Y/m/d') }}</span>{{ $review->user->name }}さんのレビュー
                                </div>
                                <h5 class="card-title">{{ $review->book->title }}</h5>
                                <div class="card-text">
                                    <small class="text-muted">
                                        <span class="me-1"><i class="fas fa-user-circle"></i></span>
                                        {{ $review->book->authors->pluck('name')->implode(', ') }}
                                    </small>
                                </div>
                                <div class="card-text mt-1">
                                    本の評価:
                                    <span class="star-rating">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="{{ $i <= $review->rate ? 'fas fa-star text-warning' : 'far fa-star' }}"></i>
                                        @endfor
                                    </span>
                                </div>
                                <p class="card-text">
                                    @foreach ($review->levelCategories as $levelCategory)
                                        <a href="{{ route('user.reviews.index', ['levelCategory' => $levelCategory->id]) }}" class="btn btn-sm btn-primary text-white">{{ $levelCategory->name }}</a>
                                    @endforeach
                                    @foreach ($review->categories as $category)
                                        <a href="{{ route('user.reviews.index', ['category' => $category->id]) }}" class="btn btn-sm btn-info text-white">{{ $category->name }}</a>
                                    @endforeach
                                </p>
                                <p class="card-text mt-2">{!! nl2br(e($review->body)) !!}</p>
                            </div>
                            @if ($review->user->id === auth()->id())
                                <div class="ms-auto me-1">
                                    <a href="{{ route('user.reviews.edit', $review) }}" class="btn btn-sm btn-success"><i class="fas fa-edit"></i></a>
                                </div>
                                <div class="ms-auto">
                                    <form action="{{ route('user.reviews.destroy', $review) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('このレビューを削除しますか？');"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{ $reviews->links('pagination::bootstrap-5') }}
</div>
@endsection
