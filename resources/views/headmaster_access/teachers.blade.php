@extends('layouts.admin')

@section('title', "Daftar Guru Pada Tahun $term->code")

@section('content')

<h1>
    <i class="fa fa-list"></i>
    Daftar Guru Pada Tahun Ajaran {{ $term->code }}
</h1>

<p class="lead">
Semester {{ \App\RoomTerm::EVEN_ODD[$even_odd] }}
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
            <a href="{{ route('headmaster_access.terms', ['term_id' => $term->id]) }}" class="btn btn-secondary btn-sm">
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

<div class="alert alert-info">
    Total Jumlah Guru: {{ $teachers->count() }}
</div>

<table class='table table-striped table-responsive-xl table-sm'>
    <thead class='thead-dark'>
        <tr>
            <th> # </th>
            <th> Nama Guru </th>
            <th> NIK </th>
            <th> Status </th>
            <th style="width: 40rem"> Mata Pelajaran Yang di Ajar </th>
            <th> Kelas Perwalian </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($teachers as $teacher)
        <tr>
            <td> {{ $loop->iteration }}. </td>
            <td> {{ $teacher->name }} </td>
            <td> {{ $teacher->teacher_id }} </td>
            <td> {{ $teacher->active ? 'Aktif' : 'Nonaktif' }} </td>
            <td> {{ $teacher->courses }} </td>
            <td> {{ $teacher_classes[$teacher->teacher_id]->classes ?? '-' }} </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection