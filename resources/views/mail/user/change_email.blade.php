@extends('layouts.mail.app')
@section('content')
<div class="container mt-5" style="max-width: 600px;">
    <h1 class="h3 border-bottom border-primary border-3 mb-5">メールアドレス変更</h1>
    <div>
        下記のURLをクリックしてメールアドレス変更手続きを完了して下さい。<br>

        <div class="text-center mt-3">
            <a href="{{ $url }}" class="w-50 btn btn-secondary">メールアドレス変更</a><br><br>
        </div>

        ※ログイン状態でアクセスする必要があります。<br>
        ※メールアドレス変更後、ログイン用メールアドレスも変更されます。<br><br>
    </div>
    <div class="mt-5">
        @include('components.parts.mail_footer')
    </div>
</div>
@endsection
