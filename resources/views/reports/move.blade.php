@extends('layouts.admin')

@section('title', 'Pindah Kelas')
@section('content')

<p class="h2">
    <i class="fa fa-arrow-up"></i>
    Pindah Kelas dari {{ $room_term->name }} Tahun Ajaran {{ $room_term->code }} Semester {{ $room_term->even_odd }}
</p>

<hr>

@if( session('message-success') )
    <div class="message alert alert-success">
        {{ session('message-success') }}
    </div>

    <script>
        window.setTimeout(function() {
            $('.message').fadeOut();
        }, 3000);
    </script>
@endif

<div class="container" style="padding: 0.6rem 0rem 0.6rem 0rem">
    <div class="row">
        <div class="col col-md-3 text-left">
            <a href="{{ route('terms.detail', $room_term->term_id) }}" class="btn btn-sm btn-secondary">
                <i class="fa fa-arrow-left"></i>
                Kembali
            </a>
        </div>
        <div class="col-md-6">
        </div>
        <div class="col col-md-3 text-right">
        </div>
    </div>
</div>

@endsection

@section('script')
@endsection