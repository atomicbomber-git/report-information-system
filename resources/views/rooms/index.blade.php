@extends('layouts.admin')

@section('title', 'Seluruh Siswa')

@section('content')

<p class="h1">
    <i class="fa fa-list"></i>
    Daftar Seluruh Ruangan
</p>

<hr>

@if( session('message-success') )
    <div class="alert alert-success">
        {{ session('message-success') }}
    </div>
@endif

<div style="width: 100%; padding: 1.4rem; text-align: right">
    <a 
        class="btn btn-primary btn-sm"
        href="{{ route('rooms.create') }}"
        >
        Tambah Ruangan Baru
        <i class="fa fa-plus"></i>
    </a>
</div>
    

<table class='table table-sm table-striped'>
    <thead class='thead-dark'>
        <tr>
            <th> # </th>
            <th> Nama </th>
            <th> Kendali </th>
        </tr>
    </thead>

    <tbody>
        @foreach ($rooms as $room)
        <tr>
            <td> {{ $loop->iteration }}. </td>
            <td> {{ $room->name }} </td>
            <td>
                <a href="{{ route('rooms.edit', $room) }}" class="btn btn-dark btn-sm">
                    <i class="fa fa-pencil"></i>
                </a>
                <a href="{{ route('rooms.delete', $room) }}" class="btn btn-danger btn-sm">
                    <i class="fa fa-trash"></i>
                </a>
            </td>
        @endforeach
    </tbody>
</table>
@endsection