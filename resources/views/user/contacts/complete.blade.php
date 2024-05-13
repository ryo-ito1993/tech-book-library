@extends('layouts.app')

@section('title', 'お問い合わせ完了')

@section('content')
<div class="container" style="max-width: 800px;">
    <div>
        <div class="bg-primary p-3 text-center">
            <h1 class="h4 m-0 text-white">
                お問い合わせ完了画面
            </h1>
        </div>
        <div class="px-3 pb-5 pt-3 collapse show mx-auto" style="max-width: 800px;">
            <div class="container mt-5">
                <div class="alert alert-success" role="alert">
                    お問い合わせが完了しました。
                </div>
                <p>お問い合わせ完了メールをご入力いただいたメールアドレスに送信いたしました。</p>
            </div>
            <div class="text-center mt-5">
                <a type="button" href="{{ route('user.top') }}" class="btn btn-primary">トップページへ戻る</a>
            </div>
        </div>
    </div>
</div>
@endsection
