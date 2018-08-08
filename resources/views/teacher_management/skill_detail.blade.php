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
    Nilai Keterampilan Mata Pelajaran {{ $course->name }}
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

<hr>

<div class="container">

    <h4> Kendali Tipe Penilaian: </h4>

    @foreach($skill_type_usages as $usage)
        <form
            method="POST"
            action="{{ $usage['is_used'] ? route('skill_grades.remove_score_type') : route('skill_grades.add_score_type') }}"
            class="d-inline-block">
            
            @csrf

            <input type="hidden" name="type" value="{{ $usage['type'] }}">
            <input type="hidden" name="room_term_id" value="{{ $room_term->id }}">
            <input type="hidden" name="course_id" value="{{ $course->id }}">
            
            <button class="btn btn-{{ $usage['is_used'] ? 'success' : 'danger' }} btn-sm">
                {{ $usage['type'] }}
                <i class="fa fa-{{ $usage['is_used'] ? 'check' : 'times' }} }}"></i>
            </button>
        </form>
    @endforeach

    <hr>

    @foreach ($skill_grade_groups as $basic_competency => $group)
    <h4> KD {{ $loop->iteration }}: {{ $basic_competency }} </h4>
    <table class='table table-striped table-responsive-xl table-sm'>
        <thead class="thead thead-dark text-center">
            <tr>
                <th rowspan="2"> Nama Siswa </th>
                
                @foreach ($group->first() as $grade)
                <th colspan="6">
                    {{ $grade->type }}
                </th>
                @endforeach
            </tr>

            <tr>
                @foreach ($group->first() as $grade)

                @for($i = 1; $i <= 6; ++$i)
                <th>
                    {{ $i }}
                </th>
                @endfor

                @endforeach
            </tr>
        </thead>

        <tbody>
            @foreach ($group as $student_name => $grades)
            <tr>
            <td> {{ $student_name }} </td>
            
            @foreach ($grades as $grade)
            
            @for($i = 1; $i <= 6; ++$i)
            <th>
                <input
                    data-id="{{ $grade->skill_grade_id }}"
                    data-basic-competency-id="{{ $grade->basic_competency_id }}"
                    data-original-value="{{ $grade->{"score_$i"} }}"
                    data-field="{{ "score_$i" }}"
                    style="width: 2.5rem; font-size: 9pt" type="number" value="{{ $grade->{"score_$i"} }}">
            </th>
            @endfor

            @endforeach
            
            </tr>
            @endforeach
        </tbody>
    <table>

    <div class="text-right">
        <button
            data-basic-competency-id="{{ $grade->basic_competency_id }}"
            class="btn btn-primary btn-sm">
            Perbaharui Data
            <i class="fa fa-pencil"></i>
        </button>
    </div>

    @endforeach
</div>

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
    function create_notification(message, container, status, timeout = 2000) {
        let alert = document.createElement('div');
        alert.classList.add('alert', `alert-${status}`);
        alert.textContent = message;
        container.appendChild(alert);
        
        window.setTimeout(function() {
            $(alert).fadeOut();
        }, timeout)
    }
</script>

<script>
    $(document).ready(function() {

        let notification_container = document.getElementById('notification-container');
        let changed_data = {};

        $('input').change(function() {

            if ( ! changed_data.hasOwnProperty($(this).data('basic-competency-id')) ) {
                changed_data[$(this).data('basic-competency-id')] = {};
            }

            let basic_competency_unit = changed_data[$(this).data('basic-competency-id')];

            if ($(this).data('original-value') == $(this).val() ) {

                $(this).parent().removeClass('table-warning');

                delete basic_competency_unit[$(this).data('id')][$(this).data('field')];
                return;
            }

            $(this).parent().addClass('table-warning');

            if ( ! basic_competency_unit.hasOwnProperty($(this).data('id')) ) {
                basic_competency_unit[$(this).data('id')] = {};
            }

            basic_competency_unit[$(this).data('id')][$(this).data('field')] = $(this).val();
        });

        $('button').click(function () {
            let basic_competency_id = $(this).data('basic-competency-id');
            
            $.ajax({
                method: 'POST',
                url: '{{ route('skill_grades.update') }}',
                data: {
                    _token: '{{ csrf_token() }}',
                    data: changed_data[basic_competency_id]
                },
                success: function() {
                    create_notification('Data berhasil diperbaharui', notification_container, 'success');
                    $('.table-warning').each(function(index, elem) {
                        $(elem).removeClass('table-warning');
                        $(elem).find('input').data('original-value', $(elem).find('input').val());
                    });
                }
            });
        });

        $('alert-success').fadeOut(3000);
    });
</script>

@endsection