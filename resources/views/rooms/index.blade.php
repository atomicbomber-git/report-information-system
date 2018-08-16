@extends('layouts.admin')

@section('title', "Seluruh Ruangan")

@section('content')

<h1>
    <i class="fa fa-list"></i>
    Daftar Seluruh Ruangan
</h1>

@if( session('message-success') )
    <div class="alert alert-success">
        {{ session('message-success') }}
    </div>
@endif

<hr>

<div class="container">
    <div class="row">
        <div class="col"></div>
        <div class="col col-3"></div>
        <div class="col text-right">
            <a 
                class="btn btn-primary btn-sm"
                href="{{ route('rooms.create') }}">
                Tambah Ruangan Baru
                <i class="fa fa-plus"></i>
            </a>
        </div>
    </div>
</div>

<hr>

<table class='table table-sm table-striped'>
    <thead class='thead-dark'>
        <tr>
            <th> # </th>
            <th> Nama </th>
            <th> Jenjang </th>
            <th> Kendali </th>
        </tr>
    </thead>

    <tbody>
        @foreach ($rooms as $room)
        <tr>
            <td> {{ $loop->iteration }}. </td>
            <td> {{ $room->name }} </td>
            <td> {{ $room->grade }} </td>
            <td>
                <a href="{{ route('rooms.edit', $room) }}" class="btn btn-dark btn-sm">
                    Sunting
                    <i class="fa fa-pencil"></i>
                </a>

                <form
                    method="POST"
                    action="{{ route('rooms.delete', $room) }}"
                    class="form-delete d-inline-block"
                    data-roomname="{{ $room->name }}">
                    @csrf
                    <button class="btn btn-danger btn-sm">
                        Hapus
                        <i class="fa fa-trash"></i>
                    </button>
                </form>
                
            </td>
        @endforeach
    </tbody>
</table>
@endsection

@section("script")
<script src="{{ asset('js/sweetalert.min.js') }}"> </script>

<script>

    $(".form-delete").each(function() {
        let form = $(this);
        form.submit(function(e) {
            e.preventDefault()

            let room_name = form.data('roomname');

            swal(`Anda yakin ingin menghapus ruangan ${room_name}?`, {
                title: 'Konfirmasi Penghapusan',
                icon: 'warning',
                buttons: ['Tidak', 'Ya'],
                dangerMode: true
            })
            .then(function(willDelete) {
                if (willDelete) {
                    form.off('submit').submit();
                }
            });
        });
    });

    $('.alert-success').fadeOut(3000);
</script>
@endsection