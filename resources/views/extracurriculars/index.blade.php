@extends('layouts.admin')

@section('title', "Kelola Ekstrakurikuler")

@section('content')

<h1>
    <i class="fa fa-list"></i>
    Kelola Ekstrakurikuler
</h1>

<hr/>

@if( session('message-success') )
    <div class="alert alert-success">
        {{ session('message-success') }}
    </div>
@endif

<div class="container">
    <table class="table table-striped table-sm">
        <thead class="thead thead-dark">
            <tr>
                <th> # </th>
                <th> Tahun Ajaran </th>
                <th> Kendali </th>
            </tr>
        </thead>

        <tbody>
            @foreach ($terms as $term)
            <tr>
                <td> {{ $loop->iteration }}. </td>
                <td> {{ $term->code }} </td>
                <td>
                    <a href="{{ route('extracurriculars.index_term', $term->id) }}" class="btn btn-dark btn-sm">
                        Detail
                        <i class="fa fa-list-alt"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection