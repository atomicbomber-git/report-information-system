@extends('layouts.admin')

@section('title', "Sunting Data Guru \"" . $teacher->user->name . "\" ($teacher->teacher_id)")

@section('styles')

@endsection

@section('content')

<h1>
    <i class="fa fa-pencil"></i>
    Sunting Data Guru
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
        action="{{ route('teachers.edit', $teacher->id) }}">

        @csrf

        <div class="form-group">
            <label for="name"> Nama: </label>
            <input name="name" value="{{ old('name', $teacher->user->name) }}" type="text" class="form-control {{ !$errors->has('name') ?: 'is-invalid' }}">
            <div class="invalid-feedback">
                {{ $errors->first('name') }}
            </div>
        </div>

        <div class="form-group">
            <label for="teacher_id"> NIK: </label>
            <input name="teacher_id" value="{{ old('teacher_id', $teacher->teacher_id) }}" type="text" class="form-control {{ !$errors->has('teacher_id') ?: 'is-invalid' }}">
            <div class="invalid-feedback">
                {{ $errors->first('teacher_id') }}
            </div>
        </div>

        <div class="form-group">
            <label for="username"> Nama Pengguna: </label>
            <input name="username" value="{{ old('username', $teacher->user->username) }}" type="text" class="form-control {{ !$errors->has('username') ?: 'is-invalid' }}">
            <div class="invalid-feedback">
                {{ $errors->first('username') }}
            </div>
        </div>

        <div class="alert alert-warning">
            <i class="fa fa-info"></i>
            Kosongkan kolom dibawah jika tidak ingin mengubah kata sandi
        </div>

        <div class="form-group">
            <label for="password"> Kata Sandi Baru: </label>
            <input name="password" type="password" class="form-control {{ !$errors->has('password') ?: 'is-invalid' }}">
            <div class="invalid-feedback">
                {{ $errors->first('password') }}
            </div>
        </div>

        <div class="form-group">
            <label for="password_confirmation"> Ulangi Kata Sandi: </label>
            <input name="password_confirmation" type="password" class="form-control {{ !$errors->has('password_confirmation') ?: 'is-invalid' }}">
            <div class="invalid-feedback">
                {{ $errors->first('password_confirmation') }}
            </div>
        </div>

        <div class="form-group text-right">
            <button class="btn btn-primary btn-sm">
                Perbarui
                <i class="fa fa-check"></i>
            </button>
        </div>

    </form>
</div>

@endsection

@section('script')
@endsection