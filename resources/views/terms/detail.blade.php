@extends('layouts.admin')

@section('title', 'Seluruh Tahun Ajaran')

@section('content')

<p class="h1">
    <i class="fa fa-list"></i>
    Daftar Kelas Pada Tahun Ajaran {{ $term->term_start }} - {{ $term->term_end }}
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
        href="{{ route('terms.create') }}"
        >
        Tambah Kelas
        <i class="fa fa-plus"></i>
    </a>
</div>
    

<table class='table table-sm'>
    <thead class='thead-dark'>
        <tr>
            <th> # </th>
            <th> Kelas </th>
            <th> Semester </th>
            <th> Wali Kelas </th>
            <th> Kendali </th>
        </tr>
    </thead>

    <tbody>
        @foreach($term->rooms as $room)
        <tr>
            <td> {{ $loop->iteration }}. </td>
            <td> {{ $room->name }}</td>
            <td> {{ $room->even_odd }} </td>
            <td> {{ $room->room_term->teacher->user->name }} </td>
            <td>
                <a href="" class="btn btn-dark btn-sm">
                    Detail
                    <i class="fa fa-list-alt"></i>
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection