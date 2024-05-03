@extends('layouts.app')

@section('title', '図書館登録')

@section('content')
<div class="container-fluid" style="max-width: 1200px">
    <header class="bg-info py-3 px-4 mb-3 text-center">
        <h1 class="btn-collapse mb-0 text-white h4">
            図書館登録
        </h1>
    </header>
    <div class="text-center">
        <p class="text-secondary">蔵書検索の対象図書館を登録します。現在地または住所で検索できます。</p>
        <div class="border-bottom"></div>
    </div>
    <button class="btn btn-primary mt-4 btn-lg"><i class="fas fa-map-marker-alt me-1"></i>現在地から取得</button>
    <div class="pt-3">
        <div class="input-group my-2">
            <input type="text" name="area" class="form-control bg-white" placeholder="住所または郵便番号で検索">
            <button class="btn btn-primary" type="button">検索</button>
        </div>
        <p class="text-left"><small class="text-secondary">
            ●例1: 東京都 千代田区 丸の内<br>（都道府県と市町村の間にはスペースを入力）<br>●例2: 1000001（郵便番号で検索）</small></p>
    </div>
</div>
@endsection
