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

@if( session('message-success') )
    <div class="alert alert-success">
        {{ session('message-success') }}
    </div>
@endif

<div class="container" style="padding: 0.6rem 0rem 0.6rem 0rem">
    <div class="row">
        <div class="col col-md-3 text-left">
            <a href="{{ route('courses.grade_index', [$information->term->id, $information->grade]) }}" class="btn btn-sm btn-secondary">
                <i class="fa fa-arrow-left"></i>
                Kembali
            </a>
        </div>
        <div class="col-md-6">
        </div>
        <div class="col col-md-3 text-right">
        </div>
    </div>
</div>

<hr/>

<fieldset>
    <legend>
        <i class="fa fa-plus"></i>
        Tambahkan Kompetensi Dasar Baru
    </legend>
    
    <div style="max-width: 25rem">
        <form method="POST" action="{{ route('courses.knowledge_basic_competency.create', $information->course->id) }}">
            @csrf

            <div class="form-group">
                <label for="name"> Nama Kompetensi Dasar: </label>
                <input required id="name" name="name" type="text" class="form-control">
            </div>

            <div class="form-group">
                <label for="even_odd"> Semester Ganjil / Genap: </label>

                <select required name="even_odd" id="even_odd" class="form-control">
                    <option value="odd"> Ganjil </option>
                    <option value="even"> Genap </option>
                </select>
            </div>

            <div class="form-group text-right">
                <button class="btn btn-primary btn-sm">
                    Tambahkan
                    <i class="fa fa-plus"></i>
                </button>
            </div>
        </form>
    </div>

</fieldset>

<hr/>

<div style="height: 2rem"></div>

<table class="table table-sm table-striped">
    <thead class="thead-dark">
        <tr>
            <th> No. </th>
            <th> Kompetensi Dasar </th>
            <th> Semester </th>
            <th> Kendali </th>
        </tr>
    </thead>

    <tbody>
        @foreach($basic_competencies as $basic_competency)
        <tr>
            <td> {{ $loop->iteration }}. </td>
            <td> {{ $basic_competency->name }} </td>
            <td> {{ \App\Util::EVEN_ODD[$basic_competency->even_odd] }} </td>
            <td>
                <a
                    class="btn btn-sm btn-dark"
                    href="{{ route('courses.knowledge_basic_competency.edit', [$information->course->id, $basic_competency->id]) }}">
                    Sunting
                    <i class="fa fa-pencil"></i>
                </a>

                <form
                    method="POST"
                    action="{{ route('courses.knowledge_basic_competency.delete', $basic_competency->id) }}"
                    class="form-delete d-inline-block"
                    data-basiccompetency="{{ $basic_competency->name }}">
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

                let basic_competency = form.data('basiccompetency');

                swal(`Anda yakin ingin menghapus kompetensi dasar ${basic_competency}?`, {
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