@extends('layouts.admin.app')

@section('title','レベルカテゴリ登録')

@section('content')
    <div class="card shadow mb-5 mx-auto" style="max-width: 800px">
        <div class="card-header">
            <h5 class="m-2">レベルカテゴリ登録</h5>
        </div>
        <div class="card-body p-5">
            <form action="{{ route('admin.level_categories.store') }}"method="POST">
                @csrf

                <div class="form-group mb-3">
                    <label for="levelCategory" class="col-form-label text-black-50 mr-2">レベルカテゴリ名</label>
                    @include('components.parts.required_badge')
                    <input type="text" name="name" id="levelCategory" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @include('components.form.error', ['name' => 'name'])
                </div>
                <div class="text-center">
                    <a href="{{ route('admin.level_categories.index') }}" class="btn btn-dark me-4">一覧へ戻る</a>
                    <button class="btn btn-primary">作成する</button>
                </div>
            </form>
        </div>
    </div>
@endsection
