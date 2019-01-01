@extends('layouts.admin')
@section('title', "Siswa-Siswa dengan Nilai Terbaik")
@section('content')

<h1>
    <i class="fa fa-list"></i>
    Siswa-Siswa dengan Nilai Terbaik
</h1>
<p class="lead"> Tahun Ajaran {{ $term->code }} Semester {{ \App\RoomTerm::EVEN_ODD[$even_odd] }} </p>

@if( session('message-success') )
    <div class="alert alert-success">
        {{ session('message-success') }}
    </div>
@endif

<hr>

<div class="container">
    <div class="row">
        <div class="col-md-3">
            <a href="{{ route('headmaster_access.terms') }}" class="btn btn-secondary btn-sm">
                <i class="fa fa-arrow-left"></i>
                Kembali
            </a>
        </div>
        <div class="col"></div>
        <div class="col-md-3 text-right">
        </div>
    </div>
</div>

<hr>

<table class='table table-striped table-responsive-xl table-sm'>
        <thead class='thead-dark'>
            <tr>
                <th> # </th>
                <th> Siswa </th>
                <th> Nilai Pengetahuan </th>
                <th> Nilai Keterampilan </th>
                <th> Rata-Rata </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($best_grades as $best_grade)
            <tr>
                <td> {{ $loop->iteration }}. </td>
                <td> {{ $reports[$best_grade['id']]->student_name }} </td>
                <td> {{ number_format($knowledge_grades[$best_grade['id']] ?? 0, 2, ',', '') }} </td>
                <td> {{ number_format($skill_grades[$best_grade['id']] ?? 0, 2, ',', '') }} </td>
                <td> {{ number_format($best_grade['grade'] ?? 0, 2, ',', '') }} </td>
            </tr>
            @endforeach
        </tbody>
    </table>

@endsection

@section('script')
@endsection