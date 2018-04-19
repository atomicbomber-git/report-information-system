@extends('layouts.admin')

@section('title', 'Seluruh Tahun Ajaran')

@section("styles")
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap4.min.css') }}">

    <style>
        #table {
            border-collapse: collapse !important;
        }
    </style>
@endsection

@section('content')

<p class="h1">
    <i class="fa fa-list"></i>
    Daftar Kelas Pada Tahun Ajaran {{ $term->term_start }} - {{ $term->term_end }}
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
            <a href="{{ route('terms.index') }}" class="btn btn-sm btn-secondary">
                <i class="fa fa-arrow-left"></i>
                Kembali
            </a>
        </div>
        <div class="col-md-6">
        </div>
        <div class="col col-md-3 text-right">
            @if($vacant_room_count !== 0)
            <a class="btn btn-primary btn-sm" href="{{ route('room_terms.create', $term) }}">
                Tambah Kelas
                <i class="fa fa-plus"></i>
            </a>
            @else
            <button
                id="whatever"
                class="btn btn-muted btn-sm"
                data-toggle="tooltip" data-placement="top" title="Seluruh kelas yang dapat ditambahkan telah ditambahkan ke dalam tahun ajaran ini"
                >
                
                Tambah Kelas
                <i class="fa fa-plus"></i>
            </button>
            @endif
        </div>
    </div>
</div>

<hr>

<table id="table" class='table table-striped table-sm table-responsive-xl' style="display: none">
    <thead class='thead-dark'>
        <tr>
            <th> # </th>
            <th> Kelas </th>
            <th> Semester </th>
            <th> Wali Kelas </th>
            <th> Jumlah Siswa </th>
            <th> Kendali </th>
        </tr>
    </thead>

    <tbody>
        @foreach($room_terms as $room_term)
        <tr>
            <td> {{ $loop->iteration }}. </td>
            <td> {{ $room_term->room_name }}</td>
            <td> {{ $room_term->even_odd }} </td>
            <td> {{ $room_term->teacher_name }} </td>
            <td> {{ $room_term->report_count }} </td>
            <td>
                <a href="{{ route('room_terms.detail', $room_term->id) }}" class="btn btn-dark btn-sm">
                    Detail
                    <i class="fa fa-list-alt"></i>
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection

@section('script')
    <script src="{{ asset('js/sweetalert.min.js') }}"> </script>
    <script src="{{ asset('js/jquery.dataTables.js') }}"> </script>
    <script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"> </script>


    <script>
        $(document).ready(function() {
            // Tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // DataTable
            $(".table").DataTable({
                "language": {
                    "url": "{{ asset("Indonesian.json") }}"
                },
                "pagingType": "full",
                "lengthMenu": [12, 24, 36],
                "pageLength": 12
            });
            
            window.setTimeout(function() {
                $(".table").fadeIn();
            }, 500)
        });
    </script>
@endsection