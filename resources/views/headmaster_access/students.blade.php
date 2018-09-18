@extends('layouts.admin')
@section('title', "Data Seluruh Siswa")
@section('content')

@section('styles')

<link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap4.min.css') }}">

<style>
    #table {
        border-collapse: collapse !important;
    }
</style>

@endsection

<h1>
    <i class="fa fa-list"></i>
    Data Siswa
</h1>

@if( session('message-success') )
    <div class="alert alert-success">
        {{ session('message-success') }}
    </div>
@endif



<hr>

<div class="container">
    <div class="row">
        <div class="col-md-3">
        </div>
        <div class="col"></div>
        <div class="col-md-3 text-right">
            <a href="{{ route('headmaster_access.terms') }}" class="btn btn-secondary btn-sm">
                <i class="fa fa-arrow-left"></i>
                Kembali
            </a>
        </div>
    </div>
</div>

<hr>

<div class="container">
    <table id="#table" class="table table-striped table-sm">
        <thead class="thead-dark">
            <tr>
                <th> # </th>
                <th> Nama </th>
                <th> Nomor Induk </th>
                <th> Jenis Kelamin </th>
                <th> Jenjang (Kelas) </th>
                <th> Kendali </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $student)
            <tr>
                <td> {{ $loop->iteration }}. </td>
                <td> {{ $student->name }} </td>
                <td> {{ $student->student_id }} </td>
                <td> {{ \App\Student::SEXES[$student->sex] }} </td>
                <td> {{ $student->current_grade }} </td>
                <td>
                    <a href="{{ route('headmaster_access.student', $student->id) }}" class="btn btn-primary btn-sm btn-dark">
                        Detail
                        <i class="fa fa-list-alt"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('script')
<script src="{{ asset('js/jquery.dataTables.js') }}"> </script>
<script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"> </script>
<script src="{{ asset('js/sweetalert.min.js') }}"> </script>
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
    });
</script>
@endsection