@extends('layouts.admin')
@section('title', "Seluruh Tahun Ajaran")
@section('content')

<h1>
    <i class="fa fa-list"></i>
    Daftar Seluruh Tahun Ajaran
</h1>

@if( session('message-success') )
    <div class="alert alert-success">
        {{ session('message-success') }}
    </div>
@endif

<hr>

<div class="text-right my-3">
    <a href="{{ route('headmaster_access.students') }}" class="btn btn-dark">
        Data Siswa <i class="fa fa-users"></i>
    </a>
</div>

<table class='table table-striped table-responsive-xl table-sm'>
    <thead class='thead-dark'>
        <tr>
            <th> # </th>
            <th> Tahun Ajaran </th>
            <th> Nilai Siswa </th>
            <th> Data Guru </th>
            <th> Jumlah Siswa </th>
            <th> Jumlah Guru </th>
        </tr>
    </thead>

    <tbody>
        @foreach ($terms as $term)
        <tr>
            <td> {{ $loop->iteration }}. </td>
            <td> {{ $term->code }} </td>
            <td>
                @foreach (\App\RoomTerm::EVEN_ODD as $even_odd => $semester)
                <a href="{{ route('headmaster_access.room_terms', [$term->id, $even_odd]) }}" class="btn btn-dark btn-sm">
                    Semester {{ $semester }}
                    <i class="fa fa-list"></i>
                </a>
                @endforeach
            </td>
            <td>
                @foreach (\App\RoomTerm::EVEN_ODD as $even_odd => $semester)
                <a href="{{ route('headmaster_access.teachers', [$term->id, $even_odd]) }}" class="btn btn-dark btn-sm">
                    Semester {{ $semester }}
                    <i class="fa fa-list"></i>
                </a>
                @endforeach
            </td>
            <td> {{ ($male_student_count[$term->id] ?? 0) + ($female_student_count[$term->id] ?? 0) }} ({{ $male_student_count[$term->id] ?? 0 }} Laki-Laki, {{ $female_student_count[$term->id] ?? 0 }} Perempuan) </td>
            <td> {{ $teacher_count[$term->id] ?? 0 }} </td>
        @endforeach
    </tbody>
</table>
@endsection