@extends('layouts.admin')

@section('title', 'Tambahkan Guru')

@section('styles')

@endsection

@section('content')

<h1>
    <i class="fa fa-plus"></i>
    Tambahkan Guru
</h1>

<hr/>

<div class="container" style="padding: 0.6rem 0rem 0.6rem 0rem">
    <div class="row">
        <div class="col col-md-3 text-left">
            <a href="{{ route('teachers.index') }}" class="btn btn-sm btn-secondary">
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

<hr/>

<div style="max-width: 20rem">
    <form
        method="POST"
        action="{{ route('teachers.create') }}">

        @csrf

        <div class='form-group'>
            <label for='name'> Nama: </label>
        
            <input
                id='name' name='name' type='text'
                value='{{ old('name') }}'
                class='form-control {{ !$errors->has('name') ?: 'is-invalid' }}'>
        
            <div class='invalid-feedback'>
                {{ $errors->first('name') }}
            </div>
        </div>

        <div class='form-group'>
            <label for='teacher_id'> NIK: </label>
        
            <input
                id='teacher_id' name='teacher_id' type='text'
                value='{{ old('teacher_id') }}'
                class='form-control {{ !$errors->has('teacher_id') ?: 'is-invalid' }}'>
        
            <div class='invalid-feedback'>
                {{ $errors->first('teacher_id') }}
            </div>
        </div>

        <div class='form-group'>
            <label for='username'> Nama Pengguna: </label>
        
            <input
                id='username' name='username' type='text'
                value='{{ old('username') }}'
                class='form-control {{ !$errors->has('username') ?: 'is-invalid' }}'>
        
            <div class='invalid-feedback'>
                {{ $errors->first('username') }}
            </div>
        </div>

        <div class='form-group'>
            <label for='password'> Kata Sandi: </label>
        
            <input
                id='password' name='password' type='password'
                value='{{ old('password') }}'
                class='form-control {{ !$errors->has('password') ?: 'is-invalid' }}'>
        
            <div class='invalid-feedback'>
                {{ $errors->first('password') }}
            </div>
        </div>

        <div class='form-group'>
            <label for='password_confirmation'> Ulangi Kata Sandi: </label>
        
            <input
                id='password_confirmation' name='password_confirmation' type='password'
                value='{{ old('password_confirmation') }}'
                class='form-control {{ !$errors->has('password_confirmation') ?: 'is-invalid' }}'>
        
            <div class='invalid-feedback'>
                {{ $errors->first('password_confirmation') }}
            </div>
        </div>

        <div class="form-group text-right">
            <button class="btn btn-primary btn-sm">
                Tambahkan
                <i class="fa fa-plus"></i>
            </button>
        </div>

    </form>
</div>

@endsection

@section('script')
@endsection