@extends('layouts.app')

@section('title', '図書館登録')

@section('content')
<div class="container-fluid" style="max-width: 1200px">
    <header class="bg-info py-3 px-4 mb-3 text-center">
        <h1 class="btn-collapse mb-0 text-white h4">
            {{ $userLibrary ? 'お気に入り図書館編集' : 'お気に入り図書館登録' }}
        </h1>
    </header>
    <div class="text-center">
        <p class="text-secondary">蔵書検索の対象図書館を登録します。現在地または住所で検索できます。</p>
        @if ($userLibrary)
            <h4>お気に入り図書館エリア：<span>{{ $userLibrary->system_name }}</span></h4>
        @endif
        @if ($userLibraries)
            <div>
                @foreach ($userLibraries as $library)
                    <span class="badge bg-info me-1">{{ $library['short'] }}</span>
                @endforeach
            </div>
        @endif
        <div class="border-bottom mt-1"></div>
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
    <div v-if="errorMessage" class="alert alert-danger">
        @{{ errorMessage }}
    </div>
    @if ($errors->any())
        <div class="alert alert-danger">
            <p>{{ $errors->first() }}</p>
        </div>
    @endif
    <div v-if="loading">
        <div class="text-center">
            <h1><i class="fas fa-spinner fa-pulse"></i></h1>
        </div>
    </div>
    <div class="pt-3" v-if="!loading && libraries.length > 0">
        <p class="fw-bold">近隣の図書館から一つ選択してください<br>※同じ市区町村など同一の蔵書システムの図書館は同時に登録されます。</p>
        <div v-for="(library, index) in libraries" :key="library.libid">
            <input type="radio" :id="'library-' + library.libid" :value="library.systemid" v-model="selectedLibraryId">
            <label :for="'library-' + library.libid">@{{ library.formal }}(@{{ library.systemname }})</label>
        </div>
        <form action="{{ route('user.library.store') }}" method="POST">
            @csrf
            <input type="hidden" name="systemid" :value="selectedLibrary?.systemid">
            <input type="hidden" name="systemname" :value="selectedLibrary?.systemname">
            <button class="btn btn-success mt-3" :disabled="!selectedLibraryId">
                {{ $userLibrary ? '図書館を更新' : '図書館を登録' }}
            </button>
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
                errorMessage: null,
                loading: false,
            };
        },
        computed: {
            selectedLibrary() {
                return this.libraries.find(library => library.systemid === this.selectedLibraryId);
            }
        },
        methods: {
            getLocation() {
                this.loading = true;
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        position => {
                            this.latitude = position.coords.latitude;
                            this.longitude = position.coords.longitude;
                            console.log("Latitude:", this.latitude, "Longitude:", this.longitude);
                            this.fetchLibraries(this.latitude, this.longitude);
                        },
                        error => {
                            this.loading = false;
                            this.errorMessage = "位置情報の取得に失敗しました。";
                            console.error("Error occurred. Error code: " + error.code);
                        }
                    );
                } else {
                    this.loading = false;
                    this.errorMessage = "位置情報がサポートされていません。";
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
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    } else {
                        return response.json().then(err => {
                            throw new Error(err.message || 'Unknown error occurred');
                        });
                    }
                })
                .then(data => {
                    this.libraries = Object.values(data);
                    console.log("Fetched libraries:", this.libraries);
                    this.loading = false;
                })
                .catch(error => {
                    this.loading = false;
                    this.errorMessage = "図書館情報の取得に失敗しました。";
                    console.error("Error fetching libraries:", error);
                });
            },
        }
    }).mount('#app');
</script>
@endsection
