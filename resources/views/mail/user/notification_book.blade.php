@extends('layouts.mail.app')
@section('content')
<div class="container mt-5" style="max-width: 600px;">
    <h1 class="h3 border-bottom border-primary border-3 mb-5">通知設定していた本が貸出可能になりました。</h1>
    <p>以下の書籍が貸出可能になりました。</p>
    <p>書籍名：{{ $book->title }}</p>
    <p>登録図書館エリア：{{ $library->system_name }}</p>
    <p><a href="{{ route('user.books.show', $book->isbn) }}">詳細ページへ</a></p>
    <div class="mt-5">
        @include('components.parts.mail_footer')
    </div>
</div>
@endsection
