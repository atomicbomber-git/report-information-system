@extends('layouts.admin')

@section('title', 'Tambahkan Siswa Baru')
@section('content')

<p class="h1">
    <i class="fa fa-plus"></i>
    Sunting Tahun Ajaran
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
            <a href="{{ route('terms.index') }}" class="btn btn-sm btn-secondary">
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

<hr>

<form method="POST" action="{{ route('terms.edit', $term) }}" style="max-width: 400px">
    @csrf
    <div class="form-group">
        <label for="term_start"> Tahun Mulai </label>
        {{--  Default value = This year  --}}
        <input value="{{ old('term_start', $term->term_start) }}" id="term_start" name="term_start" type="number" class="form-control
        @if($errors->has('term_start') || $errors->first('code')) is-invalid @endif">
        <div class="invalid-feedback">
            {{ $errors->first('term_start') }}
        </div>
    </div>

    <div class="form-group">
        <label for="term_end"> Tahun Selesai </label>
        {{--  Default value = Next year  --}}
        <input value="{{ old('term_end', $term->term_end) }}" id="term_end" name="term_end" type="number" class="form-control 
        @if($errors->has('term_end') || $errors->first('code')) is-invalid @endif">
        <div class="invalid-feedback">
            {{ $errors->first('term_end') }}
        </div>
    </div>

    @if($errors->has('code'))
        <div class="alert alert-danger">
            {{ $errors->first('code') }}
        </div>
    @endif

    <div style="height: 15px"> </div>

    <div class="form-group text-right">
        <button class="btn btn-primary">
            <i class="fa fa-check"></i>
            Perbarui
        </button>
    </div>
</form>
@endsection