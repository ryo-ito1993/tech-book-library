@extends('layouts.admin.app')

@section('title','会員一覧')

@section('content')
    <div class="card shadow">
        <div class="card-header">
            <h5 class="m-2">会員一覧：{{ $users->total() . '件中' . $users->firstItem() . '-' . $users->lastItem() }}件</h5>
        </div>
        <div class="card-body table-responsive">
        <div class="bg-white mb-3">
            <x-parts.search_box route='admin.users.index' :userName="\Request::get('userName') ?? ''" :email="\Request::get('email') ?? ''"></x-parts.search_box>

            <div class="d-flex justify-content-end align-items-center mb-3">
                {{ $users->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
            <table class="table table-bordered">
                <thead class="">
                    <tr>
                        <th scope="col" class="text-nowrap">会員ID</th>
                        <th scope="col" class="text-nowrap">名前</th>
                        <th scope="col" class="text-nowrap">メールアドレス</th>
                    </tr>
                </thead>
                <tbody style="border-style: none">
                    @foreach ($users as $user)
                        <tr>
                            <td class="text-wrap px-2">{{ $user->id }}</td>
                            <td class="text-wrap px-2">
                                <a href="{{ route('admin.users.show', ['user' => $user]) }}">{{ $user->name }}</a>
                            </td>
                            <td class="text-nowrap px-2">{{ $user->email }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-end align-items-center mb-3">
                {{ $users->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
@endsection
