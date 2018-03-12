@extends('layouts.admin')

@section('title', 'Tambahkan Siswa Baru')
@section('content')

<p class="h1">
    <i class="fa fa-plus"></i>
    Sunting Data Ruangan
</p>

<hr>

<form method="POST" action="{{ route('rooms.edit', $room) }}" style="max-width: 400px">
    @csrf
    <div class="form-group">
        <label for="name"> Nama Ruangan </label>
        <input id="name" name="name" value="{{ old('name', $room->name) }}" type="text" class="form-control">
    </div>

    <div style="height: 15px"> </div>

    <div class="form-group text-right">
        <button class="btn btn-primary">
            <i class="fa fa-pencil"></i>
            Ubah
        </button>
    </div>
</form>
@endsection