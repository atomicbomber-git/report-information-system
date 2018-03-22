@extends('layouts.admin')

@section('title', 'Detail Kelas')
@section('content')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap4.min.css') }}">
@endsection

<p class="h1">
    <i class="fa fa-plus"></i>
    Detail Nilai Mata Pelajaran ({{ $student->name }},  {{ $student->student_id }})
</p>

<hr>

<div class="container" style="padding: 0.6rem 0rem 0.6rem 0rem">
    <div class="row">
        <div class="col col-md-3 text-left">
            <a href="{{ route('reports.detail', $course_report->report_id) }}" class="btn btn-sm btn-secondary">
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


@if( session('message-success') )
    <div class="message alert alert-success">
        {{ session('message-success') }}
    </div>

    <script>
        window.setTimeout(function() {
            $('.message').fadeOut();
        }, 3000);
    </script>
@endif

@endsection

@section('script')
<script src="{{ asset('js/sweetalert.min.js') }}"> </script>
<script src="{{ asset('js/jquery.dataTables.js') }}"> </script>
<script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"> </script>

<script>
    $(document).ready(function() {
    });
</script>
@endsection