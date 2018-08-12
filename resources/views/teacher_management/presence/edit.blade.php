@extends('layouts.admin')

@section('title', 'Seluruh Tahun Ajaran')

@section('styles')
<style>
    input.absence {
        max-width: 5rem;
    }
</style>
@endsection

@section('content')

<h1>
    <i class="fa fa-list-alt"></i>
    Kelola Kehadiran Siswa
</h1>

@if( session('message-success') )
<div class="alert alert-success">
    {{ session('message-success') }}
</div>
@endif

<p class="lead">
    Kelas {{ $room_term->room->name }}
</p>

<p class="lead">
    Tahun Ajaran {{ $room_term->term->code }} Semester {{ $room_term->even_odd }}
</p>

<hr/>

<div class="container" style="padding: 0.6rem 0rem 0.6rem 0rem">
    <div class="row">
        <div class="col col-md-3 text-left">
            <a href="{{ route('teacher.management.courses', [$room_term->term->id, $room_term->getOriginal('even_odd')]) }}"
                class="btn btn-sm btn-secondary">
                <i class="fa fa-arrow-left"></i>
                Kembali
            </a>
        </div>
        <div class="col-md-6">
        </div>
        <div class="col col-md-3">
        </div>
    </div>
</div>

<hr/>

<h3>
    <i class="fa fa-list"></i>
    Daftar Kehadiran Siswa
</h3>

<table class="table table-striped table-responsive-xl table-sm">
    <thead class="thead thead-dark">
        <tr>
            <th> # </th>
            <th> Nama Siswa </th>
            <th> Sakit </th>
            <th> Izin </th>
            <th> Alpa </th>
        </tr>
    </thead>

    <tbody>
        @foreach ($reports as $report)
        <tr>
            <td> {{ $loop->iteration }}. </td>
            <td> {{ $report->name }} </td>
            <td>
                <input
                    name = "absence_sick"
                    class="absence" type="number" min="0"
                    value="{{ $report->absence_sick }}"
                    data-report-id="{{ $report->id }}"
                    data-original-value="{{ $report->absence_sick }}">
            </td>
            <td>
                <input
                    name = "absence_permit"
                    class="absence" type="number" min="0"
                    value="{{ $report->absence_permit }}"
                    data-report-id="{{ $report->id }}"
                    data-original-value="{{ $report->absence_permit }}">
            </td>
            <td>
                <input
                    name = "absence_unknown"
                    class="absence" type="number" min="0"
                    value="{{ $report->absence_unknown }}"
                    data-report-id="{{ $report->id }}"
                    data-original-value="{{ $report->absence_unknown }}">
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="text-right">
    <button id="edit-button" class="btn btn-primary btn-sm">
        Perbarui Data
        <i class="fa fa-pencil"></i>
    </button>
</div>

@endsection

@section ('script')
<script>
    let warning_class = 'table-warning';
    let changed = {};

    $(document).ready(() => {
        $('input.absence').each((i, element) => {
            $(element).change(() => {

                let attribute = $(element).attr('name');
                let report_id = $(element).data('report-id');
                let data_value = $(element).val();

                if (data_value != $(element).data('original-value')) {

                    if (!changed.hasOwnProperty(report_id)) {
                        changed[report_id] = {};
                    }

                    changed[report_id][attribute] = data_value;

                    $(element).parent().addClass(warning_class);
                }
                else {
                    delete changed[report_id][attribute];

                    if (Object.keys(changed[report_id]).length == 0) {
                        delete changed[report_id];
                    }

                    $(element).parent().removeClass(warning_class);
                }
            });
        });

        $('#edit-button').click(() => {
            if (Object.keys(changed).length != 0) {
                $.post({
                    url: '{{ route('teacher.management.presence.edit', $room_term->id) }}',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'data': changed
                    },
                    success: (data, status) => {
                        window.location.replace(
                            '{{ route('teacher.management.presence.edit', $room_term->id) }}'
                        );
                    }
                });
            }
        });
    });
</script>
@endsection