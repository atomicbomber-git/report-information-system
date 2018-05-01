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
    Kelola Mata Pelajaran {{ $information->course->name }} Kelas {{ $information->grade }} Tahun Ajaran {{ $information->term->code }}
</p>

<table class="table table-sm table-striped">
    <thead class="thead-dark">
        <tr>
            <th> No. </th>
            <th> Kompetensi Dasar </th>
            <th> Kendali </th>
        </tr>
    </thead>

    <tbody>
        @foreach($basic_competencies as $basic_competency)
        <tr>
            <td> {{ $loop->iteration }}. </td>
            <td> {{ $basic_competency->name }} </td>
            <td> </td>
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