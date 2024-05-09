@extends('layouts.app')

@section('title', 'レビューリスト')

@section('content')
<div class="container-fluid" style="max-width: 1200px">
    <header class="bg-info py-3 px-4 mb-3 text-center">
        <h1 class="mb-0 text-white h4">レビューリスト</h1>
    </header>

    <div class="row">
        @foreach ($reviews as $review)
            <div class="col-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <img src="{{ !empty($review->book->thumbnail) ? html_entity_decode($review->book->thumbnail) : asset('images/no-image.jpeg') }}" alt="thumbnail" class="img-fluid me-3" style="height: 150px; object-fit: contain; width: auto;">
                            <div class="flex-grow-1">
                                <div class="card-text text-muted mb-1"><span class="me-2">{{ $review->created_at->format('Y/m/d') }}</span>{{ $review->user->name }}さんのレビュー</div>
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
                                    @foreach ($review->categories as $category)
                                        <span class="badge bg-info text-white me-1">{{ $category->name }}</span>
                                    @endforeach
                                </p>
                                <p class="card-text mt-2">{{ $review->body }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{ $reviews->links('pagination::bootstrap-5') }}
</div>
@endsection
