@extends('layouts.admin')

@section('title', 'Seluruh Tahun Ajaran')


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
    Kelola Mata Pelajaran Kelas {{ $information->grade }} Tahun Ajaran {{ $information->term->code }}
</p>

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

<script>
    
</script>

@endsection