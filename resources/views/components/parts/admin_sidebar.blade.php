<div class="container-fluid px-5 my-5">
    <div class="row justify-content-center">
        <ul class="list-group list-group-flush accordion" id="accordion">
            <a href="{{ route('admin.top') }}" class="list-group-item list-group-item-action"><i class="fas fa-users-cog me-1"></i> ホーム</a>
            <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action"><i class="fa fa-users me-1"></i> 会員一覧</a>
            <a href="{{ route('admin.reviews.index') }}" class="list-group-item list-group-item-action"><i class="far fa-comments me-1"></i> レビュー一覧</a>
            <a href="{{ route('admin.level_categories.index') }}" class="list-group-item list-group-item-action"><i class="fas fa-layer-group me-1"></i> レベルカテゴリ一覧</a>
            <a href="{{ route('admin.categories.index') }}" class="list-group-item list-group-item-action"><i class="fas fa-laptop-code me-1"></i> 技術カテゴリ一覧</a>
            <a href="{{ route('admin.contacts.index') }}" class="list-group-item list-group-item-action"><i class="far fa-envelope"></i> お問い合わせ一覧</a>
            <a class="list-group-item list-group-item-action" href="{{ route('admin.logout') }}"
                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();"
                                >
                <i class="fas fa-sign-out-alt me-1"></i>
                ログアウト
            </a>
        </ul>
    </div>
</div>
