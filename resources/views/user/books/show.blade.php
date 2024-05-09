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
                        <div class="flex-grow-1 ms-4">
                            <h5 class="card-title">{{ $book['title'] }}</h5>
                            <div class="card-text">
                                <small class="text-muted">
                                    <span class="me-1"><i class="fas fa-user-circle"></i></span>
                                    {{ implode(', ', $book['authors']) }}
                                </small>
                            </div>
                            <div><small class="text-muted">出版社：{{ $book['publisher'] }}</small></div>
                            <div><small class="text-muted">出版日：{{ $book['publishedDate'] }}</small></div>
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
                            <button v-if="!isFavorite" id="favorite-button" class="btn btn-outline-warning mt-2" type="button" @click="toggleFavorite">
                                <i class="fa fa-book-reader"></i> 読みたい
                            </button>
                            <button v-if="isFavorite" id="favorite-button" class="btn btn-warning mt-2" type="button" @click="toggleFavorite">
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
                @if ($user->library)
                    <div class="text-center mb-4">
                        <h5>お気に入り図書館エリア：<span>{{ optional($user->library)->system_name }}</span></h5>
                        <div v-if="loading">
                            <div class="text-center">
                                <h1><i class="fas fa-spinner fa-pulse"></i></h1>
                            </div>
                        </div>
                        <div v-if="!loading && availability.books && Object.keys(availability.books).length > 0">
                            <div v-for="(data, isbn) in availability.books" :key="isbn">
                                <div v-for="(info, system) in data" :key="system">
                                    <div v-if="Object.keys(info.libkey).length > 0">
                                        <span v-for="(status, library) in info.libkey" :key="library">
                                            <span class="badge me-1 mt-1 fs-6" :class="{'bg-info': status === '貸出可', 'bg-danger': status === '貸出中', 'bg-secondary': status !== '貸出可' && status !== '貸出中'}">
                                                @{{ library }}：@{{ status }}
                                            </span>
                                        </span>
                                        <div class="mt-1">
                                            <a v-if="info.reserveurl" :href="info.reserveurl" class="btn btn-primary mt-2" target="_blank">この本を予約する</a>
                                        </div>
                                    </div>
                                    <div v-else>
                                        <span class="badge bg-secondary fs-6">蔵書なし</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="errorMessage" class="alert alert-danger">
                            @{{ errorMessage }}
                        </div>
                    </div>
                @else
                    <div class="text-center">
                        <div class="alert alert-info">
                            貸出状況を表示するには図書館登録をしてください。
                            <div class="mt-1">
                                <a href="{{ route('user.library.create') }}" class="btn btn-primary">こちらから図書館を登録</a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-12">
        <a href="{{ route('user.reviews.create', $book['isbn']) }}" class="btn btn-primary mb-3">この本をレビュー</a>
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
@section('script')
<script>
    Vue.createApp({
        data() {
            return {
                isbn: '{{ $book['isbn'] }}',
                systemId: '{{ optional($user->library)->system_id }}',
                availability: {},
                loading: false,
                errorMessage: null,
                isFavorite: {{ $isFavorite ? 'true' : 'false' }},
            };
        },
        mounted() {
            if (this.systemId) {
                this.fetchBookAvailability();
            }
        },
        methods: {
            fetchBookAvailability() {
                this.loading = true;
                fetch(`/api/getBookAvailability`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            isbn: this.isbn,
                            systemId: this.systemId,
                        }),
                    })
                .then(response => response.json())
                .then(data => {
                    this.availability = data;
                    this.loading = false;
                })
                .catch(error => {
                    this.errorMessage = '蔵書情報が利用できません';
                })
            },
            toggleFavorite() {
                fetch(`/api/books/toggleFavorite`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        isbn: this.isbn,
                        user_id: '{{ $user->id }}',
                        title : '{{ $book['title'] }}',
                        thumbnail: '{{ $book['thumbnail'] }}',
                        authors: @json($book['authors']),
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    this.isFavorite = !this.isFavorite;
                })
                .catch(error => {
                    alert('読みたいの登録に失敗しました');
                })
            }
        }
    }).mount('#app');
</script>
@endsection
