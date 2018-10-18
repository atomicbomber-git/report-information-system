@extends('layouts.admin')

@section('title', "Kelola Nilai Tahun Ajaran $term->code Semester" . \App\RoomTerm::EVEN_ODD[$even_odd])

@section('content')

<h1>
    <i class="fa fa-list-alt"></i>
    Kelola Nilai Mata Pelajaran dan Kelas Perwalian
</h1>

<p class="lead">
    Tahun Ajaran {{ $term->code }} Semester {{ \App\RoomTerm::EVEN_ODD[$even_odd] }}
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
            <th> Jumlah Siswa </th>
            <th> Kendali</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($managed_room_terms as $room_term)
        <tr>
            <td> {{ $loop->iteration }}. </td>
            <td> {{ $room_term->name }} </td>
            <td> {{ $room_term->report_count }} </td>
            <td>
                <a href="{{ route('teacher.management.presence.edit', $room_term->id)  }}" class="btn btn-dark btn-sm">
                    Kehadiran Siswa
                    <i class="fa fa-list-alt"></i>
                </a>

                <a href="{{ route('teacher.management.room_term', $room_term->id) }}" class="btn btn-dark btn-sm">
                    Cetak Laporan
                    <i class="fa fa-print"></i>
                </a>

                <a href="{{ route('teacher.management.grades', $room_term->id) }}" class="btn btn-dark btn-sm">
                    Nilai & Grafik
                    <i class="fa fa-bar-chart"></i>
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<h3 class="mt-5">
    <i class="fa fa-list"></i>
    Daftar Seluruh Ekstrakurikuler yang Dibimbing
</h3>

<table class="table table-striped table-sm">
    <thead class="thead-dark">
        <tr>
            <th> # </th>
            <th> Nama Ekstrakurikuler </th>
            <th> Jumlah Peserta </th>
            <th> Kendali </th>
        </tr>
    </thead>

    <tbody>
        @foreach ($managed_extracurriculars as $extracurricular)
        <tr>
            <td> {{ $loop->iteration }}. </td>
            <td> {{ $extracurricular->name }} </td>
            <td> {{ $extracurricular->member_count }} </td>
            <td>
                <a href="{{ route('teacher.management.extracurricular_edit_score', [$even_odd, $extracurricular->id]) }}" class="btn btn-dark btn-sm">
                    Nilai
                    <i class="fa fa-list-alt"></i>
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<h3 class="mt-5">
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
            <h4> Kelas {{ $grade }} </h4>
            @foreach ($room_terms as $room_term)
                <tr>
                    <td> {{ $loop->iteration }}. </td>
                    <td> {{ $room_term->course_name }} </td>
                    <td> {{ $room_term->room_name }} </td>
                    <td> {{ $room_term->report_count }} </td>
                    <td>
                        <a
                            href="{{ route('teacher.management.courses.detail', [$term->id, $even_odd, $room_term->id, $room_term->course_id]) }}"
                            class="btn btn-sm btn-dark">
                            Pengetahuan
                            <i class="fa fa-list-alt"></i>
                        </a>

                        <a href="{{ route('teacher.management.courses.skill_detail', [$term->id, $even_odd, $room_term->id, $room_term->course_id]) }}"
                            class="btn btn-sm btn-dark">
                            Keterampilan
                            <i class="fa fa-list-alt"></i>
                        </a>

                        @if($room_term->course_type == 'spiritual')
                        <a href="{{ route('teacher.management.spiritual_description', $room_term->id) }}" class="btn btn-sm btn-dark">
                            Spiritual
                            <i class="fa fa-list-alt"></i>
                        </a>
                        @elseif($room_term->course_type == 'social')
                        <a href="{{ route('teacher.management.social_description', $room_term->id) }}" class="btn btn-sm btn-dark">
                            Sosial
                            <i class="fa fa-list-alt"></i>
                        </a>
                        @endif

                    </td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
@endsection