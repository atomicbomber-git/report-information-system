@extends('layouts.admin')

@section('title', "Kelola Nilai Ekstrakurikuler $extracurricular->name")

@section('content')

<h1>
    <i class="fa fa-list"></i>
    Kelola Nilai Ekstrakurikuler {{ $extracurricular->name }}
</h1>

<p class="lead">
    Tahun Ajaran {{ $extracurricular->term->code }} Semester {{ \App\RoomTerm::EVEN_ODD[$even_odd] }}
</p>

<hr/>

<div class="container">
    <div class="row">
        <div class="col text-left">
            <a href="{{ route('teacher.management.courses', [$extracurricular->term->id, $even_odd]) }}" class="btn btn-secondary btn-sm">
                <i class="fa fa-arrow-left"></i>
                Kembali
            </a>
        </div>
        <div class="col-3">

        </div>
        <div class="col text-right">
        </div>
    </div>
</div>

<hr/>

@if( session('message-success') )
    <div class="alert alert-success">
        {{ session('message-success') }}
    </div>
@endif

<div class="container mb-5">

    <div class="d-inline-block">
        <h2 class="mb-3">
            <i class="fa fa-list"></i>
            Daftar Peserta
        </h2>
    
        <table class="table table-striped table-sm">
            <thead class="thead thead-dark">
                <tr>
                    <th> # </th>
                    <th> Nama Siswa </th>
                    <th> Nilai </th>
                </tr>
            </thead>
    
            <tbody>
                @foreach ($extracurricular_reports as $report)
                <tr>
                    <td> {{ $loop->iteration }}. </td>
                    <td> {{ $report->student_name }} </td>
                    <td>
                        <select
                            data-id="{{ $report->id }}"
                            data-prev-value="{{ $report->score }}"
                            class="form-control form-control-sm report">
                            
                            @foreach (array_keys(\App\ExtracurricularReport::GRADES) as $grade)
                            <option value="{{ $grade }}" {{ $report->score != $grade ?: 'selected' }}>
                                {{ $grade }}
                            </option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-right mt-3">
            <button id="btn-update" class="btn-primary btn-sm">
                Perbarui Data
                <i class="fa fa-edit"></i>
            </button>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="{{ asset('js/sweetalert.min.js') }}"> </script>
<script>
    let warning_class = 'table-warning';
    let changed = {};

    $(document).ready(function() {
        $('select.report').each((i, elem) => {
            let select = $(elem);
            let extracurricular_report_id = select.data('id');
            let prev_value = select.data('prev-value');

            select.change(() => {
                if (select.val() !== prev_value) {
                    select.parent().parent().addClass(warning_class)
                    changed[extracurricular_report_id] = select.val();
                }
                else {
                    select.parent().parent().removeClass(warning_class);
                    delete changed[extracurricular_report_id];
                }
            })
        });

        $('button#btn-update').click(() => {
            swal({
                title: "Konfirmasi Aksi",
                text: "Apakah Anda Yakin Ingin Memperbarui Data?",
                icon: "warning",
                buttons: {
                    cancel: "Batal",
                    ok: {
                        text: "Perbarui",
                        closeModal: false
                    }
                } 
            })
            .then(willSubmit => {
                if (willSubmit == null) {
                    throw null;
                }

                return $.ajax({
                    method: 'POST',
                    url: '{{ route('teacher.management.extracurricular_edit_score', [$even_odd, $extracurricular->id]) }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        data: changed
                    }
                });
            })
            .then((response) => {
                swal.stopLoading();
                swal.close();
                window.location.replace('{{ route('teacher.management.extracurricular_edit_score', [$even_odd, $extracurricular->id]) }}');
            })
            .catch(error => {
                swal.stopLoading();
                swal.close();

                if (error == null) {
                    return;
                }

                swal("Gagal", "Data Gagal Diperbarui", "error");
            });
        });

        $('.alert-success').fadeOut(3000);
    });
</script>
@endsection