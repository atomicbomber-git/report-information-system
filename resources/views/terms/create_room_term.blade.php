@extends('layouts.admin')

@section('title', 'Tambahkan Kelas Baru')
@section('content')

<p class="h1">
    <i class="fa fa-plus"></i>
    Tambahkan Kelas Baru
</p>

<hr>

<form method="POST" action="{{ route('room_terms.create', $term_id) }}" style="max-width: 400px">
    @csrf
    <div class="form-group">
        <label for="room_id"> Nama Kelas </label>
        <select id="room_select" name="room_id" id="room_id" class="form-control">
        @foreach($vacant_rooms as $vacant_room)
        <option value="{{ $vacant_room->id }}" data-evenodd="{{ $vacant_room->even_odd }}">
            Kelas {{ $vacant_room->name }}, Semester {{ \App\RoomTerm::EVEN_ODD[$vacant_room->even_odd] }}
        </option>
        @endforeach
        </select>
    </div>

    <input type="hidden" name="even_odd">

    <div class="form-group">
        <label for="teacher_id"> Wali Kelas </label>
        <select name="teacher_id" id="teacher_id" class="form-control">
        @foreach($teachers as $teacher)
        <option value="{{ $teacher->id }}">
            {{ $teacher->user->name }}
        </option>
        @endforeach
        </select>
    </div>

    <div style="height: 15px"> </div>

    <div class="form-group text-right">
        <a href="{{ route('terms.detail', $term_id) }}" class="btn btn-secondary">
            Kembali
        </a>
        <button class="btn btn-primary">
            <i class="fa fa-plus"></i>
            Tambahkan
        </button>
    </div>
</form>

@endsection

@section('script')
<script>
    $(document).ready(function() {
        
        $('input[name=even_odd]').val(
            $(this).find('option:selected').attr('data-evenodd')
        );
        
        $('#room_select').change(function() {
            $('input[name=even_odd]').val(
                $(this).find('option:selected').attr('data-evenodd')
            );
        });
    })
</script>
@endsection