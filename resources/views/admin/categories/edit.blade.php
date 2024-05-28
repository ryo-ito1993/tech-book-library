@extends('layouts.admin.app')

@section('title','カテゴリ編集')

@section('content')
    <div class="card shadow mb-5 mx-auto" style="max-width: 800px">
        <div class="card-header">
            <h5 class="m-2">技術カテゴリ編集</h5>
        </div>
        <div class="card-body p-5">
            <form action="{{ route('admin.categories.update', $category) }}"method="POST">
                @csrf
                @method('PUT')

                <div class="form-group mb-3">
                    <label for="category" class="col-form-label text-black-50 mr-2">技術カテゴリ名</label>
                    @include('components.parts.required_badge')
                    <input type="text" name="name" id="category" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $category->name) }}" required>
                    @include('components.form.error', ['name' => 'name'])
                </div>
                <div class="text-center">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-dark me-4">一覧へ戻る</a>
                    <button class="btn btn-primary">更新する</button>
                </div>
            </form>
        </div>
    </div>
@endsection
