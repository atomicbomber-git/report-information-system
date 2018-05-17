@extends('layouts.admin')

@section('title', 'Seluruh Laporan Nilai')

@section('content')

<p class="h1">
    <i class="fa fa-list"></i>
    Seluruh Laporan Nilai
</p>

<hr>

@if( session('message-success') )
    <div class="alert alert-success">
        {{ session('message-success') }}
    </div>
@endif


@endsection