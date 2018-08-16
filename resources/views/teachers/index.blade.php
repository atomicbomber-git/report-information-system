@extends('layouts.admin')

@section('title', 'Kelola Guru')

@section('styles')

<link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap4.min.css') }}">

<style>
    #table {
        border-collapse: collapse !important;
    }
</style>

@endsection

@section('content')

<h1>
    <i class="fa fa-list"></i>
    Kelola Guru
</h1>

@if( session('message-success') )
    <div class="alert alert-success">
        {{ session('message-success') }}
    </div>
@endif

<hr>

<div class="container">
    <div class="row">
        <div class="col text-left">
        </div>
        <div class="col-3">
        </div>
        <div class="col text-right">
            <a href="{{ route('teachers.create') }}" class="btn btn-primary btn-sm">
                Tambahkan Guru
                <i class="fa fa-plus"></i>
            </a>
        </div>
    </div>
</div>

<hr>

<table id="table" class='table table-striped table-responsive-xl table-sm'>
    <thead class='thead-dark'>
        <tr>
            <th> # </th>
            <th> Nama </th>
            <th> Nama Pengguna </th>
            <th> NIK </th>
            <th> Kendali </th>
        </tr>
    </thead>

    <tbody>
        @foreach($teachers as $teacher)
        <tr>
            <td> {{ $loop->iteration }}. </td>
            <td> {{ $teacher->name }} </td>
            <td> {{ $teacher->username }} </td>
            <td> {{ $teacher->teacher_id }} </td>
            <td>
                <a href="{{ route('teachers.edit', $teacher->id) }}" class="btn btn-sm btn-dark">
                    Sunting
                    <i class="fa fa-pencil"></i>
                </a>

                {{-- TEACHER DELETE button --}}
                <form
                    action="{{ route('teachers.delete', $teacher->id) }}"
                    method="POST"
                    class="form-delete d-inline-block"
                    data-teacher="{{ $teacher->name }}"
                    >
                    @csrf
                    <button class="btn btn-sm btn-danger">
                        Hapus
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

<script src="{{ asset('js/jquery.dataTables.js') }}"> </script>
<script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"> </script>
<script src="{{ asset('js/sweetalert.min.js') }}"> </script>

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

        // Teacher deletion confirmation popup
        $(".form-delete").each(function() {
            let form = $(this);
            form.submit(function(e) {
                e.preventDefault()

                let label = form.data('teacher');

                swal(`Anda yakin ingin menghapus guru ${label}?`, {
                    title: "Konfirmasi Penghapusan",
                    icon: "warning",
                    buttons: ["Tidak", "Ya"],
                    dangerMode: true
                })
                .then(function(willDelete) {
                    if (willDelete) {
                        form.off("submit").submit();
                    }
                });
            });

        });

        $('.alert-success').fadeOut(3000);
    });
</script>
@endsection