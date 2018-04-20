@extends('layouts.admin')

@section('title', 'Nilai Mata Pelajaran')

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
            <a href="{{ route('teacher.management.terms') }}" class="btn btn-sm btn-secondary">
                <i class="fa fa-arrow-left"></i>
                Kembali
            </a>
        </div>
        <div class="col-md-6">
        </div>
        <div class="col col-md-3 text-right">
            <a href="{{ route('teacher.management.courses.exams', [$information->id, $information->even_odd, $information->room_term_id, $information->course_id]) }}"
                class="btn btn-primary btn-sm">
                Nilai UTS / UAS
                <i class="fa fa-list-alt"></i>
            </a>
        </div>
    </div>
</div>

<hr>

@foreach($knowledge_grade_groups as $key => $group)
<h4> KD {{ $loop->iteration }}: {{ $basic_competencies[$key]->name }} </h4>
    <div class="basic-competency-unit" data-id="{{ $key }}">
        <table class='table table-striped table-responsive-xl table-sm'>
            <thead class='thead-dark'>
                <tr>
                    <th> Nama Siswa </th>

                    <th> Tugas 1 </th>
                    <th> Tugas 2 </th>
                    <th> Tugas 3 </th>
    
                    <th> Ujian 1 </th>
                    <th> Ujian 2 </th>
                </tr>
            </thead>
            
            <tbody>
                @foreach($group as $student_grade_key => $student_grade)
                    <tr class="knowledge-grade-unit">
                        <td> {{ $student_grade->name }} <input type="hidden" name="id" value="{{ $student_grade->id }}"> </td>
                        <td> <input tabindex="{{ $loop->parent->index * 10 + 1 }}" form="group-first_assignment" min="0" max="100" type="number" class="form-control form-control-sm" name="first_assignment" data-prev-value="{{ $student_grade->first_assignment }}" value="{{ $student_grade->first_assignment }}"> </td>
                        <td> <input tabindex="{{ $loop->parent->index * 10 + 2 }}" form="group-second_assignment" min="0" max="100" type="number" class="form-control form-control-sm" name="second_assignment" data-prev-value="{{ $student_grade->second_assignment }}" value="{{ $student_grade->second_assignment }}"> </td>
                        <td> <input tabindex="{{ $loop->parent->index * 10 + 3 }}" form="group-third_assignment" min="0" max="100" type="number" class="form-control form-control-sm" name="third_assignment" data-prev-value="{{ $student_grade->third_assignment }}" value="{{ $student_grade->third_assignment }}"> </td>
                        <td> <input tabindex="{{ $loop->parent->index * 10 + 4 }}" form="group-first_exam" min="0" max="100" type="number" class="form-control form-control-sm" name="first_exam" data-prev-value="{{ $student_grade->first_exam }}" value="{{ $student_grade->first_exam }}"> </td>
                        <td> <input tabindex="{{ $loop->parent->index * 10 + 5 }}" form="group-second_exam" min="0" max="100" type="number" class="form-control form-control-sm" name="second_exam" data-prev-value="{{ $student_grade->second_exam }}" value="{{ $student_grade->second_exam }}"> </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-right">
            <button class="btn btn-primary btn-sm btn-update">
                Perbaharui Nilai
                <i class="fa fa-pencil"></i>
            </button>
        </div>
    </div>

@endforeach

@endsection

@section('script')
{{-- Loading icon to be loaded by Javascript --}}
<div class="d-none">
    <div id="loading-button-content">
        Menyimpan Data
        <i class="fa fa-spinner fa-spin"></i>
    </div>
</div>

{{-- Floating notification message --}}
<div id="notification-container" class="floating-notification-container">

</div>

<script>
    // Max and min input scores
    const SCORE_MIN = 0;
    const SCORE_MAX = 100;

    // CSS class for inputs whose values have been changed 
    const changed_indicator = 'table-warning'; 

    function create_notification(message, container, status, timeout = 2000) {
        let alert = document.createElement('div');
        alert.classList.add('alert', `alert-${status}`);
        alert.textContent = message;
        container.appendChild(alert);
        
        window.setTimeout(function() {
            $(alert).fadeOut();
        }, timeout)
    }

    $(document).ready(function() {

        let basic_competency_units = $('.basic-competency-unit');
        
        basic_competency_units.each(function(i, basic_competency_unit) {
            $(basic_competency_unit).find('.btn-update').click(function() {
                // Gather data from a basic competency unit
                let knowledge_grades = [];
                let knowledge_grade_units = $(basic_competency_unit).find('.knowledge-grade-unit');
                
                knowledge_grade_units.each(function (k, knowledge_grade_unit) {

                    let inputs = $(knowledge_grade_unit).find('input');
                    let knowledge_grade = {};
                    inputs.each(function(l, input) {
                        knowledge_grade[input.getAttribute('name')] = $(input).val();
                    });

                    knowledge_grades.push(knowledge_grade);
                });
                
                let after_ajax_update = function() {
                    knowledge_grade_units.each(function (k, knowledge_grade_unit) {
                        // Updates the prev value attribute of the input tags, supposed to be called after
                        // every successful ajax update request.
                        $(knowledge_grade_unit).find('input').each(function(l, input) {
                            $(input).data('prev-value', $(input).val());
                        });
                        
                        // Remove every CSS indicators from input tags
                        $(knowledge_grade_unit).find(`td.${changed_indicator}`).each(function (k, cell) {
                            $(cell).removeClass(changed_indicator);
                        });
                    });
                };
                
                // Save the innerHTML of the clicked button and load the 'loading' content
                let button = $(this);
                let button_contents = $(this).html();
                button.html($('#loading-button-content').html());
                button.attr('disabled', 'true')
                
                // Container for notification
                let notification_container = document.getElementById('notification-container');

                // AJAX call to update data on the server
                $.ajax({
                    url: '{{ route('knowledge_grades.update') }}',
                    data: { _token: '{{ csrf_token() }}', data: knowledge_grades },
                    method: 'POST',
                    success: function (data) {
                        after_ajax_update();
                        button.html(button_contents);
                        button.removeAttr('disabled');

                        create_notification('Data berhasil diperbaharui', notification_container, 'success');
                    },
                    error: function (data) {
                        button.html(button_contents);
                        button.removeAttr('disabled');

                        create_notification('Data gagal diperbaharui', notification_container, 'danger');
                    }
                });
            });
        });
        
        let table_cells = $('td');
        table_cells.each(function (i, cell) {
            $(cell).find('input').change(function() {
                if ($(this).val() > SCORE_MAX) {
                    $(this).val(SCORE_MAX);
                }
                else if ($(this).val() < SCORE_MIN) {
                    $(this).val(SCORE_MIN);
                }

                if ($(this).data('prev-value') == $(this).val()) {
                    if ($(cell).hasClass(changed_indicator)) {
                        $(cell).removeClass(changed_indicator);
                    }
                }
                else {
                    if ( ! $(cell).hasClass(changed_indicator)) {
                        $(cell).addClass(changed_indicator);
                    }
                }
            });
        });
    })
</script>
@endsection