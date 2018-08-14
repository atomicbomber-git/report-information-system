@extends('layouts.admin')

@section('title', "Kelola Ekstrakurikuler Tahun Ajaran $term->code")

@section('content')

<h1>
    <i class="fa fa-list"></i>
    Kelola Ekstrakurikuler
</h1>

<p class="lead">
    Tahun Ajaran {{ $term->code }}
</p>

<hr/>

@if( session('message-success') )
    <div class="alert alert-success">
        {{ session('message-success') }}
    </div>
@endif

<div class="container">
    <div class="card" style="width: 20rem">
        <div class="card-header">
            <i class="fa fa-plus"></i>
            Tambahkan Ekstrakurikuler Baru
        </div>

        <div class="card-body">
            <form
                method='POST'
                action='{{ route('extracurriculars.create', $term->id) }}'>

                @csrf
                
                <div class='form-group'>
                    <label for='name'> Nama: </label>
                
                    <input
                        id='name' name='name' type='text'
                        value='{{ old('name') }}'
                        class='form-control {{ !$errors->has('name') ?: 'is-invalid' }}'>
                
                    <div class='invalid-feedback'>
                        {{ $errors->first('name') }}
                    </div>
                </div>

                <div class="text-right">
                    <button class="btn btn-primary btn-sm">
                        Tambahkan
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
            
            </form>
        </div>
    </div>

    <h2>
        <i class="fa fa-list"></i>
        Daftar Ekstrakurikuler
    </h2>

    <table class="table table-striped table-responsive-xl table-sm">
        <thead class="thead thead-dark">
            <tr>
                <th> # </th>
                <th> Nama Ekstrakurikuler </th>
                <th> Kendali </th>
            </tr>
        </thead>

        <tbody>
            @foreach ($extracurriculars as $extracurricular)
            <tr>
                <td> {{ $loop->iteration }}. </td>
                <td> {{ $extracurricular->name }} </td>
                <td>

                    <a href="{{ route('extracurriculars.edit', $extracurricular->id) }}" class="btn btn-dark btn-sm">
                        Sunting
                        <i class="fa fa-pencil"></i>
                    </a>

                    <form
                        class="d-inline-block form-delete"
                        method="POST"
                        action="{{ route('extracurriculars.delete', $extracurricular->id) }}"
                        data-label='{{ $extracurricular->name }}'>
                        
                        @csrf
                        <button class="btn btn-danger btn-sm">
                            Hapus
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection

@section('script')
<script src="{{ asset('js/sweetalert.min.js') }}"> </script>
<script>
    $(document).ready(function() {
        $(".form-delete").each(function() {
            let form = $(this);
            form.submit(function(e) {
                e.preventDefault()

                let label = form.data('label');

                swal('Anda yakin ingin menghapus ekstrakurikuler ' + label + '?', {
                    title: "Konfirmasi Penghapusan",
                    icon: "warning",
                    buttons: ["Tidak", "Ya"],
                    dangerMode: true
                })
                .then(function(willDelete) {
                    if (willDelete) {
                        form.off("submit").submit();
                    }
                });
            });
        });

        $('.alert-success').fadeOut(3000);
    });
</script>
@endsection