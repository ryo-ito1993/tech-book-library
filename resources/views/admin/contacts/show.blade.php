@extends('layouts.admin.app')

@section('title','お問い合わせ情報詳細')

@section('content')
    <div class="card shadow">
        <div class="card-header">
            <h5 class="m-2">お問い合わせ情報詳細</h5>
        </div>
        <div class="card-body m-3">
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <th scope="row">名前</th>
                        <td>{{ $contact->name }}</td>
                    </tr>
                    <tr>
                        <th scope="row">メールアドレス</th>
                        <td>{{ $contact->email }}</td>
                    </tr>

                    <tr>
                        <th scope="row">お問い合わせ本文</th>
                        <td>{!! nl2br(e($contact->message)) !!}</td>
                    </tr>
                    <tr>
                        <th scope="row">ステータス</th>
                        <td>
                            <form action="{{ route('admin.contacts.update_status', $contact) }}" method="post">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="form-control bg-white" onchange="this.form.submit()">
                                    @foreach (App\Models\Contact::statuses() as $statusValue => $statusName)
                                        <option value="{{ $statusValue }}" @if ($contact->status == $statusValue) selected @endif>
                                            {{ $statusName }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">登録日時</th>
                        <td>{{ $contact->created_at }}</td>
                    </tr>
                    <tr>
                        <th scope="row">更新日時</th>
                        <td>{{ $contact->updated_at }}</td>
                    </tr>
            </table>
            <div class="d-flex justify-content-center">
                <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-dark me-4">一覧へ戻る</a>
            </div>
        </div>
    </div>
@endsection
