@extends('layouts.admin')

@section('title', 'Nilai Mata Pelajaran')

@section('styles')
<style>
    #data-table input.input-sm {
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
    Nilai Mata Pelajaran {{ $course->name }}
</p>

<p class="lead">
    Kelas {{ $room->name }}
</p>

<p class="lead">
    Tahun Ajaran {{ $information->term_code }} Semester {{ $information->semester }}
</p>

<hr>

@if( session('message-success') )
    <div class="alert alert-success">
        {{ session('message-success') }}
    </div>
@endif

<div class="container" style="padding: 0.6rem 0rem 0.6rem 0rem">
    <div class="row">
        <div class="col col-md-3 text-left">
            <a href="{{ route('teacher.management.courses.detail', [$information->id, $information->even_odd, $information->room_term_id, $information->course_id]) }}" class="btn btn-sm btn-secondary">
                <i class="fa fa-arrow-left"></i>
                Kembali
            </a>
        </div>
        <div class="col-md-6">
        </div>
        <div class="col col-md-3 text-right">
            <a href="{{ route('teacher.management.courses.generate_description_text', [$information->room_term_id, $information->course_id]) }}" class="btn btn-dark btn-sm">
                Isi Nilai Deskripsi
            </a>
        </div>
    </div>
</div>

<hr>

<table id="data-table" class="table table-striped table-responsive-xl table-sm">
    <thead class="thead thead-dark">
        <tr>
            <th> Nama Siswa </th>
            <th> UTS </th>
            <th> UAS </th>
            <th> Deskripsi Pengetahuan </th>
        </tr>
    </thead>

    <tbody>
        @foreach($course_reports as $course_report)
        <tr data-id="{{ $course_report->id }}">
            <td> {{ $course_report->name }} </td>
            <td>
                <input type="number" tabindex='1' name='mid_exam' class="input-sm form-control form-control-sm" data-prev-value="{{ $course_report->mid_exam }}" value="{{ $course_report->mid_exam }}">
            </td>
            <td>
                <input type="number" tabindex='2' name='final_exam' class="input-sm form-control form-control-sm" data-prev-value="{{ $course_report->final_exam }}" value="{{ $course_report->final_exam }}">
            </td>
            <td>

                <input type="text" tabindex='3' name='knowledge_description' class="form-control form-control-sm" data-prev-value="{{ $course_report->knowledge_description }}" value="{{ $course_report->knowledge_description }}">
            </td>
            <td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="text-right">
    <button id="btn-update" class="btn btn-primary btn-sm">
        Perbarui Nilai
        <i class="fa fa-check"></i>
    </button>
</div>


@endsection

@section("script")
<div id="notification-container" class="floating-notification-container">
{{-- Supposed to contain floating notifs --}}
</div>

<script>
    function create_notification(message, container, status, timeout = 2000) {
        let alert = document.createElement('div');
        alert.classList.add('alert', `alert-${status}`);
        alert.textContent = message;
        container.appendChild(alert);
        
        window.setTimeout(function() {
            $(alert).fadeOut();
        }, timeout)
    }

    let changed_indicator = 'table-warning';

    $(document).ready(function() {

        $('#btn-update').click(function() {

            // Don't do anything if nothing's been changed, duh
            if ($(`.${changed_indicator}`).length == 0) {
                
                create_notification(
                    'Tidak terdapat data yang berubah dari nilai semula.',
                    document.getElementById('notification-container'),
                    'danger'
                )

                return;
            }

            let data = [];
            
            $('#data-table tbody tr').each(function(i, tr) {
                let changed_inputs = $(tr).find(`td.${changed_indicator} input`);

                if (changed_inputs.length == 0) {
                    return;
                }
                
                let record = { id: $(tr).data('id') };

                changed_inputs.each(function(j, input) {
                    record[$(input).attr('name')] = $(input).val()
                });
                
                data.push(record);
            });

            console.log(data);
            
            $.ajax({
                url: '{{ route('course_reports.update') }}',
                method: 'POST',
                data: { _token: '{{ csrf_token() }}', data: data },
                success: function (data) {

                    // Remove 'changed' CSS indicator, and set the previous value to the updated value
                    $(`td.${changed_indicator}`).each(function (i, td) {
                        $(td).removeClass(changed_indicator);
                        
                        let input = $(td).find('input');
                        input.data(
                            'prev-value',
                            input.val()
                        );
                    });

                    create_notification(
                        'Data berhasil diperbarui',
                        document.getElementById('notification-container'),
                        'success'
                    )
                },
                error: function (data) {
                    create_notification(
                        'Data gagal diperbarui',
                        document.getElementById('notification-container'),
                        'danger'
                    )
                }
            });
        });

        $('#data-table td').each(function (i, td) {
            let input = $(td).find('input');
            $(input).change(function () {
                if ( $(input).val() !== $(input).data('prev-value')) {
                    if ( ! $(td).hasClass(changed_indicator)) {
                        $(td).addClass(changed_indicator);
                    }
                }
                else {
                    if ($(td).hasClass(changed_indicator)) {
                        $(td).removeClass(changed_indicator)
                    }
                }
            });
        });
    });
</script>
@endsection