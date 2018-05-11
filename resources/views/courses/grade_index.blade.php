@extends('layouts.admin')

@section('title', 'Kelola Mata Pelajaran')


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
    Kelola Guru Mata Pelajaran Kelas {{ $information->grade }} Tahun Ajaran {{ $information->term->code }}
</p>

<div class="container" style="padding: 1.2rem 0rem 1.2rem 0rem">
    <div class="row">
        <div class="col col-md-3 text-left">
            <a href="{{ route('courses.term_index') }}" class="btn btn-sm btn-secondary">
                <i class="fa fa-arrow-left"></i>
                Kembali
            </a>
        </div>
        <div class="col-md-6">
        </div>
        <div class="col col-md-3 text-right">
            <a href="{{ route('courses.add', [$information->term->id, $information->grade]) }}" class="btn btn-sm btn-primary">
                Tambahkan Mata Pelajaran Baru
                <i class="fa fa-plus"></i>
            </a>
        </div>
    </div>
</div>

<table class="table table-sm table-striped">
    <thead class="thead-dark">
        <tr>
            <th> No. </th>
            <th> Mata Pelajaran </th>
            <th> Kendali </th>
        </tr>
    </thead>

    <tbody>
        @foreach($courses as $course)
        <tr>
            <td> {{ $loop->iteration }}. </td>
            <td> {{ $course->name }} </td>
            <td>
                <a href="{{ route('courses.detail', ['term_id' => $information->term->id, 'grade' => $information->grade, 'course_id' => $course->id]) }}" class="btn btn-sm btn-dark">
                    Detail
                    <i class="fa fa-list-alt"></i>
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<hr>

@if( session('message-success') )
    <div class="alert alert-success">
        {{ session('message-success') }}
    </div>
@endif

@endsection

@section('script')

<div id="notification-container" style="position: fixed; bottom: 3rem; right: 3rem"></div>

<script src="{{ asset('js/notification.js') }}"></script>


@endsection