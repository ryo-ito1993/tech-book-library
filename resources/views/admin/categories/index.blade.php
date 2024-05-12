@extends('layouts.admin.app')

@section('title','カテゴリ一覧')

@section('content')
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between">
            <h5 class="m-2">カテゴリ一覧：{{ $categories->total() . '件中' . $categories->firstItem() . '-' . $categories->lastItem() }}件</h5>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary float-right">新規カテゴリ登録</a>
        </div>
        <div class="card-body table-responsive">
        <div class="bg-white mb-3">

            <div class="d-flex justify-content-end align-items-center mb-3">
                {{ $categories->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
            <table class="table table-bordered">
                <thead class="">
                    <tr>
                        <th scope="col" class="text-nowrap">カテゴリ名</th>
                        <th scope="col" class="text-nowrap">編集</th>
                        <th scope="col" class="text-nowrap">削除</th>
                    </tr>
                </thead>
                <tbody style="border-style: none">
                    @foreach ($categories as $category)
                        <tr>
                            <td class="text-nowrap px-2">{{ $category->name }}</td>
                            <td class="text-nowrap px-2 text-center">
                                <a href="{{ route('admin.categories.edit', ['category' => $category]) }}" class="btn btn-success">編集</a>
                            </td>
                            <td class="text-nowrap px-2 text-center">
                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onclick="return confirm('このカテゴリを削除しますか？');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">削除</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-end align-items-center mb-3">
                {{ $categories->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
@endsection
