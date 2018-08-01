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

    </form>
</div>

@endsection

@section('script')
@endsection