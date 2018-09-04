@extends('layouts.admin')
@section('title', "Seluruh Tahun Ajaran")
@section('content')

<h1>
    <i class="fa fa-list"></i>
    Daftar Seluruh Tahun Ajaran
</h1>

@if( session('message-success') )
    <div class="alert alert-success">
        {{ session('message-success') }}
    </div>
@endif

<hr>

<table class='table table-striped table-responsive-xl table-sm'>
    <thead class='thead-dark'>
        <tr>
            <th> # </th>
            <th> Tahun Ajaran </th>
            <th> Semester </th>
        </tr>
    </thead>

    <tbody>
        @foreach ($terms as $code => $semesters)
        <tr>
            <td> {{ $loop->iteration }}. </td>
            <td> {{ $code }} </td>
            <td>
                @foreach ($semesters as $semester)
                <a href="{{ route('headmaster_access.room_terms', [$semester->term_id, $semester->even_odd]) }}" class="btn btn-dark btn-sm">
                    {{ \App\RoomTerm::EVEN_ODD[$semester->even_odd] }}
                    <i class="fa fa-list"></i>
                </a>
                @endforeach
            </td>
        @endforeach
    </tbody>
</table>
@endsection