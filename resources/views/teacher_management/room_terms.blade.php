@extends('layouts.admin')

@section('title', 'Kelola Kelas Perwalian')

@section('content')

<h1>
    <i class="fa fa-list-alt"></i>
    Kelola Kelas {{ $information->room_name }}
</h1>

<p class="lead">
    Tahun Ajaran {{ $information->term_code }} Semester {{ $information->semester }}
</p>

<hr/>

<div class="container" style="padding: 0.6rem 0rem 0.6rem 0rem">
    <div class="row">
        <div class="col col-md-3 text-left">
            <a href="{{ route('teacher.management.courses', ['term_id' => $information->term_id, 'even_odd' => $information->even_odd]) }}" class="btn btn-sm btn-secondary">
                <i class="fa fa-arrow-left"></i>
                Kembali
            </a>
        </div>
        <div class="col-md-6">
        </div>
        <div class="col col-md-3">
        </div>
    </div>

    <div style="height: 5rem"></div>

    <h3> <i class="fa fa-list"></i> Daftar Siswa </h3>
    <hr/>

    <div class="form-group" style="width: 13rem">
        <label for="print_date"> Tanggal Pencetakan: </label>
        <input form="print-cover-form" class="form-control" name="print_date" type="date" value="{{ today()->format('Y-m-d') }}">
    </div>

    <table class="table table-striped table-responsive-xl table-sm" style="max-width: 35rem">
        <thead class="thead-dark">
            <tr>
                <th> # </th>
                <th> Nama </th>
                <th> Kendali</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($reports as $report)
            <tr>
                <td>
                    {{ $loop->iteration }}.
                </td>
                <td>
                    {{ $report->name }}
                </td>

                <td>
                    <form
                        id="print-cover-form"
                        action="{{ route('teacher.management.print_report_cover', $report->id) }}"
                        class="d-inline-block"
                        method="GET">
                        <button class="btn btn-sm btn-dark">
                            Cetak Cover Rapor
                            <i class="fa fa-print"></i>
                        </button>
                    </form>
                    
                    <a href="{{ route('teacher.management.print_report', $report->id) }}" class="btn btn-sm btn-dark">
                        Cetak Isi Rapor
                        <i class="fa fa-print"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<hr/>
@endsection