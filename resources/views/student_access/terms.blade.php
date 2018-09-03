@extends('layouts.admin')

@section('title', "Seluruh Tahun Ajaran")

@section('content')

<h1>
    <i class="fa fa-list"></i>
    Daftar Seluruh Tahun Ajaran yang Pernah Diikuti
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
        </div>
        <div class="col-3">
        </div>
        <div class="col text-right">
        </div>
    </div>
</div>

<hr>

<table class='table table-striped table-responsive-xl table-sm'>
    <thead class='thead-dark'>
        <tr>
            <th> # </th>
            <th> Tahun Ajaran </th>
            <th> Semester Ganjil </th>
            <th> Semeter Genap </th>
        </tr>
    </thead>

    <tbody>
        @foreach ($terms as $code => $semesters)
        <tr>
            <td> {{ $loop->iteration }}. </td>
            <td> {{ $code }} </td>
            <td>
                @if(isset($semesters['odd']))
                    <a href="{{ route('teacher.management.print_report_cover', $semesters['odd']->report_id) }}" class="btn btn-sm btn-dark">
                        Cetak Cover Rapor
                        <i class="fa fa-print"></i>
                    </a>
                    <a href="{{ route('teacher.management.print_report', $semesters['odd']->report_id) }}" class="btn btn-sm btn-dark">
                        Cetak Isi Rapor
                        <i class="fa fa-print"></i>
                    </a>
                @endif
            </td>
            <td>
                @if(isset($semesters['even']))
                <a href="{{ route('teacher.management.print_report_cover', $semesters['even']->report_id) }}" class="btn btn-sm btn-dark">
                    Cetak Cover Rapor
                    <i class="fa fa-print"></i>
                </a>
                <a href="{{ route('teacher.management.print_report', $semesters['even']->report_id) }}" class="btn btn-sm btn-dark">
                    Cetak Isi Rapor
                    <i class="fa fa-print"></i>
                </a>
                @endif
            </td>
        @endforeach
    </tbody>
</table>
@endsection