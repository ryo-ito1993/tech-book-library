<div id="flash_message">
    @if(Session::has('flash_alert'))
        <div @click="hide=true" :class="{'d-none':hide}" class="alert alert-danger m-0 text-center" style="position: fixed; top: 10%; width: 100%; z-index: 9999; opacity: 0.8;">
            {{ Session::get('flash_alert') }}
        </div>
    @endif

    @if (session('status'))
        <div @click="hide=true" :class="{'d-none':hide}" class="alert alert-success m-0 text-center" style="position: fixed; top: 10%; width: 100%; z-index: 9999; opacity: 0.8;">
            {{ session('status') }}
            {{-- <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button> --}}
        </div>
    @endif
</div>

@section('flash_message_script')
<script>
    Vue.createApp({
        el: '#flash_message',
        data() {
            return {
                hide: false
            }
        }
    }).mount('#flash_message');
</script>
@endsection
