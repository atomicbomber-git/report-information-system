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
    Sunting Kompetensi Dasar {{ $basic_competency->name }}
    Mata Pelajaran {{ $basic_competency->course->name }}
    Tahun Ajaran {{ $basic_competency->course->term->code }}
</p>

@if( session('message-success') )
    <div class="alert alert-success">
        {{ session('message-success') }}
    </div>
@endif

<div class="container" style="padding: 0.6rem 0rem 0.6rem 0rem">
    <div class="row">
        <div class="col col-md-3 text-left">
            <a
                href="{{ route('courses.detail', [$basic_competency->course->term->id, $basic_competency->course->grade, $basic_competency->course->id]) }}"
                class="btn btn-sm btn-secondary"
                >

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

<div style="max-width: 25rem">
    <form
        method="POST"
        action="{{ route('courses.knowledge_basic_competency.edit', [$basic_competency->course->id, $basic_competency->id]) }}"
        >
        @csrf

        <div class="form-group">
            <label for="name"> Nama Kompetensi Dasar: </label>
            <input
                value="{{ old('name', $basic_competency->name) }}"
                id="name" name="name" type="text" class="form-control">
        </div>

        {{-- <div class="form-group">
            <label for="even_odd"> Semester Ganjil / Genap: </label>

            <select required name="even_odd" id="even_odd" class="form-control">
                <option {{ $basic_competency->even_odd == 'odd' ? 'selected=true' : '' }} value="odd"> Ganjil </option>
                <option {{ $basic_competency->even_odd == 'even' ? 'selected=true' : '' }} value="even"> Genap </option>
            </select>
        </div> --}}

        <div class="form-group text-right">
            <button class="btn btn-primary btn-sm">
                Perbarui
                <i class="fa fa-check"></i>
            </button>
        </div>
    </form>
</div>
@endsection