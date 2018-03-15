@extends('layouts.admin')

@section('title', 'Seluruh Tahun Ajaran')

@section('content')

<p class="h1">
    <i class="fa fa-list"></i>
    Daftar Seluruh Tahun Ajaran
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
        Tambah Tahun Ajaran Baru
        <i class="fa fa-plus"></i>
    </a>
</div>
    

<table class='table table-striped table-responsive-xl table-sm'>
    <thead class='thead-dark'>
        <tr>
            <th> # </th>
            <th> Tahun Mulai </th>
            <th> Tahun Selesai </th>
            <th> Kendali </th>
        </tr>
    </thead>

    <tbody>
        @foreach ($terms as $term)
        <tr>
            <td> {{ $loop->iteration }}. </td>
            <td> {{ $term->term_start }} </td>
            <td> {{ $term->term_end }} </td>
            <td>
                <a href="{{ route('terms.detail', $term) }}" class="btn btn-dark btn-sm">
                    <i class="fa fa-list-alt"></i>
                    Detail
                </a>
                <a href="{{ route('terms.edit', $term) }}" class="btn btn-dark btn-sm">
                    <i class="fa fa-pencil"></i>
                </a>
                <a href="{{ route('terms.delete', $term) }}" class="btn btn-danger btn-sm">
                    <i class="fa fa-trash"></i>
                </a>
            </td>
        @endforeach
    </tbody>
</table>
@endsection