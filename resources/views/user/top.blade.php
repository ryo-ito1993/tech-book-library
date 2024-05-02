@extends('layouts.app')

@section('title', 'TOPページ')

@section('content')
    <div class="text-center">
        <h3 class="p-4">TechBookLibrary</h3>
            　TechBookLibraryは技術書検索と図書館検索を同時に行うことができるサービスです。
    </div>

    <div class="border-bottom" style="padding:10px;"></div>

    <div class="text-center items-center">
        <div class="mb-4"></div>

        <div class="items-center justify-center">
        {{-- <%= image_tag 'map.png', size: '150x150' %> --}}
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

        <div class="items-center justify-center">
        {{-- <%= image_tag 'book.png', size: '150x150' %> --}}
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

        <div class="items-center justify-center">
        {{-- <%= image_tag 'book.png', size: '150x150' %> --}}
        </div>

        <div class="flex-grow">
            <h4 class="title-font font-medium">技術書のレビューを書こう</h4>
            <p class="leading-relaxed">　技術書のレビューを書いたり、他の人のレビューを参考にすることができます。</p>
        </div>

        <div class="border-bottom" style="padding:10px;"></div>
    </div>

@endsection
