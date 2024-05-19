@extends('layouts.app')

@section('title', 'パスワード変更')

@section('content')
    <div class="container" style="max-width: 1000px;">
        <div>
            <header class="bg-primary py-2 px-4 mb-3 text-center">
                <h1 class="btn-collapse mb-0 text-white h5">
                    パスワード変更
                </h1>
            </header>
            <div class="px-3 pb-5 pt-3 collapse show">
                <div class="card-body">
                    <form method="POST" action="{{ route('user.passwords.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="form-group row mb-3">
                            <label for="now_password" class="form-label">現在のパスワード@include('components.parts.required_badge')</label>
                            <input type="password" class="form-control bg-white @error('now_password') is-invalid @enderror" id="now_password" name="now_password" value="{{ old('now_password') }}" required>
                            @include('components.form.error', ['name' => 'now_password'])
                        </div>

                        <div class="form-group row mb-3">
                            <label for="new_password" class="form-label">新しいパスワード@include('components.parts.required_badge')</label>
                            <input type="password" class="form-control bg-white @error('new_password') is-invalid @enderror" id="new_password" name="new_password" value="{{ old('new_password') }}" required>
                            @include('components.form.error', ['name' => 'new_password'])
                        </div>

                        <div class="form-group row mb-3">
                            <label for="new_password_confirmation" class="form-label">新しいパスワード（確認）@include('components.parts.required_badge')</label>
                            <input type="password" class="form-control bg-white @error('new_password_confirmation') is-invalid @enderror" id="new_password_confirmation" name="new_password_confirmation" value="{{ old('new_password_confirmation') }}" required>
                            @include('components.form.error', ['name' => 'new_password_confirmation'])
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary">変更する</button>
                        </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
