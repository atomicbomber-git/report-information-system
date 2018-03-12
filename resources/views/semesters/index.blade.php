@extends('layouts.admin')

@section('title', 'Seluruh Semester')

@section('content')

<p class="h1">
    <i class="fa fa-list"></i>
    Daftar Seluruh Semester
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
        href="{{ route('semesters.create') }}"
        >
        Tambah Semester Baru
        <i class="fa fa-plus"></i>
    </a>
</div>
    

<table class='table'>
    <thead class='thead-dark'>
        <tr>
            <th> # </th>
            <th> Tahun Mulai </th>
            <th> Tahun Selesai </th>
        </tr>
    </thead>

    <tbody>
        @foreach ($semesters as $semester)
        <tr>
            <td> {{ $loop->iteration }}. </td>
            <td> {{ $semester->name }} </td>
            <td>
                <a href="{{ route('semesters.edit', $semester) }}" class="btn btn-dark btn-sm">
                    <i class="fa fa-pencil"></i>
                </a>
                <a href="{{ route('semesters.delete', $semester) }}" class="btn btn-danger btn-sm">
                    <i class="fa fa-trash"></i>
                </a>
            </td>
        @endforeach
    </tbody>
</table>
@endsection