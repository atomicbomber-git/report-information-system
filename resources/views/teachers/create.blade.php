@extends('layouts.admin')

@section('title', 'Tambahkan Guru')

@section('styles')

@endsection

@section('content')

<h1>
    <i class="fa fa-plus"></i>
    Tambahkan Guru
</h1>

<div style="max-width: 20rem">
    <div class="form-group">
        <label for="name"> Nama: </label>
        <input name="name" type="text" class="form-control">
    </div>

    <div class="form-group">
        <label for="teacher_id"> NIK: </label>
        <input name="teacher_id" type="text" class="form-control">
    </div>

    <div class="form-group">
        <label for="username"> Nama Pengguna: </label>
        <input name="username" type="text" class="form-control">
    </div>

    <div class="form-group">
        <label for="password"> Kata Sandi: </label>
        <input name="password" type="password" class="form-control">
    </div>

    <div class="form-group text-right">
        <button class="btn btn-primary btn-sm">
            Tambahkan
            <i class="fa fa-check"></i>
        </button>
    </div>
</div>

@endsection

@section('script')
@endsection