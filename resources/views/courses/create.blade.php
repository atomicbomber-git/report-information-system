@extends('layouts.admin')

@section('title', 'Tambah Mata Pelajaran')

@section('content')

<p class="h1">
    <i class="fa fa-list"></i>
    Tambah Mata Pelajaran
    Kelas {{ $information->grade }}
    Tahun Ajaran {{ $information->term->code }}
</p>

<hr>

<form action="" style="max-width: 20rem">
    <div class="form-group">
        <label for="name"> Nama Mata Pelajaran </label>
        <input id="name" name="name" type="text" class="form-control">
    </div>

    <div class="form-group">
        <label for="passing_grade"> Nilai KKM (Kriteria Ketuntasan Minimum) </label>
        <input id="passing_grade" type="number" min="0" max="100" class="form-control">
    </div>

    <div class="form-group">
        <label for="group"> Kelompok Mata Pelajaran </label>
        <select class="form-control" name="group" id="group">
            <option value=""> A </option>
            <option value=""> B </option>
        </select>
    </div>

    <div class="form-group text-right">
        <button class="btn btn-primary">
            Tambahkan
            <i class="fa fa-plus"></i>
        </button>
    </div>
</form>

@if( session('message-success') )
    <div class="alert alert-success">
        {{ session('message-success') }}
    </div>
@endif
    
@endsection