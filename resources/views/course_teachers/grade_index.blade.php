@extends('layouts.admin')

@section('title', 'Seluruh Tahun Ajaran')

@section('styles')
<style>
    .container-course {
        width: 30rem;
    }
</style>
@endsection

@section('content')

<h1>
    <i class="fa fa-list"></i>
    Kelola Guru Mata Pelajaran Kelas {{ $information->grade }} Tahun Ajaran {{ $information->term->code }}
</h1>

<p class="lead">
    Semester {{ $information->even_odd }}
</p>

<hr/>

<div class="container" style="padding: 0.6rem 0rem 0.6rem 0rem">
    <div class="row">
        <div class="col col-md-3 text-left">
            <a href="{{ route('course_teachers.term_index') }}" class="btn btn-sm btn-secondary">
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

@foreach($course_teacher_groups as $course_name => $course_teachers)
<div style="max-width: 34rem" class="input-unit">

    {{-- Title --}}
    <h3> {{ $course_name }} </h3>

    <table class="table table-sm table-striped">
        <thead class="thead-dark">
            <tr>
                <th> # </th>
                <th> Nama Kelas </th>
                <th> Guru </th>
            </tr>
        </thead>

        <tbody>
        @foreach($course_teachers as $course_teacher)
            <tr>
                <td> {{ $loop->iteration }}. </td>
                <td> {{ $course_teacher->room_name }} </td>
                <td>
                    <select
                        class="course-teacher"
                        data-id="{{ $course_teacher->id }}"
                        data-course-id="{{ $course_teacher->course_id }}"
                        data-prev-value="{{ $course_teacher->teacher_id }}">
                    @foreach($teachers as $teacher)
                        <option
                            value="{{ $teacher->id }}"
                            {{ $teacher->id == $course_teacher->teacher_id ? "selected" : "" }}>
                            {{ $teacher->name }} ({{ $teacher->teacher_id }})
                        </option>
                    @endforeach
                    </select>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="text-right">
        <button class="btn btn-primary btn-sm btn-update" data-course-id="{{ $course_teacher->course_id }}">
            Perbarui Data
            <i class="fa fa-pencil"></i>
        </button>
    </div>
</div>
@endforeach


@endsection

@section('script')

<div id="notification-container" style="position: fixed; bottom: 3rem; right: 3rem"></div>

<script src="{{ asset('js/notification.js') }}"></script>

<script>
    $(document).ready(function() {

        let warning_class = 'table-warning';
        let changed = {};

        $('select.course-teacher').each((i, elem) => {
            $(elem).change(() => {

                let course_id = $(elem).data('course-id');
                let course_teacher_id = $(elem).data('id');
                let select_val = $(elem).val();
                
                if (select_val != $(elem).data('prev-value')) {
                    $(elem).parent().addClass(warning_class);
                
                    if (!changed.hasOwnProperty(course_id)) {
                        changed[course_id] = {};
                    }

                    changed[course_id][course_teacher_id] = select_val;
                }
                else {
                    delete changed[course_id][course_teacher_id];

                    if (Object.keys(changed[course_id]).length == 0) {
                        delete changed[course_id];
                    }

                    $(elem).parent().removeClass(warning_class);
                }
                
            });
        });

        $('button.btn-update').click(function() {
            let course_id = $(this).data('course-id');
            
            if (!changed.hasOwnProperty(course_id)) {
                return;
            }
            
            $.ajax({
                method: 'POST',
                url: '{{ route('course_teachers.update') }}',
                data: {
                    _token: '{{ csrf_token() }}',
                    data: changed[course_id]
                },
                success: () => {
                    $(`select[data-course-id=${course_id}]`).each((i, elem) => {
                        $(elem).data('prev-value', $(elem).val());
                        $(elem).parent().removeClass(warning_class);
                    });

                    create_notification(
                        '{{ __('messages.update.success') }}',
                        document.getElementById('notification-container'),
                        'success'
                    );
                },
                error: () => {
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