@extends('layouts.admin')

@section('title', "Tambah Peserta Ekstrakurikuler Tahun Ajaran" . $extracurricular->term->code)

@section('content')

<h1>
    <i class="fa fa-list"></i>
    Tambah Peserta Ekstrakurikuler {{ $extracurricular->name }}
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
            <a href="{{ route('extracurricular_reports.index', [$extracurricular->id, $even_odd]) }}" class="btn btn-secondary btn-sm">
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
            Daftar Siswa
        </h2>
    
        <table class="table table-striped table-sm">
            <thead class="thead thead-dark">
                <tr>
                    <th class="p-2 text-center"> <i class="fa fa-plus"></i> </th>
                    <th class="p-2"> Nama Siswa </th>
                    <th class="p-2"> Nomor Induk Siswa </th>
                </tr>
            </thead>
    
            <tbody>
                @foreach ($reports as $report)
                <tr>
                    <td class="p-2 text-center"> <input type="checkbox" class="report" data-report-id="{{ $report->id }}"/> </td>
                    <td class="p-2"> {{ $report->student_name }} </td>
                    <td class="p-2"> {{ $report->student_id }} </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-right mt-4">
            <button id="btn-create" class="btn btn-primary btn-sm">
                Tambahkan
                <i class="fa fa-plus"></i>
            </button>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="{{ asset('js/sweetalert.min.js') }}"> </script>
<script>
    let hightlight_class = 'table-info';
    let reports = [];

    $(document).ready(function() {
        $('input.report').each((i, elem) => {
            
            let checkbox = $(elem);
            let report_id = $(elem).data('report-id');

            checkbox.change(() => {
                if (!reports.includes(report_id)) {
                    checkbox.parent().parent().addClass(hightlight_class);
                    reports.push(report_id);
                }
                else {
                    checkbox.parent().parent().removeClass(hightlight_class);
                    reports = reports.filter((report) => {
                        return report != report_id;
                    });
                }
            });
        });

        $('button#btn-create').click(() => {
            if (reports.length == 0) {
                return;
            }

            swal({
                title: "Konfirmasi Penambahan Siswa",
                text: "Apakan Anda yakin ingin menambahkan siswa ke dalam ekstrakurikuler ini?",
                icon: "info",
                buttons: {
                    cancel: 'Batal',
                    confirm: {
                        text: 'Tambahkan',
                        value: true,
                        closeModal: false
                    }
                }
            })
            .then((willSubmit) => {
                if (willSubmit == null) {
                    throw null;
                }

                return $.ajax({
                    method: 'POST',
                    url: '{{ route('extracurricular_reports.create', [$extracurricular->id, $even_odd]) }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        reports: reports
                    }
                });
            })
            .then((data) => {
                console.log(data);
            }) 
            .then(() => {
                swal.stopLoading();
                swal.close();
                window.location.replace('{{ route('extracurricular_reports.index', [$extracurricular->id, $even_odd]) }}');
            })
            .catch((err) => {
                if (err == null) {
                    return;
                }

                swal('Gagal', 'Data gagal ditambahkan', 'error');
            });
        });
    });
</script>
@endsection