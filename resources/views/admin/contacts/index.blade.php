@extends('layouts.admin.app')

@section('title','お問合せ一覧')

@section('content')
    <div class="card shadow">
        <div class="card-header">
            <h5 class="m-2">お問合せ一覧：{{ $contacts->total() . '件中' . $contacts->firstItem() . '-' . $contacts->lastItem() }}件</h5>
        </div>
        <div class="card-body table-responsive">
        <div class="bg-white mb-3">
            <x-parts.search_box route='admin.contacts.index' :contactName="\Request::get('contactName') ?? ''" :email="\Request::get('email') ?? ''" :status="\Request::get('status') ?? ''"></x-parts.search_box>

            <div class="d-flex justify-content-end align-items-center mb-3">
                {{ $contacts->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
            <table class="table table-bordered">
                <thead class="">
                    <tr>
                        <th scope="col" class="text-nowrap">名前</th>
                        <th scope="col" class="text-nowrap">メールアドレス</th>
                        <th scope="col" class="text-nowrap">ステータス</th>
                        <th scope="col" class="text-nowrap">詳細</th>
                    </tr>
                </thead>
                <tbody style="border-style: none">
                    @foreach ($contacts as $contact)
                        <tr>

                            <td class="text-wrap px-2">{{ $contact->name }}</a></td>
                            <td class="text-nowrap px-2">{{ $contact->email }}</td>
                            <td class="text-nowrap px-2">
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
                            <td class="text-wrap px-2 text-center">
                                <a href="{{ route('admin.contacts.show', ['contact' => $contact]) }}" class="btn btn-primary">詳細</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-end align-items-center mb-3">
                {{ $contacts->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
@endsection
