@extends('layouts.app')

@section('title', '図書館登録')

@section('content')
<div class="container-fluid" style="max-width: 1200px">
    <header class="bg-primary py-2 px-4 mb-3 text-center">
        <h1 class="btn-collapse mb-0 text-white h5">
            {{ $userLibrary ? 'お気に入り図書館編集' : 'お気に入り図書館登録' }}
        </h1>
    </header>
    <div class="text-center">
        <p class="mb-4">蔵書検索の対象図書館を登録します。現在地または都道府県・市区町村で検索できます。</p>
        @if ($userLibrary)
            <h5 class="fw-bold mb-3">お気に入り図書館エリア：<span>{{ $userLibrary->system_name }}</span></h5>
        @endif
        @if ($userLibraries)
            <div class="my-3">
                @foreach ($userLibraries as $library)
                    <span class="badge bg-info me-1 mb-2 fs-6">{{ $library['short'] }}</span>
                @endforeach
            </div>
        @endif
        <div class="border-bottom border-1 mt-1"></div>
    </div>
    <button class="btn btn-primary mt-4 btn-lg" @click="getLocation"><i class="fas fa-map-marker-alt me-1"></i>現在地から取得</button>
    <div class="pt-3">
        <div class="input-group my-2">
            <select class="form-select bg-white" v-model="selectedPrefecture" @change="fetchCities">
                <option value="">都道府県を選択</option>
                <option v-for="prefecture in prefectures" :value="prefecture">@{{ prefecture.name }}</option>
            </select>
            <select class="form-select bg-white" v-model="selectedCity" :disabled="cities.length === 0">
                <option value="">市区町村を選択</option>
                <option v-for="city in cities" :value="city">@{{ city.name }}</option>
            </select>
            <button class="btn btn-primary" type="button" :disabled="!selectedCity" @click="fetchLibrariesByPrefCity">検索</button>

        </div>
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
                prefectures: @json($prefectures),
                selectedPrefecture: '',
                cities: [],
                selectedCity: '',
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
                this.libraries = [];
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
                fetch(`/api/getLibrariesByLocation`, {
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
            fetchLibrariesByPrefCity(){
                this.loading = true;
                this.libraries = [];
                if (this.selectedCity) {
                    fetch(`/api/getLibrariesByPrefCity`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            pref: this.selectedPrefecture.name,
                            city: this.selectedCity.name,
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
                }
            },
            fetchCities(){
                this.cities = [];
                this.selectedCity = '';
                if (this.selectedPrefecture) {
                    fetch(`/api/getCitiesByPrefecture/${this.selectedPrefecture.id}`)
                    .then(response => {
                        if (response.ok) {
                            return response.json();
                        } else {
                            throw new Error('Failed to fetch cities');
                        }
                    })
                    .then(data => {
                        this.cities = data;
                    })
                    .catch(error => {
                        console.error('Error fetching cities:', error);
                    });
                }
            },

        }
    }).mount('#app');
</script>
@endsection
