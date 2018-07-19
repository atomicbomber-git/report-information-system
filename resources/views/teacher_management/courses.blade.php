@extends('layouts.admin')

@section('title', 'Seluruh Tahun Ajaran')

@section('content')

<h1>
    <i class="fa fa-list-alt"></i>
    Kelola Nilai Mata Pelajaran dan Kelas Perwalian
</h1>

<p class="lead">
    Tahun Ajaran {{ $information->term_code }} Semester {{ $information->semester }}
</p>

<hr/>

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

<hr/>

<h3>
    <i class="fa fa-list"></i>
    Kelola Kelas Perwalian
</h3>

<table class="table table-striped table-responsive-xl table-sm">
    <thead class="thead-dark">
        <tr>
            <th> # </th>
            <th> Kelas </th>
            <th> Kendali</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($managed_room_terms as $room_term)
        <tr>
            <td> {{ $loop->iteration }}. </td>
            <td> {{ $room_term->name }} </td>
            <td>
                <a href="{{ route('teacher.management.room_term', $room_term->id) }}" class="btn btn-dark btn-sm">
                    Detail
                    <i class="fa fa-list-alt"></i>
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div style="height: 4rem">

</div>

<h3>
    <i class="fa fa-list"></i>
    Daftar Seluruh Kelas yang Diajar
</h3>

<table class='table table-striped table-responsive-xl table-sm'>
    <thead class='thead-dark'>
        <tr>
            <th> # </th>
            <th> Mata Pelajaran </th>
            <th> Kelas </th>
            <th> Jumlah Siswa </th>
            <th> Nilai </th>
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
                            Pengetahuan
                            <i class="fa fa-list-alt"></i>
                        </a>

                        <a href="{{ route('teacher.management.courses.skill_detail', [$information->id, $information->even_odd, $room_term->id, $room_term->course_id]) }}"
                            class="btn btn-sm btn-dark">
                            Keterampilan
                            <i class="fa fa-list-alt"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
@endsection