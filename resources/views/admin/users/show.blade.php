@extends('layouts.admin.app')

@section('title','会員情報詳細')

@section('content')
    <div class="card shadow">
        <div class="card-header">
            <h5 class="m-2">会員情報詳細</h5>
        </div>
        <div class="card-body m-3">
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <th scope="row">氏名</th>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <th scope="row">メールアドレス</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th scope="row">登録図書館エリア</th>
                        @if ($user->library)
                            <td>{{ $user->library->system_name }}</td>
                        @else
                            <td>未登録</td>
                        @endif
                    </tr>
                    <tr>
                        <th scope="row">登録日時</th>
                        <td>{{ $user->created_at }}</td>
                    </tr>
                    <tr>
                        <th scope="row">更新日時</th>
                        <td>{{ $user->updated_at }}</td>
                    </tr>
            </table>
            <div class="text-center">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-dark mr-4">一覧へ戻る</a>
            </div>
        </div>
    </div>
@endsection
