@extends('layouts.app')

@section('title', '読みたい一覧')

@section('content')
<div class="container-fluid" id="app" style="max-width: 1200px">
    <header class="bg-primary py-2 px-4 mb-3 text-center">
        <h1 class="btn-collapse mb-0 text-white h5">読みたい一覧</h1>
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
                            <div v-if="loading['{{ $book->isbn }}']">
                                <div class="text-center">
                                    <h1><i class="fas fa-spinner fa-pulse"></i></h1>
                                </div>
                            </div>
                            <div class="text-center mt-2">
                                <div v-if="availability['{{ $book->isbn }}'] === '貸出可'">
                                    <span class="badge bg-info fs-6">貸出可</span>
                                </div>
                                <div v-if="availability['{{ $book->isbn }}'] === '貸出不可'">
                                    <span class="badge bg-danger fs-6">貸出不可</span>
                                </div>
                                <div v-if="availability['{{ $book->isbn }}'] === '蔵書なし'">
                                    <span class="badge bg-secondary fs-6">蔵書なし</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        @empty
            <div class="alert alert-info text-center" role="alert">
                「読みたい」はまだありません。
            </div>
        @endforelse
    </div>
    {{ $books->links('pagination::bootstrap-5') }}
</div>
@endsection

@section('script')
<script>
    Vue.createApp({
        data() {
            return {
                books: @json($books->items()) || [],
                loading: {},
                availability: {},
                systemId: '{{ optional($user->library)->system_id }}',
            };
        },
        methods: {
            checkAvailability(isbn) {
                this.loading[isbn] = true;
                fetch(`/api/getBookAvailability`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        isbn: isbn,
                        systemId: this.systemId,
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    this.loading[isbn] = false;
                    let isAvailable = '蔵書なし';
                    if (data.books && data.books[isbn]) {
                        Object.values(data.books[isbn]).forEach(system => {
                            if (system.libkey && Object.keys(system.libkey).length > 0) {
                                isAvailable = '貸出不可';
                            }
                            if (system.libkey && Object.values(system.libkey).includes('貸出可')) {
                                isAvailable = '貸出可';
                            }
                        });
                    }
                    this.availability[isbn] = isAvailable;
                })
                .catch(error => {
                    console.error('Error fetching book availability:', error);
                    this.loading[isbn] = false;
                });
            }
        },
        mounted() {
            if (this.systemId) {
                this.books.forEach(book => {
                    this.checkAvailability(book.isbn);
                });
            }
        }
    }).mount('#app');
    </script>
@endsection
