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

<p class="h1">
    <i class="fa fa-list"></i>
    Kelola Guru
</p>

<hr>

@if( session('message-success') )
    <div class="alert alert-success">
        {{ session('message-success') }}
    </div>
@endif

<div class="container" style="padding: 0.6rem 0rem 0.6rem 0rem">
    <div class="row">
        <div class="col col-md-3 text-left">
        </div>
        <div class="col-md-6">
        </div>
        <div class="col col-md-3 text-right">
            <a href="" class="btn btn-primary btn-sm">
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
        </tr>
    </thead>

    <tbody>
        @foreach($teachers as $teacher)
        <tr>
            <td> {{ $loop->iteration }} </td>
            <td> {{ $teacher->name }} </td>
            <td> {{ $teacher->username }} </td>
            <td> {{ $teacher->teacher_id }} </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection

@section('script')

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