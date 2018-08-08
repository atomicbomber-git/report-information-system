@extends('layouts.admin')

@section('title', 'Detail Kelas')
@section('content')

@section("styles")
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap4.min.css') }}">

    <style>
        #table {
            border-collapse: collapse !important;
        }
    </style>
@endsection

<p class="h2">
    <i class="fa fa-plus"></i>
    Detail Kelas {{ $room_term->name }} Tahun Ajaran {{ $room_term->code }} Semester {{ $room_term->even_odd }}
</p>

<hr>

@if( session('message-success') )
    <div class="message alert alert-success">
        {{ session('message-success') }}
    </div>

    <script>
        window.setTimeout(function() {
            $('.message').fadeOut();
        }, 3000);
    </script>
@endif

<div class="container" style="padding: 0.6rem 0rem 0.6rem 0rem">
    <div class="row">
        <div class="col col-md-3 text-left">
            <a href="{{ route('terms.detail', $room_term->term_id) }}" class="btn btn-sm btn-secondary">
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

<hr>

<div class="card" style="max-width: 400px">
    <div class="card-body">
        <h4 class="card-title">
            Ubah Wali Kelas
        </h4>

        <hr/>

        <form method="POST" action="{{ route('room_terms.update', $room_term) }}">
            @csrf
            <div class="form-group">
                <label for="teacher_id"> Wali Kelas </label>
                <select name="teacher_id" id="teacher_id" class="form-control">
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}" @if($teacher->id === $room_term->teacher_id) selected="true" @endif>
                        {{ $teacher->name }} ({{ $teacher->teacher_id }})
                    </option>
                @endforeach
                </select>
            </div>
        
            <div style="height: 15px"> </div>
        
            <div class="form-group text-right">
                <button class="btn btn-primary btn-sm">
                    <i class="fa fa-pencil"></i>
                    Ubah
                </button>
            </div>
        </form>
    </div>
</div>

<div style="height: 40px"></div>

<div class="card">
    <div class="card-body">
        <h4 class="card-title">
            Kelola Data Siswa
            <hr/>
        </h4>

        <div class="row">
            <div class="col col-md-3"></div>
            <div class="col col-md-6"></div>
            <div class="col col-md-3 text-right">
                <a class="btn btn-primary btn-sm" href="{{ route('reports.create', $room_term) }}">
                    Tambahkan Siswa Baru <i class="fa fa-plus"></i>
                </a>
            </div>
        </div>

        <h4> Daftar Siswa dalam Kelas </h4>

        <table id="table" class="table table-sm table-striped">
            <thead class="thead-dark">
                <tr>
                    <th> # </th>
                    <th> Nama Siswa </th>
                    <th> NISN </th>
                    <th> Kendali </th>
                </tr>
            </thead>

            <tbody>
            @foreach($reports as $report)
                <tr>
                    <td> {{ $loop->iteration }}. </td>
                    <td> {{ $report->name }} </td>
                    <td> {{ $report->student_id }} </td>
                    <td>
                        <a href="{{ route('reports.detail', $report) }}" class="btn btn-dark btn-sm">
                            Nilai
                            <i class="fa fa-list-alt"></i>
                        </a>
                        <a class="btn btn-edit btn-dark btn-sm"
                            href="{{ route('reports.move', $report) }}"
                            >
                            Pindah Kelas
                            <i class="fa fa-pencil"></i>
                        </a>

                        {{-- DELETE STUDENT buttton --}}
                        <form
                            method = "POST"
                            action = "{{ route('reports.delete', $report) }}"
                            class = "form-delete d-inline-block"
                            data-studentname = "{{ $report->name }}">
                            
                            @csrf
                            <button class="btn btn-danger btn-sm">
                                <i class="fa fa-trash"></i>
                                Hapus
                            </button>
                        </form>

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('script')
<script src="{{ asset('js/sweetalert.min.js') }}"> </script>
<script src="{{ asset('js/jquery.dataTables.js') }}"> </script>
<script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"> </script>

<script>
        // DataTable
        $(".table").DataTable({
            "language": {
                "url": "{{ asset("Indonesian.json") }}"
            },
            "pagingType": "full",
            "lengthMenu": [20, 40],
            "pageLength": 20,
            "columnDefs": [
                { orderable: false },
                { orderable: true },
                { orderable: true },
                { orderable: false }
            ]
        });

        $(".form-delete").each(function() {
            let form = $(this);
            form.submit(function(e) {
                e.preventDefault()

                let student_name = form.data('studentname');

                swal(`Anda yakin ingin menghapus berkas siswa ${student_name}?`, {
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

    </script>
@endsection