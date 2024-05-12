@extends('layouts.admin.app')

@section('title','レビュー情報詳細')

@section('content')
    <div class="card shadow">
        <div class="card-header">
            <h5 class="m-2">レビュー情報詳細</h5>
        </div>
        <div class="card-body m-3">
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <th scope="row">投稿者</th>
                        <td>{{ $review->user->name }}</td>
                    </tr>
                    <tr>
                        <th scope="row">書籍名</th>
                        <td>{{ $review->book->title }}</td>
                    </tr>
                    <tr>
                        <th scope="row">著者名</th>
                        <td>{{ $review->book->authors->pluck('name')->implode(', ') }}</td>
                    </tr>
                    <tr>
                        <th scope="row">レビュー本文</th>
                        <td>{!! nl2br(e($review->body)) !!}</td>
                    </tr>
                    <tr scope="row">
                        <th>本の評価</th>
                        <td>
                            <span class="star-rating">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="{{ $i <= $review->rate ? 'fas fa-star text-warning' : 'far fa-star' }}"></i>
                                @endfor
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">カテゴリ</th>
                        <td>
                            @foreach ($review->categories as $category)
                                <span class="badge bg-info text-white me-1">{{ $category->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">登録日時</th>
                        <td>{{ $review->created_at }}</td>
                    </tr>
                    <tr>
                        <th scope="row">更新日時</th>
                        <td>{{ $review->updated_at }}</td>
                    </tr>
            </table>
            <div class="text-center">
                <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-dark mr-4">一覧へ戻る</a>
            </div>
        </div>
    </div>
@endsection
