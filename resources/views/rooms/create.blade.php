@extends('layouts.admin')

@section('title', 'Tambahkan Ruangan Baru')
@section('content')

<p class="h1">
    <i class="fa fa-plus"></i>
    Tambahkan Ruangan Baru
</p>

<hr>

<div class="container" style="padding: 0.6rem 0rem 0.6rem 0rem">
    <div class="row">
        <div class="col col-md-3 text-left">
            <a href="{{ route('rooms.index') }}" class="btn btn-sm btn-secondary">
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

<form method="POST" action="{{ route('rooms.create') }}" style="max-width: 400px">
    @csrf

    <div class='form-group'>
        <label for='name'> Nama Ruangan: </label>
    
        <input
            id='name' name='name' type='text'
            value='{{ old('name') }}'
            class='form-control {{ !$errors->has('name') ?: 'is-invalid' }}'>
    
        <div class='invalid-feedback'>
            {{ $errors->first('name') }}
        </div>
    </div>

    <div class='form-group'>
        <label for='grade'> Jenjang: </label>
    
        <input
            id='grade' name='grade' type='number'
            value='{{ old('grade') }}'
            class='form-control {{ !$errors->has('grade') ?: 'is-invalid' }}'>
    
        <div class='invalid-feedback'>
            {{ $errors->first('grade') }}
        </div>
    </div>

    <div style="height: 15px"> </div>

    <div class="form-group text-right">
        <button class="btn btn-primary">
            <i class="fa fa-plus"></i>
            Tambahkan
        </button>
    </div>
</form>
@endsection