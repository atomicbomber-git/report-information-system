@extends('layouts.admin')

@section('title', 'Tambahkan Siswa Baru')
@section('content')

<p class="h1">
    <i class="fa fa-plus"></i>
    Tambahkan Siswa Baru
</p>

<hr>

<form method="POST" style="max-width: 400px">
    @csrf
    <div class="form-group">
        <label for="name"> Nama Asli </label>
        <input id="name" name="name" type="text" class="form-control">
    </div>
    <div class="form-group">
        <label for="username"> Nama Pengguna Akun </label>
        <input id="username" name="username" type="text" class="form-control">
    </div>
    <div class="form-group">
        <label for="student_id"> Nomor Induk </label>
        <input id="student_id" name="student_id" type="text" class="form-control">
    </div>
    <div class="form-group">
        <label for="sex"> Jenis Kelamin </label>

        {{--  Options for sexes  --}}
        <div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="sex" id="sex_male_option" value="male">
                <label class="form-check-label" for="sex_male_option"> Pria </label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="sex" id="sex_female_option" value="female">
                <label class="form-check-label" for="sex_female_option"> Wanita </label>
            </div>
        </div>
        
    </div>
    <div class="form-group">
        <label for="birthplace"> Tempat Lahir </label>
        <input id="birthplace" name="birthplace" type="text" class="form-control">
    </div>
    <div class="form-group">
        <label for="birthdate"> Tanggal Lahir </label>
        <input id="birthdate" name="birthdate" type="date" class="form-control">
    </div>
    <div class="form-group">
        <label for="religion"> Agama </label>
        <select name="religion" id="religion" class="form-control">
            <option value="Islam"> Islam </option>
            <option value="Katolik"> Katolik </option>
            <option value="Protestan"> Protestan </option>
            <option value="Hindu"> Hindu </option>
            <option value="Buddha"> Buddha </option>
            <option value="Kong Hu Cu"> Kong Hu Cu </option>
        </select>
    </div>

    <div class="form-group">
        <label for="address"> Alamat </label>
        <textarea name="address" id="address" cols="10" rows="4" class="form-control"></textarea>
    </div>

    <div class="form-group">
        <label for="phone"> Nomor Telefon  </label>
        <input id="phone" name="phone" type="phone" class="form-control">
    </div>

    <div style="height: 15px"> </div>

    <div class="form-group text-right">
        <button class="btn btn-primary">
            <i class="fa fa-plus"></i>
            Tambahkan
        </button>
    </div>
</form>
@endsection