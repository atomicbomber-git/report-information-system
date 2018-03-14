@extends('layouts.admin')

@section('title', 'Tambahkan Siswa Baru')
@section('content')

<p class="h1">
    <i class="fa fa-plus"></i>
    Tambahkan Ruangan Baru
</p>

<hr>

<form method="POST" action="{{ route('terms.create') }}" style="max-width: 400px">
    @csrf
    <div class="form-group">
        <label for="term_start"> Tahun Mulai </label>
        {{--  Default value = This year  --}}
        <input value="{{ old('term_start', today()->format('Y')) }}" id="term_start" name="term_start" type="number" class="form-control
        @if($errors->has('term_start') || $errors->first('code')) is-invalid @endif">
        <div class="invalid-feedback">
            {{ $errors->first('term_start') }}
        </div>
    </div>

    <div class="form-group">
        <label for="term_end"> Tahun Selesai </label>
        {{--  Default value = Next year  --}}
        <input value="{{ old('term_end', today()->addYear()->format('Y')) }}" id="term_end" name="term_end" type="number" class="form-control 
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
            <i class="fa fa-plus"></i>
            Tambahkan
        </button>
    </div>
</form>
@endsection