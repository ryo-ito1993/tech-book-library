@extends('layouts.admin.app')

@section('title','レビュー一覧')

@section('content')
    <div class="card shadow">
        <div class="card-header">
            <h5 class="m-2">レビュー一覧：{{ $reviews->total() . '件中' . $reviews->firstItem() . '-' . $reviews->lastItem() }}件</h5>
        </div>
        <div class="card-body table-responsive">
        <div class="bg-white mb-3">
            <x-parts.search_box route='admin.reviews.index' :reviewer="\Request::get('reviewer') ?? ''" :bookName="\Request::get('bookName') ?? ''"></x-parts.search_box>

            <div class="d-flex justify-content-end align-items-center mb-3">
                {{ $reviews->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
            <table class="table table-bordered">
                <thead class="">
                    <tr>
                        <th scope="col" class="text-nowrap">投稿者</th>
                        <th scope="col" class="text-nowrap">書籍名</th>
                        <th scope="col" class="text-nowrap">投稿日</th>
                        <th scope="col" class="text-nowrap">詳細</th>
                    </tr>
                </thead>
                <tbody style="border-style: none">
                    @foreach ($reviews as $review)
                        <tr>

                            <td class="text-wrap px-2">{{ $review->user->name }}</td>
                            <td class="text-nowrap px-2">{{ $review->book->title }}</td>
                            <td class="text-nowrap px-2">{{ $review->created_at->format('Y/m/d') }}</td>
                            <td class="text-wrap px-2 text-center">
                                <a href="{{ route('admin.reviews.show', ['review' => $review]) }}" class="btn btn-primary">詳細</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-end align-items-center mb-3">
                {{ $reviews->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
@endsection
