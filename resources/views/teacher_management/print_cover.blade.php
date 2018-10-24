<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        Cover Laporan Hasil Belajar {{ $report->student->user->name }} ({{ $report->student->student_id }})
    </title>
    <link href="{{ asset('css/paper.css') }}" rel="stylesheet">
    <style>
        @page { size: A4 }
        
        h1.title, h2.title, h3.title, h4.title, h5.title {
            text-align: center;
            margin: 0.3rem;
        }

        div.container {
            margin: auto 4rem auto 4rem;
        }

        .box {
            padding: 0.3rem;
            border: thin solid black;
        }

        div.group {
            margin-top: 2.5rem;
            margin-bottom: 2.5rem;
        }

        img {
            text-align: center;
            display: block;
            width: 10rem;
            height: auto;
            margin: 9rem auto 9rem auto;
        }

        td.stretch {
            width: 100%;
        }

        .underlined {
            border-bottom: thin solid black;
        }

        .signature-box td:nth-child(2) {
            width: calc(100% - 8rem);
        }

    </style>
</head>
<body class="A4">
    <section class="sheet padding-10mm">
        <div class="container">
            <div class="group">
                <h1 class="title"> RAPOR </h1>
                <h1 class="title"> SEKOLAH MENENGAH PERTAMA </h1>
                <h1 class="title"> (SMP) </h1>
            </div>
            
    
            <img src="{{ asset('images/tut_wuri_handayani.png') }}">
    
            <div class="group">
                <h3 class="title"> Nama Peserta Didik: </h3>
                <h1 class="title box">
                    {{ strtoupper($report->student->user->name) }}
                </h1>
            </div>
            
            <div class="group">
                <h3 class="title"> NISN: </h3>
                <h1 class="title box">
                    {{ $report->student->student_id }}
                </h1>
            </div>
            
            <div class="group">
                <h1 class="title">
                    KEMENTERIAN PENDIDIKAN DAN KEBUDAYAAN REPUBLIK INDONESIA
                </h1>
            </div>
        </div>
    </section>

    <section class="sheet padding-10mm">
        <div class="container">
            <div class="group">
                <h1 class="title"> RAPOR </h1>
                <h1 class="title"> SEKOLAH MENENGAH PERTAMA </h1>
                <h1 class="title"> (SMP) </h1>
            </div>

            <div class="group">
                <table>
                    <tbody>
                        <tr> <td> Nama Sekolah </td> <td> : </td> <td class="stretch underlined"> SMP Negeri 16 Pontianak </td> </tr>
                        <tr> <td> NPSN </td> <td> : </td> <td class="stretch underlined"> 30105203 </td> </tr>
                        <tr> <td> NIS/NSS/NDS </td> <td> : </td> <td class="stretch underlined"> 20092220001153 </td> </tr>
                        <tr> <td> Alamat Sekolah </td> <td> : </td> <td class="stretch underlined"> JL. RE Martanidata Pontianak Kode Pos 78115 Telp. 0561-779598 </td> </tr>
                        <tr> <td> Desa/Kelurahan </td> <td> : </td> <td class="stretch underlined"> Sungai Jawi Dalam </td> </tr>
                        <tr> <td> Kecamatan </td> <td> : </td> <td class="stretch underlined"> Pontianak Barat </td> </tr>
                        <tr> <td> Kota/Kabupaten </td> <td> : </td> <td class="stretch underlined"> Kota Pontianak </td> </tr>
                        <tr> <td> Provinsi </td> <td> : </td> <td class="stretch underlined"> Kalimantan Barat </td> </tr>
                        <tr> <td> Website </td> <td> : </td> <td class="stretch underlined"> http://www.smpn16.dindikptk.net </td> </tr>
                        <tr> <td> E-Mail </td> <td> : </td> <td class="stretch underlined"> smpn16@dindikptk.net </td> </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section class="sheet padding-10mm">
        <div class="container">
            <div class="group">
                <h1 class="title"> IDENTITAS PESERTA DIDIK </h1>
            </div>

            <div class="group">
                <table>
                    <tbody>
                        @php
                            
                        @endphp
                        {{-- 1.--}}  <tr> <td> 1.       </td> <td colspan="2"> Nama Lengkap Peserta Didik </td>                 <td> : </td><td> <strong> {{ strtoupper($report->student->user->name) }} </strong> </td> </tr>
                        {{-- 2.--}}  <tr> <td> 2.       </td> <td colspan="2"> Nomor Induk/NISN </td>                   <td> : </td><td> {{ $report->student->student_id }} </td> </tr>
                        {{-- 3.--}}  <tr> <td> 3.       </td> <td colspan="2"> Tempat, Tanggal Lahir </td>                  <td> : </td><td> {{ $report->student->birthplace }}/{{ $report->student->birthdate }} </td> </tr>
                        {{-- 4.--}}  <tr> <td> 4.       </td> <td colspan="2"> Jenis Kelamin </td>                  <td> : </td><td> {{ $report->student->sex() }} </td> </tr>
                        {{-- 5.--}}  <tr> <td> 5.       </td> <td colspan="2"> Agama </td>                  <td> : </td><td> {{ $report->student->religion() }} </td> </tr>
                        {{-- 6.--}}  <tr> <td> 6.       </td> <td colspan="2"> Status dalam Keluarga </td>                  <td> : </td><td> {{ $report->student->status_in_family }} </td> </tr>
                        {{-- 7.--}}  <tr> <td> 7.       </td> <td colspan="2"> Anak ke </td>                    <td> : </td><td> {{ $report->student->nth_child }} </td> </tr>
                        {{-- 8.--}}  <tr> <td> 8.       </td> <td colspan="2"> Alamat Peserta didik </td>                   <td> : </td><td> {{ $report->student->address }} </td> </tr>
                        {{-- 9.--}}  <tr> <td> 9.       </td> <td colspan="2"> Nomor Telepon Rumah </td>                    <td> : </td><td> {{ $report->student->phone }} </td> </tr>
                        {{-- 10.--}} <tr> <td> 10.      </td> <td colspan="2"> Sekolah Asal (SD/MI) </td>                   <td> : </td><td> {{ $report->student->alma_mater }} </td> </tr>
                        {{-- 11.--}} <tr> <td> 11.      </td> <td colspan="2"> Diterima di SMP ini </td>                    <td> </td><td> </td> </tr>
                        {{-- __A--}} <tr> <td> {{----}} </td> <td            > a. </td> <td> Di Kelas </td> <td> : </td> <td> {{ $first_class }} </td> </tr>
                        {{-- __B--}} <tr> <td> {{----}} </td> <td            > b. </td> <td> Pada Tanggal </td> <td> : </td> <td> {{ $report->student->acceptance_date }} </td> </tr>
                        {{-- 12.--}} <tr> <td> 12.      </td> <td colspan="2"> Orang Tua </td> <td> </td> <td></td> <td></td> </tr>
                        {{-- __A--}} <tr> <td> {{----}} </td> <td            > a. </td> <td> Nama Ayah </td> <td> : </td> <td> {{ strtoupper($report->student->father_name) }} </td> </tr>
                        {{-- __B--}} <tr> <td> {{----}} </td> <td            > b. </td> <td> Nama Ibu </td> <td> : </td> <td> {{ strtoupper($report->student->mother_name) }} </td> </tr>
                        {{-- __C--}} <tr> <td> {{----}} </td> <td            > c. </td> <td> Alamat </td> <td> : </td> <td> {{ $report->student->parents_address }} </td> </tr>
                        {{-- __D--}} <tr> <td> {{----}} </td> <td            > d. </td> <td> Nomor Telp/HP </td> <td> : </td> <td> {{ $report->student->parents_phone }} </td> </tr>
                        {{-- 13.--}} <tr> <td> 13.      </td> <td colspan="2"> Pekerjaan Orang Tua </td> <td> </td> <td></td> <td></td> </tr>
                        {{-- __A--}} <tr> <td> {{----}} </td> <td            > a. </td> <td> Ayah </td> <td> : </td> <td> {{ $report->student->father_occupation }} </td> </tr>
                        {{-- __B--}} <tr> <td> {{----}} </td> <td            > b. </td> <td> Ibu </td> <td> : </td> <td> {{ $report->student->mother_occupation }} </td> </tr>
                        {{-- 14.--}} <tr> <td> 14.      </td> <td colspan="2"> Wali Didik </td> <td> </td> <td></td> <td></td> </tr>
                        {{-- __A--}} <tr> <td> {{----}} </td> <td            > a. </td> <td> Nama Wali </td> <td> : </td> <td> {{ $report->student->guardian_name }} </td> </tr>
                        {{-- __B--}} <tr> <td> {{----}} </td> <td            > b. </td> <td> No Telp/HP </td> <td> : </td> <td> {{ $report->student->guardian_phone }} </td> </tr>
                        {{-- __C--}} <tr> <td> {{----}} </td> <td            > c. </td> <td> Alamat </td> <td> : </td> <td> {{ $report->student->guardian_address }} </td> </tr>
                        {{-- __D--}} <tr> <td> {{----}} </td> <td            > d. </td> <td> Pekerjaan </td> <td> : </td> <td> {{ $report->student->guardian_occupation }} </td> </tr>

                    </tbody>
                </table>
            </div>

            <div class="group">
                <table style="width: 100%">
                    <tbody class="signature-box">
                        <tr> <td> </td>    <td></td>    <td> Pontianak, {{ (new Date(request('print_date')))->format('j F Y')  }} </td> </tr>
                        <tr> <td> </td>    <td></td>    <td> Kepala Sekolah </td> </tr>
                        <tr> <td> </td>    <td></td>    <td></td> </tr>
                        <tr> <td> </td>    <td></td>    <td> <strong class="underlined"> {{ $headmaster->name }} </strong> </td> </tr>
                        <tr> <td> </td>    <td></td>    <td> {{ $headmaster->teacher->teacher_id }} </td> </tr>
                        <tr style="height: 5rem"> <td> </td>    <td></td>    <td> </td> </tr>
                        <tr> <td> Keterangan: </td> <td></td>    <td></td> </tr>
                        <tr> <td> NIS: Nomor Induk Peserta Didik </td>    <td></td>    <td></td> </tr>
                        <tr> <td> NISN: Nomor Induk Peserta Didik Nasional </td>    <td></td>    <td></td> </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</body>
</html>