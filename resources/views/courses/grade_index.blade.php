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