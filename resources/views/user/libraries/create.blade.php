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
    <button class="btn btn-primary mt-4 btn-lg" @click="getLocation"><i class="fas fa-map-marker-alt me-1"></i>現在地から取得</button>
    <div class="pt-3">
        <div class="input-group my-2">
            <input type="text" name="area" class="form-control bg-white" placeholder="住所または郵便番号で検索">
            <button class="btn btn-primary" type="button">検索</button>
        </div>
        <p class="text-left"><small class="text-secondary">
            ●例1: 東京都 千代田区 丸の内<br>（都道府県と市町村の間にはスペースを入力）<br>●例2: 1000001（郵便番号で検索）</small></p>
    </div>
    <div class="pt-3" v-if="libraries.length > 0">
        <p>近隣の図書館から一つ選択してください<br>※同じ市区町村など同一の蔵書システムの図書館は同時に登録されます。</p>
        <div v-for="(library, index) in libraries" :key="library.libid">
            <input type="radio" :id="'library-' + library.libid" :value="library.systemid" v-model="selectedLibraryId">
            <label :for="'library-' + library.libid">@{{ library.formal }}(@{{ library.systemname }})</label>
        </div>
        <form action="{{ route('user.library.store') }}" method="POST">
            @csrf
            <input type="hidden" name="systemid" :value="selectedLibrary?.systemid">
            <input type="hidden" name="systemname" :value="selectedLibrary?.systemname">
            <button class="btn btn-success mt-3">登録</button>
        </form>
    </div>


</div>
@endsection
@section('script')
<script>
    Vue.createApp({
        data() {
            return {
                latitude: null,
                longitude: null,
                libraries: [],
                selectedLibraryId: null,
            };
        },
        computed: {
            selectedLibrary() {
                return this.libraries.find(library => library.systemid === this.selectedLibraryId);
            }
        },
        methods: {
            getLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        position => {
                            this.latitude = position.coords.latitude;
                            this.longitude = position.coords.longitude;
                            console.log("Latitude:", this.latitude, "Longitude:", this.longitude);
                            this.fetchLibraries(this.latitude, this.longitude);
                        },
                        error => {
                            console.error("Error occurred. Error code: " + error.code);
                        }
                    );
                } else {
                    console.error("Geolocation is not supported by this browser.");
                }
            },
            fetchLibraries(latitude, longitude) {
                fetch(`/api/libraries`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        latitude: latitude,
                        longitude: longitude,
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    this.libraries = Object.values(data); // オブジェクトを配列に変換
                    console.log("Fetched libraries:", this.libraries);
                })
                .catch(error => {
                    console.error("Error fetching libraries:", error);
                });
            },
        }
    }).mount('#app');
</script>
@endsection
