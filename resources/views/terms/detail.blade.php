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

<div style="width: 100%; padding: 1.4rem; text-align: right">
    <a 
        class="btn btn-primary btn-sm"
        href="{{ route('room_terms.create', $term) }}"
        >
        Tambah Kelas
        <i class="fa fa-plus"></i>
    </a>
</div>
    

<table id="table" class='table table-striped table-sm table-responsive-xl'>
    <thead class='thead-dark'>
        <tr>
            <th> # </th>
            <th> Kelas </th>
            <th> Semester </th>
            <th> Wali Kelas </th>
            <th> Kendali </th>
        </tr>
    </thead>

    <tbody>
        @foreach($term->room_terms as $room_term)
        <tr>
            <td> {{ $loop->iteration }}. </td>
            <td> {{ $room_term->room->name }}</td>
            <td> {{ $room_term->even_odd }} </td>
            <td> {{ $room_term->teacher->user->name }} </td>
            <td>
                <a href="" class="btn btn-dark btn-sm">
                    Detail
                    <i class="fa fa-list-alt"></i>
                </a>
                
                <form method="POST" class="room_term_delete_form" style="display: inline-block" action="{{ route('room_terms.delete', $room_term) }}">
                    @csrf
                    <button class="btn btn-danger btn-sm">
                        <i class="fa fa-trash"></i>
                    </button>
                </form>
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
            // DataTable
            $(".table").DataTable({
                "language": {
                    "url": "{{ asset("Indonesian.json") }}"
                },
                "pagingType": "full",
                "lengthMenu": [12, 24, 36],
                "pageLength": 12
            });

            // Handle delete form submissions
            $(".room_term_delete_form").submit(function(e) {
                e.preventDefault();
                let form = $(this);

                swal({
                    title: 'Menghapus Data Kelas',
                    icon: 'warning',
                    text: 'Apakah Anda yakin hendak menghapus kelas ini?',
                    dangerMode: true,
                    buttons: true
                })
                .then(function(value) {
                    if (value) {
                        form.off('submit').submit();
                    }
                });
            });
        });
    </script>
@endsection