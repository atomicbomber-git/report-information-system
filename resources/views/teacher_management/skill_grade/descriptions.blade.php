@extends('layouts.admin')

@section('title', "Deskripsi Nilai Keterampilan Mata Pelajaran $course->name")

@section('styles')
<style>
    .basic-competency-unit input {
        max-width: 7rem;
    }

    .floating-notification-container {
        position: fixed;
        width: 20rem;
        bottom: 0.2rem;
        right: 0.2rem;
    }
</style>
@endsection

@section('content')

<p class="h1">
    <i class="fa fa-list"></i>
    Deskripsi Nilai Keterampilan Mata Pelajaran {{ $course->name }}
</p>

<p class="lead">
    Kelas {{ $room_term->room->name }}
</p>

<p class="lead">
    Tahun Ajaran {{ $room_term->term->code }} Semester {{ $room_term->even_odd }}
</p>

<hr>

@if( session('message-success') )
    <div class="alert alert-success">
        {{ session('message-success') }}
    </div>
@endif

<div class="container" style="padding: 0.6rem 0rem 0.6rem 0rem">
    <div class="row">
        <div class="col col text-left">
            <a href="{{ route('teacher.management.courses.skill_detail', [$room_term->term->id, $room_term->getOriginal('even_odd'),$room_term->id, $course->id]) }}"
                class="btn btn-sm btn-secondary">
                <i class="fa fa-arrow-left"></i>
                Kembali
            </a>
        </div>
        <div class="col-md-4">
        </div>
        <div class="col col text-right">
            <a href="{{ route('teacher.management.courses.generate_skill_description_text', [$room_term->id, $course->id]) }}" class="btn btn-sm btn-dark">
                Isi Nilai Deskripsi
            </a>
        </div>
    </div>
</div>

<hr>

<div class="container">
    <table class='table table-striped table-responsive-xl table-sm'>
        <thead class="thead thead-dark">
            <tr>
                <th> # </th>
                <th> Nama Siswa </th>
                <th> Deskripsi Nilai Keterampilan </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($descriptions as $description)
            <tr>
                <td> {{ $loop->iteration }}. </td>
                <td> {{ $description->student_name }} ({{ $description->student_id }}) </td>
                <td>
                    <textarea data-id="{{ $description->course_report_id }}" data-prev-value="{{ $description->skill_description }}" class="description form-control" style="width: 100%" rows="2">{{ $description->skill_description }}</textarea>
                </td>
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

{{-- Floating notification message --}}
<div id="notification-container" class="floating-notification-container">
</div>

<script src="{{ asset('js/sweetalert.min.js') }}"> </script>
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
                url: '{{ route('teacher.management.descriptions.edit', [$room_term, $course]) }}',
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