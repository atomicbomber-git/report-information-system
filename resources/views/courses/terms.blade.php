@extends('layouts.admin')

@section('title', 'Kelola Mata Pelajaran')

@section('content')

<p class="h1">
    <i class="fa fa-list"></i>
    Kelola Mata Pelajaran
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
            <th> Kendali </th>
        </tr>
    </thead>

    <tbody>
    @foreach($terms as $term)
        <tr>
            <td> {{ $loop->iteration }}. </td>
            <td> {{ $term->code }} </td>
            <td>
                @foreach($grades as $grade)
                <a href="{{ route('courses.grade_index', [$term->id, $grade]) }}" class="btn btn-dark btn-sm">
                    Kelas {{ $grade }}
                    <i class="fa fa-list-alt"></i>
                </a>
                @endforeach
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection