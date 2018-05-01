@extends('layouts.admin')

@section('title', 'Kelola Guru Mata Pelajaran')

@section('content')

<p class="h1">
    <i class="fa fa-list"></i>
    Kelola Guru Mata Pelajaran
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
            <td> {{ \App\RoomTerm::EVEN_ODD[$term->even_odd] }} </td>
            <td>
                @foreach($grades as $grade)
                <a href="{{ route('course_teachers.grade_index', [$term->id, $term->even_odd, $grade]) }}" class="btn btn-dark btn-sm">
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