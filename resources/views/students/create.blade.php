@extends('layouts.admin')

@section('title', 'Tambahkan Siswa Baru')
@section('content')

<p class="h1">
    <i class="fa fa-plus"></i>
    Tambahkan Siswa Baru
</p>

<hr/>

<div class="container" style="padding: 0.6rem 0rem 0.6rem 0rem">
    <div class="row">
        <div class="col col-md-3 text-left">
            <a href="{{ route('students.index') }}" class="btn btn-sm btn-secondary">
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

<form method="POST" style="max-width: 400px">
    @csrf
    <div class="form-group">
        <label for="name"> Nama Asli: </label>
        <input id="name" name="name" type="text" 
            value="{{ old('name') }}"
            class="form-control {{ !$errors->has('name') ?: 'is-invalid' }}">
        <div class="invalid-feedback">
            {{ $errors->first('name') }}
        </div>
    </div>

    <div class="form-group">
        <label for="username"> Nama Pengguna Akun: </label>
        <input id="username" name="username" type="text"
            value="{{ old('username') }}"
            class="form-control {{ !$errors->has('username') ?: 'is-invalid' }}">
        <div class="invalid-feedback">
            {{ $errors->first('username') }}
        </div>
    </div>

    <div class="form-group">
        <label for="student_id"> Nomor Induk Siswa: </label>
        <input id="student_id" name="student_id" type="text"
            value="{{ old('student_id') }}"
            class="form-control {{ !$errors->has('student_id') ?: 'is-invalid' }}">
        <div class="invalid-feedback">
            {{ $errors->first('student_id') }}
        </div>
    </div>

    <div class="alert alert-warning">
        <i class="fa fa-warning"></i>
        Jika kata sandi dikosongkan, maka kata sandi akan 
        dianggap sama dengan nama pengguna
    </div>

    <div class="form-group">
        <label for="password"> Kata Sandi: </label>
        <input type="password" id="password" name="password"
            class="form-control {{ !$errors->has('password') ?: 'is-invalid' }}">
        <div class="invalid-feedback">
            {{ $errors->first('password') }}
        </div>
    </div>

    <div class="form-group">
        <label for="password_confirmation"> Ulangi Kata Sandi: </label>
        <input type="password" id="password_confirmation" name="password_confirmation"
            class="form-control {{ !$errors->has('password_confirmation') ?: 'is-invalid' }}">
        <div class="invalid-feedback">
            {{ $errors->first('password_confirmation') }}
        </div>
    </div>

    <hr/>

    <div class='form-group'>
        <label for='current_grade'> Jenjang: </label>
        <select name='current_grade' id='current_grade' class='form-control'>
            @foreach($grades as $grade)
            <option {{ old('current_grade') !== $grade ?: 'selected' }} value='{{ $grade }}'> {{ $grade }} </option>
            @endforeach
        </select>
        <div class='invalid-feedback'>
            {{ $errors->first('current_grade') }}
        </div>
    </div>

    <div class='form-group'>
        <label for='sex'> Jenis Kelamin: </label>
        <select name='sex' id='sex' class='form-control'>
            @foreach(\App\Student::SEXES as $sex_id => $sex_caption)
            <option {{ old('sex') !== $sex_id ?: 'selected' }} value='{{ $sex_id }}'> {{ $sex_caption }} </option>
            @endforeach
        </select>
        <div class='invalid-feedback'>
            {{ $errors->first('sex') }}
        </div>
    </div>

    <div class='form-group'>
        <label for='alma_mater'> Sekolah Asal: </label>
    
        <input
            id='alma_mater' name='alma_mater' type='text'
            value='{{ old('alma_mater') }}'
            class='form-control {{ !$errors->has('alma_mater') ?: 'is-invalid' }}'>
    
        <div class='invalid-feedback'>
            {{ $errors->first('alma_mater') }}
        </div>
    </div>

    <div class='form-group'>
        <label for='acceptance_date'> Tanggal Penerimaan: </label>
    
        <input
            id='acceptance_date' name='acceptance_date' type='text'
            value='{{ old('acceptance_date') }}'
            class='form-control {{ !$errors->has('acceptance_date') ?: 'is-invalid' }}'>
    
        <div class='invalid-feedback'>
            {{ $errors->first('acceptance_date') }}
        </div>
    </div>

    <div class='form-group'>
        <label for='birthplace'> Tempat Lahir: </label>
    
        <input
            id='birthplace' name='birthplace' type='text'
            value='{{ old('birthplace') }}'
            class='form-control {{ !$errors->has('birthplace') ?: 'is-invalid' }}'>
    
        <div class='invalid-feedback'>
            {{ $errors->first('birthplace') }}
        </div>
    </div>

    <div class='form-group'>
        <label for='birthdate'> Tanggal Lahir: </label>
    
        <input
            id='birthdate' name='birthdate' type='date'
            value='{{ old('birthdate') }}'
            class='form-control {{ !$errors->has('birthdate') ?: 'is-invalid' }}'>
    
        <div class='invalid-feedback'>
            {{ $errors->first('birthdate') }}
        </div>
    </div>

    <div class='form-group'>
        <label for='religion'> Agama: </label>
        <select name='religion' id='religion' class='form-control'>
            @foreach(\App\Student::RELIGIONS as $religion_id => $religion_caption)
            <option {{ old('religion') !== $religion_id ?: 'selected' }} value='{{ $religion_id }}'> {{ $religion_caption }} </option>
            @endforeach
        </select>
        <div class='invalid-feedback'>
            {{ $errors->first('religion') }}
        </div>
    </div>

    <div class='form-group'>
        <label for='address'> Alamat: </label>
    
        <textarea
            id='address' name='address'
            class='form-control {{ !$errors->has('address') ?: 'is-invalid' }}'
            col='30' row='6'
            >{{ old('address') }}</textarea>
    
        <div class='invalid-feedback'>
            {{ $errors->first('address') }}
        </div>
    </div>

    <div class='form-group'>
        <label for='phone'> Nomor Telepon Rumah: </label>
    
        <input
            id='phone' name='phone' type='phone'
            value='{{ old('phone') }}'
            class='form-control {{ !$errors->has('phone') ?: 'is-invalid' }}'>
    
        <div class='invalid-feedback'>
            {{ $errors->first('phone') }}
        </div>
    </div>

    <div class='form-group'>
        <label for='nth_child'> Anak Ke-: </label>
    
        <input
            id='nth_child' name='nth_child' type='number'
            value='{{ old('nth_child') }}'
            class='form-control {{ !$errors->has('nth_child') ?: 'is-invalid' }}'>
    
        <div class='invalid-feedback'>
            {{ $errors->first('nth_child') }}
        </div>
    </div>

    <div class='form-group'>
        <label for='father_name'> Nama Ayah: </label>
    
        <input
            id='father_name' name='father_name' type='text'
            value='{{ old('father_name') }}'
            class='form-control {{ !$errors->has('father_name') ?: 'is-invalid' }}'>
    
        <div class='invalid-feedback'>
            {{ $errors->first('father_name') }}
        </div>
    </div>

    <div class='form-group'>
        <label for='father_occupation'> Pekerjaan Ayah: </label>
    
        <input
            id='father_occupation' name='father_occupation' type='text'
            value='{{ old('father_occupation') }}'
            class='form-control {{ !$errors->has('father_occupation') ?: 'is-invalid' }}'>
    
        <div class='invalid-feedback'>
            {{ $errors->first('father_occupation') }}
        </div>
    </div>

    <div class='form-group'>
        <label for='mother_name'> Nama Ibu: </label>
    
        <input
            id='mother_name' name='mother_name' type='text'
            value='{{ old('mother_name') }}'
            class='form-control {{ !$errors->has('mother_name') ?: 'is-invalid' }}'>
    
        <div class='invalid-feedback'>
            {{ $errors->first('mother_name') }}
        </div>
    </div>

    <div class='form-group'>
        <label for='mother_occupation'> Pekerjaan Ibu: </label>
    
        <input
            id='mother_occupation' name='mother_occupation' type='text'
            value='{{ old('mother_occupation') }}'
            class='form-control {{ !$errors->has('mother_occupation') ?: 'is-invalid' }}'>
    
        <div class='invalid-feedback'>
            {{ $errors->first('mother_occupation') }}
        </div>
    </div>

    <div class='form-group'>
        <label for='parent_address'> Alamat Orang Tua: </label>
    
        <textarea
            id='parent_address' name='parent_address'
            class='form-control {{ !$errors->has('parent_address') ?: 'is-invalid' }}'
            col='30' row='6'
            >{{ old('parent_address') }}</textarea>
    
        <div class='invalid-feedback'>
            {{ $errors->first('parent_address') }}
        </div>
    </div>

    <div class='form-group'>
        <label for='guardian_name'> Nama Wali: </label>
    
        <input
            id='guardian_name' name='guardian_name' type='text'
            value='{{ old('guardian_name') }}'
            class='form-control {{ !$errors->has('guardian_name') ?: 'is-invalid' }}'>
    
        <div class='invalid-feedback'>
            {{ $errors->first('guardian_name') }}
        </div>
    </div>

    <div class='form-group'>
        <label for='parents_phone'> Nomor Telepon Orang Tua: </label>
    
        <input
            id='parents_phone' name='parents_phone' type='phone'
            value='{{ old('parents_phone') }}'
            class='form-control {{ !$errors->has('parents_phone') ?: 'is-invalid' }}'>
    
        <div class='invalid-feedback'>
            {{ $errors->first('parents_phone') }}
        </div>
    </div>

    <div class='form-group'>
        <label for='guardian_occupation'> Pekerjaan Wali: </label>
    
        <input
            id='guardian_occupation' name='guardian_occupation' type='text'
            value='{{ old('guardian_occupation') }}'
            class='form-control {{ !$errors->has('guardian_occupation') ?: 'is-invalid' }}'>
    
        <div class='invalid-feedback'>
            {{ $errors->first('guardian_occupation') }}
        </div>
    </div>

    <div class='form-group'>
        <label for='guardian_address'> Alamat Wali: </label>
    
        <textarea
            id='guardian_address' name='guardian_address'
            class='form-control {{ !$errors->has('guardian_address') ?: 'is-invalid' }}'
            col='30' row='6'
            >{{ old('guardian_address') }}</textarea>
    
        <div class='invalid-feedback'>
            {{ $errors->first('guardian_address') }}
        </div>
    </div>

    <div class='form-group'>
        <label for='guardian_phone'> Nomor Telepon Wali: </label>
    
        <input
            id='guardian_phone' name='guardian_phone' type='phone'
            value='{{ old('guardian_phone') }}'
            class='form-control {{ !$errors->has('guardian_phone') ?: 'is-invalid' }}'>
    
        <div class='invalid-feedback'>
            {{ $errors->first('guardian_phone') }}
        </div>
    </div>

    <div style="height: 15px"> </div>

    <div class="form-group text-right">
        <button class="btn btn-primary btn-sm">
            <i class="fa fa-plus"></i>
            Tambahkan
        </button>
    </div>
</form>
@endsection