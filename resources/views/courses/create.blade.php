@extends('layouts.admin')

@section('title', 'Tambah Mata Pelajaran')

@section('content')

<p class="h1">
    <i class="fa fa-list"></i>
    Tambah Mata Pelajaran
    Kelas {{ $information->grade }}
    Tahun Ajaran {{ $information->term->code }}
</p>

<hr/>

<div class="container" style="padding: 1.2rem 0rem 1.2rem 0rem">
    <div class="row">
        <div class="col col-md-3 text-left">
            <a href="{{ route('courses.grade_index', [$information->term->id, $information->grade]) }}" class="btn btn-sm btn-secondary">
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

</hr>

<form method="POST" action="{{ route('courses.create', [$information->term->id, $information->grade]) }}" style="max-width: 20rem">
    @csrf
    
    <div class="form-group">
        <label for="name"> Nama Mata Pelajaran </label>
        <input id="name" name="name" type="text" class="form-control">
    </div>

    <div class="form-group">
        <label for="group"> Kelompok Mata Pelajaran </label>
        <select class="form-control" name="group" id="group">
            <option value="A"> A </option>
            <option value="B"> B </option>
        </select>
    </div>

    <input type="hidden" name="term_id" value="{{ $information->term->id }}">
    <input type="hidden" name="grade" value="{{ $information->grade }}">
    <input type="hidden" name="description" value="Test Test">

    <div class="form-group text-right">
        <button class="btn btn-primary btn-sm">
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