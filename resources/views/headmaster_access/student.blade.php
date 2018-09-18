@extends('layouts.admin')
@section('title', "Data Siswa " . $student->student_id)
@section('content')

<h1>
    <i class="fa fa-list"></i>
    Data Siswa {{ $student->student_id }}
</h1>

@if( session('message-success') )
    <div class="alert alert-success">
        {{ session('message-success') }}
    </div>
@endif

<hr>

<div class="container">
    <div class="row">
        <div class="col-md-3">
        </div>
        <div class="col"></div>
        <div class="col-md-3 text-right">
            <a href="{{ route('headmaster_access.students') }}" class="btn btn-secondary btn-sm">
                <i class="fa fa-arrow-left"></i>
                Kembali
            </a>
        </div>
    </div>
</div>

<hr>

<div class="container">
    <table class="table table-bordered table-sm">
        <tbody>
            <tr> <th> Nama </th> <td> {{ $student->user->name }} </td> </tr>
            <tr> <th> Status Siswa </th> <td> {{ $student->active ? 'Aktif' : 'Non-Aktif' }} </td> </tr>
            <tr> <th> Jenis Kelamin </th> <td> {{ $student->sex() }} </td> </tr>
            <tr> <th> NIM </th> <td> {{ $student->student_id }} </td> </tr>
            <tr> <th> Tempat, Tanggal Lahir </td> <td> {{ $student->birthplace . ", " . $student->birthdate->format('d-m-Y') }} </td> </tr>
            <tr> <th> Status Dalam Keluarga </th> <td> {{ $student->status_in_family }} </td> </tr>
            <tr> <th> Anak ke- </th> <td> {{ $student->nth_child }} </td> </tr>
            <tr> <th> Alamat </th> <td> {{ $student->address }} </td> </tr>
            <tr> <th> Nomor Telefon </th> <td> {{ $student->phone }} </td> </tr>
            <tr> <th> Nama Ayah </th> <td> {{ $student->father_name }} </td> </tr>
            <tr> <th> Nama Ibu </th> <td> {{ $student->mother_name }} </td> </tr>
            <tr> <th> Alamat Orang Tua </th> <td> {{ $student->parents_address }} </td> </tr>
            <tr> <th> Pekerjaan Ayah </th> <td> {{ $student->father_occupation }} </td> </tr>
            <tr> <th> Pekerjaan Ibu </th> <td> {{ $student->mother_occupation }} </td> </tr>
            <tr> <th> Nama wali </th> <td> {{ $student->guardian_name }} </td> </tr>
            <tr> <th> Alamat wali </th> <td> {{ $student->guardian_address }} </td> </tr>
            <tr> <th> Pekerjaan wali </th> <td> {{ $student->guardian_occupation }} </td> </tr>
        </tbody>
    </table>
</div>
@endsection