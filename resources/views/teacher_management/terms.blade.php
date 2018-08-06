@extends('layouts.admin')

@section('title', 'Seluruh Tahun Ajaran')

@section('content')

<p class="h1">
    <i class="fa fa-list"></i>
    Kelola Nilai Siswa
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
            <th> Tahun Ajaran </th>
            <th> Semester </th>
            <th> Kendali </th>
        </tr>
    </thead>

    <tbody>
    @foreach($terms as $term)
        <tr>
            <td> {{ $loop->iteration }}. </td>
            <td> {{ $term->code }} </td>
            <td> {{ $term->semester }} </td>
            <td>
                <a
                    href="{{ route('teacher.management.courses', ['term_id' => $term->id, 'even_odd' => $term->even_odd]) }}"
                    class="btn btn-sm btn-dark">
                    Kendali
                    <i class="fa fa-list-alt"></i>
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection