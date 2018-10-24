@extends('layouts.admin')

@section('title', 'Sunting Data Kepala Sekolah')
@section('content')

<p class="h1">
    <i class="fa fa-plus"></i>
    Sunting Data Kepala Sekolah
</p>

<hr>

@if( session('message-success') )
    <div class="alert alert-success">
        {{ session('message-success') }}
    </div>
@endif

<form action="{{ route('headmaster.update') }}" method="POST">
    @csrf

    <div class='form-group'>
        <label for='name'> Nama: </label>
    
        <input
            id='name' name='name' type='text'
            value='{{ old('name', $headmaster->name) }}'
            class='form-control {{ !$errors->has('name') ?: 'is-invalid' }}'>
    
        <div class='invalid-feedback'>
            {{ $errors->first('name') }}
        </div>
    </div>

    <div class='form-group'>
        <label for='teacher_id'> NIK: </label>
    
        <input
            id='teacher_id' name='teacher_id' type='text'
            value='{{ old('teacher_id', $headmaster->teacher->teacher_id) }}'
            class='form-control {{ !$errors->has('teacher_id') ?: 'is-invalid' }}'>
    
        <div class='invalid-feedback'>
            {{ $errors->first('teacher_id') }}
        </div>
    </div>

    <div class='form-group'>
        <label for='username'> Username: </label>
    
        <input
            id='username' name='username' type='text'
            value='{{ old('username', $headmaster->username) }}'
            class='form-control {{ !$errors->has('username') ?: 'is-invalid' }}'>
    
        <div class='invalid-feedback'>
            {{ $errors->first('username') }}
        </div>
    </div>

    <div class='form-group'>
        <label for='password'> Kata Sandi: </label>
    
        <input
            id='password' name='password' type='password'
            class='form-control {{ !$errors->has('password') ?: 'is-invalid' }}'>
    
        <div class='invalid-feedback'>
            {{ $errors->first('password') }}
        </div>
    </div>

    <div class='form-group'>
        <label for='password_confirmation'> Ulangi Kata Sandi: </label>
    
        <input
            id='password_confirmation' name='password_confirmation' type='password'
            class='form-control {{ !$errors->has('password_confirmation') ?: 'is-invalid' }}'>
    
        <div class='invalid-feedback'>
            {{ $errors->first('password_confirmation') }}
        </div>
    </div>

    <div class="form-group text-right">
        <button class="btn btn-primary">
            Perbarui
            <i class="fa fa-check"></i>
        </button>
    </div>
</form>
@endsection