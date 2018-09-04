@extends('layouts.admin')

@section('title', "Daftar Kelas Pada Tahun Ajaran $term->code")

@section('content')

<h1>
    <i class="fa fa-list"></i>
    Daftar Kelas Pada Tahun Ajaran {{ $term->code }}
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
            <a href="{{ route('headmaster_access.terms') }}" class="btn btn-secondary btn-sm">
                Kembali
                <i class="fa fa-arrow-left"></i>
            </a>
        </div>
        <div class="col-3">
        </div>
        <div class="col text-right">
        </div>
    </div>
</div>

<hr>

<table class='table table-striped table-responsive-xl table-sm' style="width: 20rem">
    <thead class='thead-dark'>
        <tr>
            <th> # </th>
            <th> Kelas </th>
            <th> Kendali </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($room_terms as $room_term)
        <tr>
            <td> {{ $loop->iteration }}. </td>
            <td> {{ $room_term->room_name }} </td>
            <td>
                <a href="" class="btn btn-dark btn-sm">
                    Laporan Nilai Siswa
                    <i class="fa fa-list"></i>
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection