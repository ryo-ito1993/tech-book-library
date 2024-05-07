@extends('layouts.app')

@section('title', '本の詳細')

@section('content')
<div class="container-fluid" style="max-width: 1200px">
    <div class="row">
        <div class="col-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <img src="{{ !empty($book['thumbnail']) ? $book['thumbnail'] : asset('images/no-image.jpeg') }}" alt="thumbnail" class="img-fluid me-3" style="height: 175px; object-fit: contain; width: auto;">
                        <div class="flex-grow-1">
                            <h5 class="card-title">{{ $book['title'] }}</h5>
                            <div class="card-text">
                                <small class="text-muted">
                                    <span class="me-1"><i class="fas fa-user-circle"></i></span>
                                    {{ implode(', ', $book['authors']) }}
                                </small>
                            </div>
                            <button id="favorite-button" class="btn btn-outline-primary mt-1" type="button">
                                <i class="fa fa-book-reader"></i> 読みたい
                            </button>
                        </div>
                    </div>
                </div>
                <div class="bg-success py-1 px-4 mb-3 text-center">
                    <h1 class="btn-collapse mb-0 text-white h4">
                        お気に入り図書館の蔵書
                    </h1>
                </div>
                <div class="text-center mb-4">
                    <h5>{{ $user->library->system_name }}の蔵書</h5>
                    @if ($bookAvailable)
                        <div>
                            @foreach($bookAvailable['libkey'] as $libraryName => $status)
                                <span class="badge {{ $status === '貸出可' ? 'bg-success' : ($status === '貸出中' ? 'bg-danger' : 'bg-warning text-dark') }} mb-2" style="font-size: 1em;">
                                    {{ $libraryName }}：{{ $status }}
                                </span>
                            @endforeach
                        </div>
                        <button class="btn btn-primary mt-2">予約する</button>
                    @endif

                    {{-- <div>
                        <span class="badge bg-success mb-2" style="font-size: 1em;">玉川台：貸出可</span>
                        <span class="badge bg-danger mb-2" style="font-size: 1em;">世田谷：貸出中</span>
                        <span class="badge bg-warning text-dark mb-2" style="font-size: 1em;">経堂：館内のみ</span>
                        <span class="badge bg-success mb-2" style="font-size: 1em;">玉川台：貸出可</span>
                        <span class="badge bg-danger mb-2" style="font-size: 1em;">世田谷：貸出中</span>
                        <span class="badge bg-warning text-dark mb-2" style="font-size: 1em;">経堂：館内のみ</span>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <button class="btn btn-primary mb-3">この本をレビュー</button>
        @for ($i = 0; $i < 3; $i++)
        <div class="card mb-1">
            <div class="card-body">
                <h5 class="card-title">ユーザー名: タロウ 山田</h5>
                <p class="card-text">投稿日: 2024/04/23</p>
                <p class="card-text">
                    評価:
                    <span class="star-rating">★★★☆☆</span>
                </p>
                <p class="card-text">
                    カテゴリー:
                    <span class="badge bg-info text-white">文学</span>
                    <span class="badge bg-info text-white">プログラミング</span>
                </p>
                <p class="card-text">この本は非常に役立ちました。初心者でも理解しやすい内容で、具体的な例が豊富に紹介されています。</p>
            </div>
        </div>
        @endfor
    </div>

</div>
@endsection
