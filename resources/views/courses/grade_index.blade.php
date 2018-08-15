@extends('layouts.admin')

@section('title', "Kelola Mata Pelajaran Kelas $information->grade Tahun Ajaran " . $information->term->code)


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

@if( session('message-success') )
    <div class="alert alert-success">
        {{ session('message-success') }}
    </div>
@endif

<hr>

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
                
                <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-dark btn-sm">
                    Sunting
                    <i class="fa fa-pencil"></i>
                </a>

                {{-- COURSE DELETE button --}}
                <form
                    method="POST"
                    action="{{ route('courses.delete', ['course' => $course->id]) }}"
                    class="form-delete d-inline-block"
                    data-coursename="{{ $course->name }}">
                    @csrf
                    <button class="btn btn-sm btn-danger">
                        Hapus
                        <i class="fa fa-trash"></i>
                    </button>
                </form>

            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<hr>

@endsection

@section('script')

<div id="notification-container" style="position: fixed; bottom: 3rem; right: 3rem"></div>

<script src="{{ asset('js/notification.js') }}"></script>
<script src="{{ asset('js/sweetalert.min.js') }}"> </script>

<script>
    $(document).ready(function() {
        $(".form-delete").each(function() {
            let form = $(this);
            form.submit(function(e) {
                e.preventDefault()

                let coursename = form.data('coursename');

                swal(`Anda yakin ingin menghapus mata pelajaran ${coursename}?`, {
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
    });
</script>

@endsection