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
            <a href="{{ route('room_terms.detail', $room_term->id) }}" class="btn btn-sm btn-secondary">
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

<div style="height: 3rem"></div>

<div class="container">
    <div class="row">
        <form action="{{ route('reports.move', $report) }}" method="POST">

            @csrf

            <div class="form-group">
                <label for="room_term"> Kelas Tujuan: </label>
                <select name="room_term_id" id="room_term" class="form-control">
                @foreach($room_terms as $room_term)
                    <option value="{{ $room_term->id }}">
                        Kelas {{ $room_term->room_name }} Semester {{ \App\RoomTerm::EVEN_ODD[$room_term->even_odd] }}
                    </option>
                @endforeach
                </select>
            </div>

            <div class="form-group text-right">
                <button class="btn btn-primary btn-sm">
                    Setujui Pemindahan
                    <i class="fa fa-check"></i>
                </button>
            </div>

        </form>
    </div>
</div>

@endsection

@section('script')
@endsection