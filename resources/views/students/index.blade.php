@extends('layouts.admin')

@section('title', 'Seluruh Siswa')

@section('styles')

<link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap4.min.css') }}">

<style>
    #table {
        border-collapse: collapse !important;
    }
</style>

@endsection

@section('content')

<p class="h1">
    <i class="fa fa-list"></i>
    Daftar Seluruh Siswa
</p>


@if( session('message-success') )
    <div class="alert alert-success">
        {{ session('message-success') }}
    </div>
@endif

<hr/>

<div class="container">
    <div class="row">
        <div class="col"></div>
        <div class="col-1"></div>
        <div class="col text-right">
            <a 
                class="btn btn-primary btn-sm align-top"
                href="{{ route('students.create') }}">
                Tambah Siswa
                <i class="fa fa-plus"></i>
            </a>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col">
            <div class="alert alert-info">
                <i class="fa fa-info"></i>
                @if(request('show_inactives'))
                    Sekarang siswa non-aktif tidak ditampilkan
                @else
                    Sekarang siswa non-aktif ditampilkan
                @endif

                <a href="{{ route('students.index', ['show_inactives' => request('show_inactives') ? FALSE : TRUE]) }}" class="btn btn-info btn-sm">
                    {{ request('show_inactives') ? "" : "Tidak" }} Tampilkan
                    <i class="fa {{ request('show_inactives') ? "fa-eye" : "fa-eye-slash" }}"></i>
                </a>
            </div>
        </div>
        <div class="col-1"></div>
        <div class="col text-right">
            @if(filled($advancable_grades))
            @foreach ($advancable_grades as $advancable_grade)
            <a href="{{ route('students.advance_grades', $advancable_grade) }}" class="btn btn-dark btn-sm">
                Kenaikan Kelas {{ $advancable_grade }}
                <i class="fa fa-arrow-up"></i>
            </a>
            @endforeach
            @endif

            @if(filled($last_grade))
            <a class="btn btn-dark btn-sm" href="{{ route('students.deactivate', $last_grade) }}">
                Deaktivasi Siswa Kelas {{ $last_grade }}
                <i class="fa fa-pencil"></i>
            </a>
            @endif
        </div>
    </div>
</div>

<hr/>

<table id="table" class='table table-sm table-striped table-responsive-xl table-sm'>
    <thead class='thead-dark'>
        <tr>
            <th> # </th>
            <th> Nama </th>
            <th> Nama Pengguna </th>
            <th> Nomor Induk </th>
            <th> Jenis Kelamin </th>
            {{-- <th> Tempat, Tanggal Lahir </th> --}}
            <th> Jenjang (Kelas) </th>
            <th> Kendali </th>
        </tr>
    </thead>

    <tbody>
        @foreach ($students as $student)
        <tr>
            <td> {{ $loop->iteration }}. </td>
            <td> {{ $student->name }} </td>
            <td> {{ $student->username }} </td>
            <td> {{ $student->student_id }} </td>
            <td> {{ \App\Student::SEXES[$student->sex] }} </td>
            {{-- <td> {{ $student->birthplace }}, {{ $student->birthdate }} </td> --}}
            <td> {{ $student->current_grade }} </td>
            <td>
                <a href="{{ route('students.edit', $student->id) }}" class="btn btn-dark btn-sm"> 
                    Sunting
                    <i class="fa fa-pencil"></i>
                </a>

                <form
                    method="POST"
                    action="{{ route('students.delete', $student->id) }}"
                    class="form-delete d-inline-block"
                    data-student="{{ $student->name }}">

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
<script src="{{ asset('js/jquery.dataTables.js') }}"> </script>
<script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"> </script>
<script src="{{ asset('js/sweetalert.min.js') }}"> </script>
<script>
    $(document).ready(function() {

        $('[data-toggle="tooltip"]').tooltip();

        // DataTable
        $(".table").DataTable({
            "language": {
                "url": "{{ asset("Indonesian.json") }}"
            },
            "pagingType": "full",
            "lengthMenu": [12, 24, 36],
            "pageLength": 12
        });

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