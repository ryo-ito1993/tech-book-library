@extends('layouts.mail.app')
@section('content')
<div class="container mt-5" style="max-width: 600px;">
    <h1 class="h3 border-bottom border-primary border-3 mb-5">会員よりお問い合わせがございました</h1>

    <p>名前：{{ $contact->name }}</p>
    <p>メールアドレス：{{ $contact->email }}</p>
    <p>メッセージ：{{ $contact->message }}</p>
    <div class="mt-5">
        @include('components.parts.mail_footer')
    </div>
</div>
@endsection
