@extends('layouts.app')

@section('title', 'TOPページ')

@section('content')
    <div class="top-image" style="background-image: url('{{ asset('images/top-image.jpg') }}')">
        <div>
            <h1 class="display-1" style="font-family: 'Kaisei Tokumin', serif;">TechBookLibrary</h1>
            <p class="fs-5">TechBookLibraryは技術書検索と図書館検索を同時に行うことができるサービスです。</p>
            @guest
                <a href="{{ route('login.guest') }}" class="btn btn-success mt-3">ゲストログインで試してみる</a>
            @endguest
        </div>
    </div>

    <div class="border-bottom" style="padding:10px;"></div>

    <div class="text-center items-center">
        <div class="mb-4"></div>

        <div class="items-center justify-center mb-2">
            <img src="{{ asset('images/library.png') }}" alt="library" style="height: 130px; width: 190px;">
        </div>

        <div class="flex-grow">
        <h4 class="title-font font-medium mb-3">図書館を登録をしよう</h4>
        <p class="leading-relaxed">　ログイン後、「図書館」ボタンから図書館エリアを登録してみよう。</p>
        </div>

        <div class="border-bottom" style="padding:10px;"></div>
        </div>
    </div>

    <div class="text-center items-center">
        <div class="mb-4"></div>

        <div class="items-center justify-center mb-2">
            <img src="{{ asset('images/read-book.png') }}" alt="read-book" style="height: 160px;">
        </div>

        <div class="flex-grow">
        <h4 class="title-font font-medium">技術書を探そう</h4>
        <p class="leading-relaxed">　技術書を検索して、図書館蔵書の空き状況を確認したり、お気に入り登録ができます。</p>
        </div>
            <div class="border-bottom" style="padding:10px;"></div>
        </div>
    </div>

    <div class="text-center items-center">
        <div class="mb-4"></div>

        <div class="items-center justify-center mb-2">
            <img src="{{ asset('images/review-book.png') }}" alt="review-book" style="height: 160px;">
        </div>

        <div class="flex-grow">
            <h4 class="title-font font-medium">技術書のレビューを書こう</h4>
            <p class="leading-relaxed">　技術書のレビューを書いたり、他の人のレビューを参考にすることができます。</p>
        </div>

        <div class="border-bottom" style="padding:10px;"></div>
    </div>
<style>

</style>
@endsection
