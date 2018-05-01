@extends('layouts.admin')

@section('title', 'Kelola Guru')

@section('content')

<p class="h1">
    <i class="fa fa-list"></i>
    Kelola Guru
</p>

<hr>

@if( session('message-success') )
    <div class="alert alert-success">
        {{ session('message-success') }}
    </div>
@endif

<table class='table table-striped table-responsive-xl table-sm'>
    <thead class='thead-dark'>
        <tr>
            <th> # </th>
            <th> Nama </th>
            <th> Nama Pengguna </th>
            <th> NIK </th>
        </tr>
    </thead>

    <tbody>
        @foreach($teachers as $teacher)
        <tr>
            <td> {{ $loop->iteration }} </td>
            <td> {{ $teacher->name }} </td>
            <td> {{ $teacher->username }} </td>
            <td> {{ $teacher->teacher_id }} </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection