@extends('layouts.app')

@section('title', 'メールアドレス変更')

@section('content')
    <div class="container" style="max-width: 1000px;">
        <div>
            <header class="bg-primary py-2 px-4 mb-3 text-center">
                <h1 class="btn-collapse mb-0 text-white h5">
                    メールアドレス変更
                </h1>
            </header>
            <div class="px-3 pb-5 pt-3 collapse show">
                <div class="card-body">
                    <form method="POST" action="{{ route('user.emails.send')}}" class="mb-4">
                        @csrf
                        <div class="form-group row mb-3">
                            <label for="email" class="form-label">新しいメールアドレス@include('components.parts.required_badge')</label>
                            <input type="email" class="form-control bg-white @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                            @include('components.form.error', ['name' => 'email'])
                            <small >※メールアドレス変更後、ログイン用メールアドレスも変更されます。</small>
                        </div>
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary">確認メールを送信する</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
