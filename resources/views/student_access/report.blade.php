@extends('layouts.admin')

@section('title', "Nilai Siswa")

@section('content')

<h1>
    <i class="fa fa-list"></i>
    Nilai Siswa
</h1>

@if( session('message-success') )
    <div class="alert alert-success">
        {{ session('message-success') }}
    </div>
@endif

<hr>

<div class="container">
    <div class="row">
        <div class="col text-left">
            <a href="{{ route('student_access.terms') }}" class="btn btn-secondary btn-sm">
                <i class="fa fa-arrow-left"></i>
                Kembali
            </a>
        </div>
        <div class="col-3">
        </div>
        <div class="col text-right">
        </div>
    </div>
</div>

<hr>

@foreach ($course_groups as $group_code => $courses)
<h2> Kelompok {{ $group_code }} </h2>
    <table class="table table-sm table-striped">
        <thead class="thead-dark">
            <tr>
                <th> # </th>
                <th> Mata Pelajaran </th>
                <th> Nilai Pengetahuan </th>
                <th> Deskripsi </th>
                <th> Nilai Keterampilan </th>
                <th> Deskripsi </th>
            </tr>
        </thead>

        <tbody>
            @foreach ($courses as $course)
            <tr>
                <td> {{ $loop->iteration }} </td>
                <td> {{ $course->name }} </td>
                <td> {{ blank($course->knowledge_grade) ? '-' : number_format($course->knowledge_grade, 0) }} </td>
                <td> {{ $course->descriptions->knowledge_description ?? '-' }} </td>
                <td> {{ blank($course->skill_grade) ? '-' : number_format($course->skill_grade, 0) }} </td>
                <td> {{ $course->descriptions->skill_description ?? '-' }} </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endforeach

<hr>

<h2> Ekstrakurikuler </h2>

<table class="table table-sm table-striped">
    <thead class="thead thead-dark">
        <tr>
            <th> No </th>
            <th> Kegiatan Ekstrakurikuler </th>
            <th> Keterangan </th>
        </tr>
    </thead>

    <tbody>
        @foreach ($extracurriculars as $extracurricular)
        <tr>
            <td> {{ $loop->iteration }} </td>
            <td> {{ $extracurricular->name }} </td>
            <td> {{ \App\ExtracurricularReport::GRADES[$extracurricular->score] ?? '-' }} </td>
        </tr>
        @endforeach
    </tbody>
</table>

<h2> Ketidakhadiran </h2>

<table class="table table-sm table-striped table-bordered">
    <thead>
        <tr> <td> Sakit </td> <td> {{ $report->absence_sick }} hari </td> </tr>
        <tr> <td> Izin </td> <td> {{ $report->absence_permit }} hari </td> </tr>
        <tr> <td> Tanpa Keterangan </td> <td> {{ $report->absence_unknown }} hari </td> </tr>
    </thead>
</table>

@endsection