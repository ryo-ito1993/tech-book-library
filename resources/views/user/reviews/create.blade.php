@extends('layouts.app')

@section('title', 'レビュー登録')

@section('content')
<div class="container-fluid" style="max-width: 1200px">
    <header class="bg-primary py-3 px-4 mb-3 text-center">
        <h1 class="mb-0 text-white h4">レビュー登録</h1>
    </header>

    <div class="card bg-white">
        <div class="card-body">
            <div class="d-flex flex-row">
                <img src="{{ $book['thumbnail'] }}" alt="thumbnail" class="img-fluid me-3" style="height: 150px; object-fit: contain;">
                <div class="flex-grow-1 ms-3">
                    <h5 class="card-title">{{ $book['title'] }}</h5>
                    <div class="card-text">
                        <small class="text-muted">
                            <span class="me-1"><i class="fas fa-user-circle"></i></span>
                            {{ implode(', ', $book['authors']) }}
                        </small>
                    </div>
                    @if ($book['publisher'])
                        <div><small class="text-muted">出版社：{{ $book['publisher'] }}</small></div>
                    @endif
                    @if ($book['publishedDate'])
                        <div><small class="text-muted">出版日：{{ $book['publishedDate'] }}</small></div>
                    @endif
                    <div>
                        <small class="text-muted">
                            <a href="{{ $book['infoLink'] }}" target="_blank">
                                <i class="fas fa-external-link-alt me-1"></i>GoogleBooks
                            </a>
                            <a href="https://calil.jp/book/{{ $book['isbn'] }}" target="_blank" class="ms-2">
                                <i class="fas fa-external-link-alt me-1"></i>カーリル
                            </a>
                        </small>
                    </div>
                </div>
            </div>
            <form action="{{ route('user.reviews.store') }}" method="post" class="mt-3">
                @csrf
                <div class="rating mb-3">
                    <div class="mb-1">
                        本の評価
                        @include('components.parts.required_badge')
                    </div>
                    <span v-for="(star, index) in stars" :key="index" class="star me-1"
                    @mouseover="setRating(index+1)"
                    :class="{ 'text-warning': index < rating }">
                        <i :class="index < rating ? 'fas fa-star' : 'far fa-star'"></i>
                    </span>
                </div>
                <input type="hidden" name="rating" v-model="rating">
                <div class="form-group mb-3">
                    <label for="review">レビュー</label>@include('components.parts.required_badge')
                    <textarea name="review" class="form-control bg-white mt-1 @error('review') is-invalid @enderror" required>{{ old('review') }}</textarea>
                    @include('components.form.error', ['name' => 'review'])
                </div>
                <div class="form-group mb-3">
                    <label for="categories">カテゴリ</label>
                    <select id="categories" class="form-control @error('categories') is-invalid @enderror" name="categories[]" multiple="multiple">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @if (old('categories') && in_array($category->id, old('categories'))) selected @endif>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @include('components.form.error', ['name' => 'categories'])
                </div>
                <div class="form-group mb-3">
                    <label for="levelCategories">カテゴリ</label>
                    <select id="levelCategories" class="form-control @error('levelCategories') is-invalid @enderror" name="levelCategories[]" multiple="multiple">
                        @foreach ($levelCategories as $levelCategory)
                            <option value="{{ $levelCategory->id }}" @if (old('levelCategories') && in_array($levelCategory->id, old('levelCategories'))) selected @endif>{{ $levelCategory->name }}</option>
                        @endforeach
                    </select>
                    @include('components.form.error', ['name' => 'levelCategories'])
                </div>
                <input type="hidden" name="title" value="{{ $book['title'] }}">
                <input type="hidden" name="isbn" value="{{ $book['isbn'] }}">
                <input type="hidden" name="thumbnail" value="{{ $book['thumbnail'] }}">
                @foreach ($book['authors'] as $author)
                    <input type="hidden" name="authors[]" value="{{ $author }}">
                @endforeach
                <button type="submit" class="btn btn-primary">レビューを投稿</button>
            </form>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    Vue.createApp({
        data() {
            return {
                rating: {{ old('rating', 3) }},
                stars: 5,
            };
        },
        methods: {
            setRating(rating) {
                this.rating = rating;
            },
        },
    }).mount('#app');
</script>
<script>
$(document).ready(function() {
    $('#categories').select2({
        placeholder: "カテゴリを選択",
        allowClear: true,
        width: '100%'
    });
    $('#levelCategories').select2({
        placeholder: "レベルカテゴリを選択",
        allowClear: true,
        width: '100%'
    });
});


</script>
@endsection
