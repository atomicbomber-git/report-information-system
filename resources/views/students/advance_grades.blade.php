@extends('layouts.admin')

@section('title', "Kelola Kenaikan Siswa Kelas $grade")

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
    Daftar Seluruh Siswa Kelas {{ $grade }}
</p>


@if( session('message-success') )
    <div class="alert alert-success">
        {{ session('message-success') }}
    </div>
@endif

<hr/>

<div class="container">
    <div class="row">
        <div class="col text-left">
            <a href="{{ route('students.index') }}" class="btn btn-secondary btn-sm">
                Kembali
                <i class="fa fa-arrow-left"></i>
            </a>
        </div>
        <div class="col-1"></div>
        <div class="col text-right">
        </div>
    </div>
</div>

<hr/>

<table id="table" class='table table-sm table-striped table-responsive-xl table-sm'>
    <thead class='thead-dark'>
        <tr>
            <th> </th>
            <th> Nama </th>
            <th> Nomor Induk </th>
            <th> Jenis Kelamin </th>
        </tr>
    </thead>

    <tbody>
        @foreach ($students as $student)
        <tr>
            <td>
                <input data-id="{{ $student->id }}" type="checkbox" class="form-control form-control-sm student">
            </td>
            <td> {{ $student->name }} </td>
            <td> {{ $student->student_id }} </td>
            <td> {{ \App\Student::SEXES[$student->sex] }} </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="text-right mt-3 mb-5">
    <button class="btn btn-secondary btn-sm" id="btn-select-all">
        Pilih Semua Siswa
        <i class="fa fa-check"></i>
    </button>

    <button class="btn btn-primary btn-sm" id="btn-advance">
        Naikkan ke Kelas {{ $grade + 1 }}
        <i class="fa fa-arrow-up"></i>
    </button>
</div>

@endsection

@section('script')
<script src="{{ asset('js/jquery.dataTables.js') }}"> </script>
<script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"> </script>
<script src="{{ asset('js/sweetalert.min.js') }}"> </script>
<script>
    $(document).ready(function() {

        let checkboxes = $('input.student');
        let student_ids = [];

        $('button#btn-select-all').click(() => {
            checkboxes.filter(':not(:checked)')
                .prop('checked', true)
                .change();
        });

        $('input.student').each((i, elem) => {
            let checkbox = $(elem);
            let student_id = checkbox.data('id');
            checkbox.change(() => {
                if (checkbox.is(':checked')) {
                    if (!student_ids.includes(student_id)) {
                        student_ids.push(student_id);
                    }

                    checkbox.parent().parent().addClass('table-info');
                }
                else {
                    student_ids = student_ids.filter(id => id != student_id);
                    checkbox.parent().parent().removeClass('table-info');
                }
            });
        });

        // DataTable
        $(".table").DataTable({
            "language": {
                "url": "{{ asset("Indonesian.json") }}"
            },
            "pagingType": "full",
            "lengthMenu": [12, 24, 36],
            "pageLength": 12,
            // "ordering": false,
            "columns": [
                {"orderable": false},
                null, null, null
            ]
        });

        // Submission
        $('button#btn-advance').click(() => {

            if (student_ids.length == 0) {
                swal('Informasi', 'Anda hanya dapat melakukan tindakan ini jika Anda telah memilih setidaknya satu(1) siswa dari daftar yang ada.', 'info');
                return;
            }

            // Confirmation dialog
            swal({
                title: 'Konfirmasi Tindakan',
                text: 'Apakah Anda yakin ingin menaikkan seluruh siswa yang dipilih ke kelas {{ $grade + 1 }}?',
                icon: 'warning',
                buttons: {
                    ok: {
                        text: 'Tambahkan',
                        closeModal: false
                    },
                    cancel: 'Batalkan'
                },
                dangerMode: true,
                closeOnClickOutside: true,
                closeOnEsc: true
            })
            .then(willSubmit => {
                if (willSubmit == null) {
                    throw null;
                }

                return $.ajax({
                    method: 'POST',
                    url: '{{ route('students.advance_grades', $grade) }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        student_ids: student_ids
                    }
                });
            })
            .then(response => {
                window.location.replace('{{ route('students.index') }}');
            })
            .catch(error => {
                if (error == null) {
                    return;
                }

                swal('Informasi', 'Tindakan gagal dilakukan.', 'error');
            })
        });

        // Fade out notifications
        $('.alert-success').fadeOut(3000);
    });
</script>
@endsection