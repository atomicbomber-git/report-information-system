@extends('layouts.admin')

@section('title', "Kelola Peserta Ekstrakurikuler Tahun Ajaran" . $extracurricular->term->code)

@section('content')

<h1>
    <i class="fa fa-list"></i>
    Kelola Peserta Ekstrakurikuler {{ $extracurricular->name }}
</h1>

<p class="lead">
    Tahun Ajaran {{ $extracurricular->term->code }}
</p>

<p class="lead">
    Semester {{ \App\RoomTerm::EVEN_ODD[$even_odd] }}
</p>

<hr/>

<div class="container">
    <div class="row">
        <div class="col text-left">
            <a href="{{ route('extracurriculars.index_term', $extracurricular->term->id) }}" class="btn btn-secondary btn-sm">
                <i class="fa fa-arrow-left"></i>
                Kembali
            </a>
        </div>
        <div class="col-3">

        </div>
        <div class="col text-right">
            <a href="{{ route('extracurricular_reports.create', [$extracurricular->id, $even_odd]) }}" class="btn btn-primary btn-sm">
                Tambahkan Peserta Baru
                <i class="fa fa-plus"></i>
            </a>
        </div>
    </div>
</div>

<hr/>

@if( session('message-success') )
    <div class="alert alert-success">
        {{ session('message-success') }}
    </div>
@endif

<div class="container">
    <h2 class="mb-3">
        <i class="fa fa-list"></i>
        Daftar Seluruh Peserta
    </h2>

    <table class="table table-striped table-sm">
        <thead class="thead thead-dark">
            <tr>
                <th> # </th>
                <th> Nama Siswa </th>
                <th> Nomor Induk Siswa </th>
                <th> Kendali </th>
            </tr>
        </thead>

        <tbody>
            @foreach($extracurricular_reports as $report)
            <tr>
                <td> {{ $loop->iteration }} </td>
                <td> {{ $report->student_name }} </td>
                <td> {{ $report->student_id }} </td>
                <td>
                    <form action="" class="d-inline-block">
                        <button class="btn btn-danger btn-sm">
                            Hapus
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection

@section('script')
<script src="{{ asset('js/sweetalert.min.js') }}"> </script>
<script>
    $(document).ready(function() {
        $(".form-delete").each(function() {
            let form = $(this);
            form.submit(function(e) {
                e.preventDefault()

                let label = form.data('label');

                swal('Anda yakin ingin menghapus ekstrakurikuler ' + label + '?', {
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

        $('.alert-success').fadeOut(3000);
    });
</script>
@endsection