@extends('layouts.mail.app')
@section('content')
<div class="container mt-5" style="max-width: 600px;">
    <h1 class="h3 border-bottom border-primary border-3 mb-5">お問い合わせを受け付けました</h1>
    <p>この度は、TechBookLibraryへのお問い合わせ誠にありがとうございました。</p>
    <p>以下の内容でお問い合わせを受け付けました。</p>
    <p>お問い合わせ内容:</p>
    <p>名前：{{ $contact->name }}</p>
    <p>メールアドレス：{{ $contact->email }}</p>
    <p>メッセージ：{{ $contact->message }}</p>
    <p>-----------------------</p>
    <p>担当者が確認次第、ご連絡させていただきます。</p>
    <p>何かご不明点、ご質問等がございましたら、お気軽にお問い合わせください。</p>
    <div class="mt-5">
        @include('components.parts.mail_footer')
    </div>
</div>
@endsection
