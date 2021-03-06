@extends('layouts.admin')

@section('title', 'Seluruh Tahun Ajaran')

@section('content')

<h1>
    <i class="fa fa-list"></i>
    Daftar Seluruh Tahun Ajaran
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
            <a 
                class="btn btn-primary btn-sm"
                href="{{ route('terms.create') }}">
                Tambah Tahun Ajaran Baru
                <i class="fa fa-plus"></i>
            </a>
        </div>
    </div>
</div>

<hr>

<table class='table table-striped table-responsive-xl table-sm'>
    <thead class='thead-dark'>
        <tr>
            <th> # </th>
            <th st> Tahun Mulai </th>
            <th st> Tahun Selesai </th>
            <th st> Nilai KKM (Kriteria Ketuntasan Minimal) </th>
            <th st> Kendali </th>
        </tr>
    </thead>

    <tbody>
        @foreach ($terms as $term)
        <tr>
            <td> {{ $loop->iteration }}. </td>
            <td> {{ $term->term_start }} </td>
            <td> {{ $term->term_end }} </td>
            <td> {{ $term->passing_grade }} </td>
            <td>
                <a href="{{ route('terms.detail', $term) }}" class="btn btn-dark btn-sm">
                    <i class="fa fa-list-alt"></i>
                    Detail
                </a>

                <a href="{{ route('terms.edit', $term) }}" class="btn btn-dark btn-sm">
                    Sunting
                    <i class="fa fa-pencil"></i>
                </a>

                <form
                    class="form-delete" action="{{ route('terms.delete', $term) }}"
                    style="display: inline-block"
                    data-label="{{ $term->term_start . "-" . $term->term_end }}">
                    <button class="btn btn-danger btn-sm">
                        Hapus
                        <i class="fa fa-trash"></i>
                    </button>
                </form>

            </td>
        @endforeach
    </tbody>
</table>
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

                swal('Anda yakin ingin menghapus tahun ajaran ' + label + '?', {
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