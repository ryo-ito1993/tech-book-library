@extends('layouts.app')

@section('content')
    @php
        $status_code = $exception->getStatusCode();
        $message = $exception->getMessage();
            switch ($status_code) {
                case 400:
                    $message = 'Bad Request';
                    break;
                case 401:
                    $message = '認証に失敗しました';
                    break;
                case 403:
                    $message = 'この操作を行う権限がありません';
                    break;
                case 404:
                    $message = '存在しないページです';
                    break;
                case 408:
                    $message = 'タイムアウトです';
                    break;
                case 414:
                    $message = 'リクエストURIが長すぎます';
                    break;
                case 419:
                    $message = '不正なリクエストです。';
                    break;
                case 500:
                    $message = 'サーバー側でエラーがおきました';
                    break;
                case 503:
                    $message = 'Service Unavailable';
                    break;
                default:
                    $message = 'エラーがおきました。';
                    break;
            }
    @endphp

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card bg-white">
                <div class="row g-0">
                    <div class="py-3 col-md-4 d-flex align-items-center justify-content-center">
                        <img src="{{ asset('images/sorry.png') }}" style="max-width:200px;">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <div class="card-body">
                                <h1>エラー コード{{ $status_code }} ：{{ $message }}</h1>

                                @if ($status_code == 419)
                                    <p>前回から時間が経ち、セッションが切れたり、違うページから他のデータを処理しようとした可能性があります</p>
                                    <p class="mt-4 text-center"><a href="{{ route('user.login') }}">再度ログインしてお試しください</a></p>
                                @elseif($status_code == 404)
                                    <p>対象のページはありません。</p>
                                @elseif($status_code !== 403)
                                    <p>エラーがおきました。<br>
                                        一度ページを閉じていただき、30分ほど時間をおいて再度お試しください。</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
