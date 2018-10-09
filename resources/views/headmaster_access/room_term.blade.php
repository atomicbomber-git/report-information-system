@extends('layouts.admin')

@section('title', "Daftar Kelas Pada Tahun Ajaran " . $room_term->term->code)

@section('content')

<h1>
    <i class="fa fa-list"></i>
    Rata-Rata Nilai Siswa 
</h1>

<p class="lead">
    Kelas {{ $room_term->room->name }} Pada Tahun Ajaran {{ $room_term->term->code }}
</p>

@if( session('message-success') )
    <div class="alert alert-success">
        {{ session('message-success') }}
    </div>
@endif

<hr>

<div class="container">
    <div class="row">
        <div class="col text-left">
            <a href="{{ route('headmaster_access.room_terms', [$room_term->term->id, $room_term->getOriginal('even_odd')]) }}" class="btn btn-secondary btn-sm">
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

<table class='table table-striped table-responsive-xl table-sm'>
    <thead class='thead-dark'>
        <tr>
            <th> # </th>
            <th> Siswa </th>
            <th> Nilai Pengetahuan </th>
            <th> Nilai Keterampilan </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($reports as $report)
        <tr>
            <td> {{ $loop->iteration }} </td>
            <td> {{ $report['student_name'] }} </td>
            <td> {{ number_format($knowledge_grades[$report['student_id']], 2, ',', '') ?? '-' }} </td>
            <td> {{ number_format($skill_grades[$report['student_id']], 2, ',', '') ?? '-' }} </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection