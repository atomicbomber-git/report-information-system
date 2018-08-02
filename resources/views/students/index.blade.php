@extends('layouts.admin')

@section('title', 'Seluruh Siswa')

@section('content')

<p class="h1">
    <i class="fa fa-list"></i>
    Daftar Seluruh Siswa
</p>

<hr/>

@if( session('message-success') )
    <div class="alert alert-success">
        {{ session('message-success') }}
    </div>
@endif

<div style="width: 100%; padding: 1.4rem; text-align: right">
    <a 
        class="btn btn-primary btn-sm"
        href="{{ route('students.create') }}"
        >
        Tambah Siswa Baru
        <i class="fa fa-plus"></i>
    </a>
</div>

<table class='table table-sm table-striped'>
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
            <td> {{ \App\Student::RELIGIONS[$student->religion] }} </td>
            <td> {{ $student->address }} </td>
            <td> {{ $student->phone }} </td>
            <td>
                <a href="" class="btn btn-dark btn-sm">
                    <i class="fa fa-eye"></i>
                </a>
                <a href="" class="btn btn-dark btn-sm"> 
                    <i class="fa fa-pencil"></i>
                </a>

                <form
                    method="POST"
                    action="{{ route('students.delete', $student) }}"
                    class="form-delete d-inline-block"
                    data-student="{{ $student->user->name }}">

                    @csrf

                    <button class="btn btn-sm btn-danger">
                        Hapus
                        <i class="fa fa-trash"></i>
                    </button>

                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection

@section('script')
<script src="{{ asset('js/sweetalert.min.js') }}"> </script>
<script>
    $(document).ready(function() {
        // Handle delete button click
        $(".form-delete").each(function() {
            let form = $(this);
            form.submit(function(e) {
                e.preventDefault()

                let label = form.data('student');

                swal(`Anda yakin ingin menghapus berkas siswa ${label}?`, {
                    title: "Konfirmasi Penghapusan",
                    icon: "warning",
                    buttons: ["Tidak", "Ya"],
                    dangerMode: true
                })
                .then(function(willDelete) {
                    if (willDelete) {
                        form.off("submit").submit();
                    }
                });
            });
        });

        // Fade out notifications
        $('.alert-success').fadeOut(3500);
    });
</script>
@endsection