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

<p class="h1">
    <i class="fa fa-list"></i>
    Kelola Guru Mata Pelajaran Kelas {{ $information->grade }} Tahun Ajaran {{ $information->term->code }} Semester {{ $information->even_odd }}
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

@foreach($courses as $course_name => $course)
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
        @foreach($course as $room_term)
            <tr>
                <td> {{ $loop->iteration }}. </td>
                <td> {{ $room_term->room_name }} </td>
                <td>
                    <select data-id="{{ $room_term->id }}" data-prev-value="{{ $room_term->teacher_id }}">
                    @foreach($teachers as $teacher)
                        <option
                            value="{{ $teacher->id }}"
                            {{ $teacher->id == $room_term->teacher_id ? "selected" : "" }}
                            >
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
        <button class="btn btn-primary btn-sm btn-update">
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

        let changed_indicator = 'table-warning';
        $('select').each(function() {
            
            let select_elem = $(this);
            let table_row = select_elem.parent().parent();

            $(this).change(function() {
                
                if (select_elem.data('prev-value') != select_elem.val()) {
                    if ( ! table_row.hasClass(changed_indicator)) {
                        table_row.toggleClass(changed_indicator);
                    }
                }
                else {
                    if (table_row.hasClass(changed_indicator)) {
                        table_row.toggleClass(changed_indicator);
                    }
                }

            });
        });

        $('.input-unit').each(function() {
            
            let container = $(this);

            container.find('.btn-update').click(function() {
                
                let data = [];
                container.find(`.${changed_indicator} select`).each(function() {
                    data.push({
                        id: $(this).data('id'),
                        teacher_id: $(this).val()
                    });
                });

                if (data.length == 0) { return; }
                
                $.ajax({
                    method: 'POST',
                    url: '{{ route('course_teachers.update') }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        data: data
                    },
                    success: function() {
                        $(`.${changed_indicator}`).each(function() {
                            $(this).removeClass(changed_indicator);
                            let select_elem = $(this).find('select');
                            select_elem.data('prev-value', select_elem.val());
                        });

                        create_notification(
                            'Data berhasil diperbarui',
                            document.getElementById('notification-container'),
                            'success'
                        );
                    },
                    error: function() {
                        create_notification(
                            'Data gagal diperbarui',
                            document.getElementById('notification-container'),
                            'danger'
                        );
                    }
                });

            });
        });
    });
</script>

@endsection