@extends('layouts.app')

@section('title', 'お問い合わせ')

@section('content')
    <div class="container" style="max-width: 1000px;">
        <div>
            <header class="bg-primary py-2 px-4 mb-3 text-center">
                <h1 class="btn-collapse mb-0 text-white h5">
                    お問い合わせ
                </h1>
            </header>
            <div class="px-3 pb-5 pt-3 collapse show">
                <div class="card-body">
                    <form method="POST" action="{{ route('user.contacts.confirm') }}">
                        @csrf

                        <div class="form-group row mb-3">
                            <label for="name" class="form-label">お名前@include('components.parts.required_badge')</label>
                            <input type="text" class="form-control bg-white @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @include('components.form.error', ['name' => 'name'])
                        </div>

                        <div class="form-group row mb-3">
                            <label for="email" class="form-label">メールアドレス@include('components.parts.required_badge')</label>
                            <input type="text" class="form-control bg-white @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                            @include('components.form.error', ['name' => 'email'])
                        </div>

                        <div class="form-group row mb-3">
                            <label for="message" class="form-label">お問い合わせ内容@include('components.parts.required_badge')</label>
                            <textarea class="form-control bg-white @error('message') is-invalid @enderror" id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                            @include('components.form.error', ['name' => 'message'])
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary">確認画面へ</button>
                            <p class="mt-4">ご返信希望のお問い合わせ頂きました内容に関しまして、随時ご回答してまいりたいと思います。お時間を頂く場合もございますことご了承下さい。</p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
