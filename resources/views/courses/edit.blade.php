@extends('layouts.admin')

@section('title', 'Sunting Mata Pelajaran')


@section('styles')
<style>
    .container-course {
        width: 30rem;
    }
</style>
@endsection

@section('content')

<h1>
    <i class="fa fa-pencil"></i>
    Sunting Mata Pelajaran {{ $course->name }}
</h1>

<p class="lead">
    Tahun Ajaran {{ $course->term->code }}
</p>

@if( session('message-success') )
    <div class="alert alert-success">
        {{ session('message-success') }}
    </div>
@endif

<div class="container" style="padding: 0.6rem 0rem 0.6rem 0rem">
    <div class="row">
        <div class="col col-md-3 text-left">
            <a href="{{ route('courses.grade_index', [$course->term_id, $course->grade]) }}" class="btn btn-sm btn-secondary">
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

<div class="container" style="padding: 0.6rem 0rem 0.6rem 0rem">
    <div style="max-width: 18rem">
            <form
            method='POST'
            action='{{ route('courses.edit', $course->id) }}'
            >
        
            @csrf
            
            <div class='form-group'>
                <label for='name'> Nama Mata Pelajaran: </label>
            
                <input
                    id='name' name='name' type='text'
                    value='{{ old('name', $course->name) }}'
                    class='form-control {{ !$errors->has('name') ?: 'is-invalid' }}'>
            
                <div class='invalid-feedback'>
                    {{ $errors->first('name') }}
                </div>
            </div>
    
            <div class='form-group'>
                <label for='group'> Kelompok: </label>
                <select name='group' id='group' class='form-control'>
                    @foreach(\App\Course::groups() as $group)
                    <option {{ old('group', $course->group) !== $group ?: 'selected' }} value='{{ $group }}'> {{ $group }} </option>
                    @endforeach
                </select>
                <div class='invalid-feedback'>
                    {{ $errors->first('group') }}
                </div>
            </div>
    
            <div class='form-group'>
                <label for='type'> Tipe: </label>
                <select name='type' id='type' class='form-control'>
                    @foreach(\App\Course::types() as $key => $value)
                    <option {{ old('type', $course->type) !== $key ?: 'selected' }} value='{{ $key }}'> {{ $value }} </option>
                    @endforeach
                </select>
                <div class='invalid-feedback'>
                    {{ $errors->first('type') }}
                </div>
            </div>
    
            <div class="text-right">
                <button class="btn btn-primary btn-sm">
                    Perbarui
                    <i class="fa fa-pencil"></i>
                </button>
            </div>
        </form>
    </div>
</div>

@endsection