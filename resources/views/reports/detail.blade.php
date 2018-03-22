@extends('layouts.admin')

@section('title', 'Detail Kelas')
@section('content')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap4.min.css') }}">
@endsection

<p class="h1">
    <i class="fa fa-plus"></i>
    Detail Nilai Siswa
</p>

<hr>

<div class="container" style="padding: 0.6rem 0rem 0.6rem 0rem">
    <div class="row">
        <div class="col col-md-3 text-left">
            <a href="{{ route('room_terms.detail', $report->room_term_id) }}" class="btn btn-sm btn-secondary">
                <i class="fa fa-arrow-left"></i>
                Kembali
            </a>
        </div>
        <div class="col-md-6">
        </div>
        <div class="col col-md-3 text-right">
        </div>
    </div>
</div>

<hr>

<h3> Informasi </h3>
<dd>
    <dt> Nama Siswa: </dt> <dl> {{ $information->student_name }} </dl>
    <dt> NISN: </dt> <dl> {{ $information->student_id }} </dl>
    <dt> Semester: </dt> <dl> {{ $information->semester }} </dl>
    <dt> Tahun Ajaran: </dt> <dl> {{ $information->term_code }} </dl>
</dd>

<hr>

@foreach($course_reports as $key => $group)

<h3> Mata Pelajaran Kelompok {{ $key }} </h3>

<table style="border-collapse: collapse !important" class="table table-sm table-striped">
    <thead class="thead-dark">
        <tr>
            <th> Mata Pelajaran </th>
            <th class="text-right"> UTS </th>
            <th class="text-right"> UAS </th>
            <th class="text-right"> Pengetahuan </th>
            <th class="text-right"> Keterampilan  </th>
            <th> Detail Nilai </th>
        </tr>
    </thead>

    <tbody>
    @foreach($group as $course)
    <tr>
        <td> {{ $course->name }} </td>
        <td class="text-right"> {{ $course->mid_exam or '-' }} </td>
        <td class="text-right"> {{ $course->final_exam or '-' }} </td>
        <td class="text-right"> {{ $course->knowledge_grade or '-' }} </td>
        <td class="text-right"> {{ $course->skill_grade or '-' }} </td>
        <td>
            <a href="{{ route('course_reports.detail', $course->id) }}" class="btn btn-sm btn-dark">
                <i class="fa fa-list"> Pengetahuan </i>
            </a>
            <a href="" class="btn btn-sm btn-dark">
                <i class="fa fa-list"> Keterampilan </i>
            </a>
        </td>
        
    </tr>
    @endforeach
    </tbody>
</table>

@endforeach

@if( session('message-success') )
    <div class="message alert alert-success">
        {{ session('message-success') }}
    </div>

    <script>
        window.setTimeout(function() {
            $('.message').fadeOut();
        }, 3000);
    </script>
@endif

@endsection

@section('script')
<script src="{{ asset('js/sweetalert.min.js') }}"> </script>
<script src="{{ asset('js/jquery.dataTables.js') }}"> </script>
<script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"> </script>

<script>
    $(document).ready(function() {
    });
</script>
@endsection