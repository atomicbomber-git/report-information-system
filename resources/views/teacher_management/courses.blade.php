@extends('layouts.admin')

@section('title', 'Seluruh Tahun Ajaran')

@section('content')

<p class="h1">
    <i class="fa fa-list"></i>
    Daftar Seluruh Kelas yang Diajar
</p>

<p class="lead">
    Tahun Ajaran {{ $information->term_code }} Semester {{ $information->semester }}
</p>

<hr>

@if( session('message-success') )
    <div class="alert alert-success">
        {{ session('message-success') }}
    </div>
@endif

<div class="container" style="padding: 0.6rem 0rem 0.6rem 0rem">
    <div class="row">
        <div class="col col-md-3 text-left">
            <a href="{{ route('teacher.management.terms') }}" class="btn btn-sm btn-secondary">
                <i class="fa fa-arrow-left"></i>
                Kembali
            </a>
        </div>
        <div class="col-md-6">
        </div>
        <div class="col col-md-3">
        </div>
    </div>
</div>

<hr>

<table class='table table-striped table-responsive-xl table-sm'>
    <thead class='thead-dark'>
        <tr>
            <th> # </th>
            <th> Mata Pelajaran </th>
            <th> Kelas </th>
            <th> Jumlah Siswa </th>
            <th> Kendali </th>
        </tr>
    </thead>

    <tbody>
        @foreach ($room_term_groups as $grade => $room_terms)
            <h3> Kelas {{ $grade }} </h3>
            @foreach ($room_terms as $room_term)
                <tr>
                    <td> {{ $loop->iteration }}. </td>
                    <td> {{ $room_term->course_name }} </td>
                    <td> {{ $room_term->room_name }} </td>
                    <td> {{ $room_term->report_count }} </td>
                    <td>
                        <a
                            href="{{ route('teacher.management.courses.detail', [$information->id, $information->even_odd, $room_term->id, $room_term->course_id]) }}"
                            class="btn btn-sm btn-dark">
                            Nilai
                            <i class="fa fa-list-alt"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
@endsection