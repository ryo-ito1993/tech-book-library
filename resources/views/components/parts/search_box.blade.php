<div class="bg-white mb-3">
    <form method="GET" action="" id="search-form">
        <div class="form-inlin mb-3">
            @if (isset($userName))
                <div class="form-group mb-3 mr-3">
                    <label for="" class="form-check-label mr-3">会員名</label>
                    <input type="text" name="userName" value="{{ $userName ?? '' }}" class="form-control" />
                </div>
            @endif

            @if (isset($contactName))
                <div class="form-group mb-3 mr-3">
                    <label for="" class="form-check-label mr-3">名前</label>
                    <input type="text" name="contactName" value="{{ $contactName ?? '' }}" class="form-control" />
                </div>
            @endif

            @if (isset($email))
                <div class="form-group mb-3 mr-3">
                    <label for="" class="form-check-label mr-3">メールアドレス</label>
                    <input type="text" name="email" value="{{ $email ?? '' }}" class="form-control" />
                </div>
            @endif

            @if (isset($library))
                <div class="form-group mb-3 mr-3">
                    <label for="" class="form-check-label mr-3">登録図書館エリア</label>
                    <input type="text" name="library" value="{{ $library ?? '' }}" class="form-control" />
                </div>
            @endif

            @if (isset($reviewer))
                <div class="form-group mb-3 mr-3">
                    <label for="" class="form-check-label mr-3">投稿者</label>
                    <input type="text" name="reviewer" value="{{ $reviewer ?? '' }}" class="form-control" />
                </div>
            @endif

            @if (isset($bookName))
                <div class="form-group mb-3 mr-3">
                    <label for="" class="form-check-label mr-3">書籍名</label>
                    <input type="text" name="bookName" value="{{ $bookName ?? '' }}" class="form-control" />
                </div>
            @endif

            @if (isset($levelCategoryName))
                <div class="form-group mb-3 mr-3">
                    <label for="" class="form-check-label mr-3">レベルカテゴリ名</label>
                    <input type="text" name="levelCategoryName" value="{{ $levelCategoryName ?? '' }}" class="form-control" />
                </div>
            @endif

            @if (isset($categoryName))
                <div class="form-group mb-3 mr-3">
                    <label for="" class="form-check-label mr-3">技術カテゴリ名</label>
                    <input type="text" name="categoryName" value="{{ $categoryName ?? '' }}" class="form-control" />
                </div>
            @endif

            @if (isset($status))
                <div class="form-group mb-3 mr-3">
                    <label for="" class="form-check-label mr-3">ステータス</label>
                    <select name="status" class="form-control">
                        <option value="">選択してください</option>
                        @foreach (App\Models\Contact::statuses() as $value => $statusName)
                            <option value="{{ $value }}" @if (request('status') == (string)$value) selected @endif>{{ $statusName }}</option>
                        @endforeach

                    </select>
                </div>
            @endif

        <div class="row justify-content-center">
            <button class="btn btn-dark w-25 me-4">検索</button>
            <a href="{{ route($route) }}" class="btn btn-outline-secondary w-25" name="reset">リセット</a>
        </div>
    </form>
</div>
