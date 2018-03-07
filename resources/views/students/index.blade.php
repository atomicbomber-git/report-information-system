@extends('layouts.admin')

@section('title', 'Seluruh Siswa')

@section('content')

<p class="h1">
    <i class="fa fa-list"></i>
    Daftar Seluruh Siswa
</p>

<hr>

<div style="width: 100%; padding: 1.4rem; text-align: right">
    <a 
        class="btn btn-primary btn-sm"
        href="{{ route('students.create') }}"
        >
        Tambah Siswa Baru
        <i class="fa fa-plus"></i>
    </a>
</div>
    

<table class='table'>
    <thead class='thead-dark'>
        <tr>
            <th> # </th>
            <th> Nama </th>
            <th> Nomor Induk </th>
            <th> Jenis Kelamin </th>
            <th> Tempat, Tanggal Lahir </th>
            <th> Agama </th>
            <th> Alamat </th>
            <th> No. Telefon </th>
            <th> Kendali </th>
        </tr>
    </thead>

    <tbody>
        @foreach ($students as $student)
        <tr>
            <td> {{ $loop->iteration }}. </td>
            <td> {{ $student->user->name }} </td>
            <td> {{ $student->student_id }} </td>
            <td> {{ \App\Student::SEXES[$student->sex] }} </td>
            <td> {{ $student->birthplace }}, {{ $student->birthdate }} </td>
            <td> {{ $student->religion }} </td>
            <td> {{ $student->address }} </td>
            <td> {{ $student->phone }} </td>
            <td>
                <a href="" class="btn btn-dark btn-sm">
                    <i class="fa fa-eye"></i>
                </a>
                <a href="" class="btn btn-dark btn-sm"> 
                    <i class="fa fa-pencil"></i>
                </a>
                <a href="" class="btn btn-danger btn-sm">
                    <i class="fa fa-trash"></i>
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection