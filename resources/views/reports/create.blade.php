@extends('layouts.admin')

@section('title', 'Detail Kelas')
@section('content')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap4.min.css') }}">
@endsection

<p class="h1">
    <i class="fa fa-plus"></i>
    Tambahkan Siswa ke Kelas
</p>

<hr>

<div class="container" style="padding: 0.6rem 0rem 0.6rem 0rem">
    <div class="row">
        <div class="col col-md-3 text-left">
            <a href="{{ route('room_terms.detail', $room_term) }}" class="btn btn-sm btn-secondary">
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

<table style="border-collapse: collapse !important" class="table table-sm table-striped">
    <thead class="thead-dark">
        <tr>
            <th> Masukkan? </th>
            <th> Nama Siswa </th>
            <th> NISN </th>
        </tr>
    </thead>

    <tbody>
    @foreach($students as $student)
        <tr>
            <td style="vertical-align: middle">
                <input class="student_checkbox form-control" data-id="{{ $student->id }}" type="checkbox">
            </td>
            <td> {{ $student->name }} </td>
            <td> {{ $student->student_id }} </td>
        </tr>
    @endforeach
    </tbody>
</table>

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

@endsection

@section('script')
<script src="{{ asset('js/sweetalert.min.js') }}"> </script>
<script src="{{ asset('js/jquery.dataTables.js') }}"> </script>
<script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"> </script>

<script>
    $(document).ready(function() {

        let picked_students = [];

        // DataTable
        $(".table").DataTable({
            "language": {
                "url": "{{ asset("Indonesian.json") }}"
            },
            "pagingType": "full",
            "lengthMenu": [20, 40],
            "pageLength": 20
        });
        
        // Handle checkbox checking / unchecking
        $('.student_checkbox').change(function() {
            if (this.checked) {
                // Hightlight row

                picked_students.push($(this).data('id'));
                $(this).parent().parent().addClass('table-primary');
            }
            else {

                picked_students = picked_students.filter((id) => {
                    return id !== $(this).data('id');
                });

                // Unhighlight row
                $(this).parent().parent().removeClass('table-primary');
            }
            console.log(picked_students);

        });

        // Handle submission
        $('button#submit').click(function() {
            $.post({
                url: '{{ route('reports.create', $room_term) }}',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'student_ids': picked_students
                },
                success: function(data, status) {
                    alert(data);
                }
            });
        });
    });
</script>
@endsection