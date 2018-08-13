@extends('layouts.admin')

@section('title', "Kelola Deskripsi Nilai Spiritual Kelas " . $room_term->room->name . " Semester $room_term->even_odd")

@section('content')

<h1>
    <i class="fa fa-list"></i>
    Kelola Deskripsi Sikap Spiritual
</h1>

<p class="lead">
    Kelas {{ $room_term->room->name }} Semester {{ $room_term->even_odd }}
</p>

<hr/>

<div class="container" style="padding: 0.6rem 0rem 0.6rem 0rem">
    <div class="row">
        <div class="col col-md-3 text-left">
            <a href="{{ route('teacher.management.courses', ['term_id' => $room_term->term->id, 'even_odd' => $room_term->getOriginal('even_odd') ]) }}"
                class="btn btn-sm btn-secondary">
                <i class="fa fa-arrow-left"></i>
                Kembali
            </a>
        </div>
        <div class="col-md-6">
        </div>
        <div class="col col-md-3 text-right">
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
    <table class="table table-striped table-responsive-xl table-sm">
        <thead class="thead thead-dark">
            <tr>
                <th> # </th>
                <th> Nama Siswa </th>
                <th> Deskripsi Sikap Spiritual </th>
            </tr>
        </thead>

        <tbody>
            @foreach ($reports as $report)
            <tr>
                <td> {{ $loop->iteration }}. </td>
                <td> {{ $report->student_name }} ({{ $report->student_id }}) </td>
                <td> <textarea data-id="{{ $report->id }}" data-prev-value="{{ $report->spiritual_attitude_description }}" class="description form-control" rows="2">{{ $report->spiritual_attitude_description }}</textarea></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="text-right">
        <button id="btn-update" class="btn btn-primary btn-sm">
            Perbarui Data
            <i class="fa fa-pencil"></i>
        </button>
    </div>
</div>

@endsection

@section('script')

<div id="notification-container" style="position: fixed; bottom: 3rem; right: 3rem"></div>

<script src="{{ asset('js/notification.js') }}"></script>

<script>
    let warning_class = 'table-warning';
    let changed = {};

    $(document).ready(() => {
        $('textarea.description').each((i, elem) => {
            let textarea = $(elem);
            let course_report_id = $(elem).data('id');
            
            textarea.change(() => {
                if (textarea.val() != textarea.data('prev-value')) {
                    textarea.parent().parent().addClass(warning_class);
                    changed[course_report_id] = textarea.val();
                }
                else {
                    textarea.parent().parent().removeClass(warning_class);
                    delete changed[course_report_id];
                }
            });
        });

        $('button#btn-update').click(() => {
            if (Object.keys(changed) == 0) {
                return;
            }

            $.ajax({
                method: 'POST',
                url: '{{ route('teacher.management.spiritual_description', $room_term->id) }}',
                data: {
                    _token: '{{ csrf_token() }}',
                    data: changed
                },
                success: () => {
                    changed = {};

                    create_notification(
                        '{{ __('messages.update.success') }}',
                        document.getElementById('notification-container'),
                        'success'
                    );

                    $('textarea.description').each((i, elem) => {
                        $(elem).data('prev-value', $(elem).val());
                        $(elem).parent().parent().removeClass(warning_class);
                    });
                },
                error: () => {
                    changed = {};

                    create_notification(
                        '{{ __('messages.update.failure') }}',
                        document.getElementById('notification-container'),
                        'danger'
                    );
                }
            });
        });
    });
</script>

@endsection