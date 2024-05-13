@extends('layouts.app')

@section('title', 'お問い合わせ内容確認')

@section('content')
    <div class="container" style="max-width: 800px;">
        <div>
            <div class="bg-primary p-3 text-center">
                <h1 class="h4 m-0 text-white">
                    お問い合わせ内容確認
                </h1>
            </div>
            <div class="px-3 pb-5 pt-3 collapse show">
                <div class="card-body">
                    <form method="POST" action="">
                        @csrf
                        <input type="hidden" name="name" value="{{ $contact['name'] }}">
                        <input type="hidden" name="email" value="{{ $contact['email'] }}">
                        <input type="hidden" name="message" value="{{ $contact['message'] }}">

                        <div class="form-group row mb-3 align-items-center">
                            <label for="name"
                                class="col-md-4 col-form-label text-end">名前</label>
                            <div class="col-md-8">
                                {{ $contact['name'] }}
                            </div>
                        </div>

                        <div class="form-group row mb-3 align-items-center">
                            <label for="email"
                                class="col-md-4 col-form-label text-end">メールアドレス</label>
                            <div class="col-md-8">
                                {{ $contact['email'] }}
                            </div>
                        </div>

                        <div class="form-group row mb-3 align-items-center">
                            <label for="message" class="col-md-4 col-form-label text-end">メッセージ</label>

                            <div class="col-md-8 text-break">
                                {!! nl2br(e($contact['message'])) !!}
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <button type="submit" formaction="{{ route('user.contacts.back') }}" formmethod="post" class="btn btn-dark me-3">入力内容を修正する</button>
                            <button type="submit" formaction="{{ route('user.contacts.store') }}" formmethod="post" class="btn btn-primary">この内容で送信する</button>
                            <p class="mt-4">ご確認いただき、よろしければ送信ボタンを押してください。</p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
