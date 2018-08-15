@extends('layouts.admin')

@section('title', "Kelola Ekstrakurikuler $extracurricular->name Tahun Ajaran " . $extracurricular->term->code)

@section('content')

<h1>
    <i class="fa fa-list"></i>
    Kelola Ekstrakurikuler
</h1>

<p class="lead">
    Tahun Ajaran {{ $extracurricular->term->code }}
</p>

<hr/>

<div class="container">
    <div class="row">
        <div class="col text-left">
            <a href="{{ route('extracurriculars.index_term', $extracurricular->term_id) }}" class="btn btn-secondary btn-sm">
                <i class="fa fa-arrow-left"></i>
                Kembali
            </a>
        </div>
        <div class="col-3">

        </div>
        <div class="col text-right">

        </div>
    </div>
</div>

<hr/>

@if( session('message-success') )
    <div class="alert alert-success">
        {{ session('message-success') }}
    </div>
@endif

<div class="container">
    <div class="d-inline-block">
        <h2 class="mb-3">
            <i class="fa fa-pencil"></i>
            Sunting Ekstrakurikuler {{ $extracurricular->name }}
        </h2>
    
        <form
            method='POST'
            action='{{ route('extracurriculars.edit', $extracurricular) }}'>
        
            @csrf
        
            <div class='form-group'>
                <label for='name'> Nama: </label>
            
                <input
                    id='name' name='name' type='text'
                    value='{{ old('name', $extracurricular->name) }}'
                    class='form-control {{ $errors->has('name', 'is-invalid') }}'>
            
                <div class='invalid-feedback'>
                    {{ $errors->first('name') }}
                </div>
            </div>

            <div class='form-group'>
                <label for='teacher_id'> Guru Pembimbing: </label>
                <select name='teacher_id' id='teacher_id' class='form-control'>
                    @foreach($teachers as $teacher)
                    <option {{ old('teacher_id', $extracurricular->teacher_id) !== $teacher->id ?: 'selected' }} value='{{ $teacher->id }}'> {{ $teacher->name }} ({{ $teacher->teacher_id }}) </option>
                    @endforeach
                </select>
                <div class='invalid-feedback'>
                    {{ $errors->first('teacher_id') }}
                </div>
            </div>
    
            <div class="text-right mt-5">
                <button class="btn btn-primary btn-sm">
                    Perbarui Data
                    <i class="fa fa-pencil"></i>
                </button>
            </div>
        </form>
    </div>
</div>

@endsection