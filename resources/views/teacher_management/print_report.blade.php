<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> Laporan Hasil Belajar {{ $report->student->user->name }} ({{ $report->student->student_id }}) </title>

    <link href="{{ asset('css/paper.css') }}" rel="stylesheet">

    <style>
        @page { size: A4 }

        h1.title, h2.title, h3.title, h4.title, h5.title {
            /* text-align: center; */
            margin: 0.3rem;
        }

        table.report {
            border: medium solid black;
            border-collapse: collapse;
        }

        table.report thead {
            border: medium solid black;
        }

        table.report td, table.report th {
            padding: 0.1rem;
            border: thin solid black;
        }

        table.report td.description {
            max-width: 10rem;
            font-size: 0.7rem;
        }

        table.report .border-md {
            border: medium solid black;
        }

        table.report .score, table.report .grade {
            text-align: center;
        }

        table.report .score {
            max-width: 4rem;
        }

        table.report .grade {
            max-width: 3rem;
        }

        table.header {
            width: 100%;
            padding: 1rem;
            border-bottom: medium solid black;
        }

        table.box {
            border: medium solid black;
            border-collapse: collapse;
        }

        table.box td, table.box th {
            border: thin solid black;
            padding: 0.2rem;
        }

        .mt {
            margin-top: 1rem;
        }

        table.signature {
            width: 100%;
        }
        
        table.signature td {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .underlined {
            border-bottom: medium solid black;
        }

    </style>
</head>
<body class="A4">
    <section class="sheet padding-10mm">
        <table class="header">
            <tbody>
                <tr>
                    <td> Nama Sekolah </td>
                    <td> : </td>
                    <td> <strong> SMP Negeri 16 Pontianak </strong> </td>
                    <td> Kelas </td>
                    <td> : </td>
                    <td> {{ $report->room_term->room->name }} </td>
                </tr>

                <tr>
                    <td> Alamat </td>
                    <td> : </td>
                    <td> JL. RE Martadinata Pontianak </td>
                    <td> Semester </td>
                    <td> : </td>
                    <td> {{ $report->room_term->even_odd_numeric() }} / {{ $report->room_term->even_odd }} </td>
                </tr>

                <tr>
                    <td> Nama </td>
                    <td> : </td>
                    <td> <strong> {{ strtoupper($report->student->user->name) }} </strong> </td>
                    <td> Tahun Pelajaran </td>
                    <td> : </td>
                    <td> {{ $report->room_term->term->code }} </td>
                </tr>

                <tr>
                    <td> Nomor Induk/NISN </td>
                    <td> : </td>
                    <td> {{ $report->student->student_id }} </td>
                </tr>

            </tbody>
        </table>

        <h3 class="title" style="text-align:center; padding: 2.5rem 0 2.5rem 0"> PENCAPAIAN KOMPETENSI PESERTA DIDIK </h1>

        <table style="width: 100%">
            <tbody>
                <tr>
                    <td> <h4 class="title"> A. </h4> </td>
                    <td> <h4 class="title"> Sikap </h4> </td>
                    <td> </td>
                    <td> </td>
                </tr>

                <tr>
                    <td> <h4 class="title"> </h4> </td>
                    <td> <h4 class="title"> 1. Sikap Spiritual </h4> </td>
                    <td> </td>
                    <td> </td>
                </tr>

                <tr>
                    <td> </td>
                    <td colspan="3" style="border: medium solid black; width: 100%">
                        <h4 class="title"> Deskripsi: </h4>
                        <p style="padding: 2.5rem 0 2.5rem 0">
                            {{ $report->spiritual_attitude_description }}
                        </p>
                    </td>
                </tr>

                <tr>
                    <td> <h4 class="title"> </h4> </td>
                    <td> <h4 class="title"> 2. Sikap Sosial </h4> </td>
                    <td> </td>
                    <td> </td>
                </tr>

                <tr>
                    <td> </td>
                    <td colspan="3" style="border: medium solid black; width: 100%">
                        <h4 class="title"> Deskripsi: </h4>
                        <p style="padding: 2.5rem 0 2.5rem 0">
                            {{ $report->social_attitude_description }}
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
    </section>

    <section class="sheet padding-10mm">
        
        <table>
            <tbody>
                <tr>
                    <td> <h4 class="title"> B. </h4> </td>
                    <td> <h4 class="title"> Pengetahuan dan Keterampilan </h4> </td>
                    <td> </td>
                    <td> </td>
                </tr>
                <tr>
                    <td> </td>
                    <td> <h4 class="title"> Ketuntasan Belajar Minimal </h4> </td>
                    <td> : </td>
                    <td> <h4 class="title"> {{ $report->room_term->term->passing_grade  }} </h4> </td>
                </tr>
            </tbody>
        </table>

        <table class="report" style="width: 100%">
            <thead>
                <tr>
                    <th rowspan="2"> No. </th>
                    <th rowspan="2"> Mata Pelajaran </th>
                    <th colspan="3"> Pengetahuan </th>
                    <th colspan="3"> Keterampilan </th>
                </tr>
    
                <tr>
                    {{-- Empty --}}
                    {{-- Empty --}}
                    <th class="score"> Angka </th>
                    <th class="grade"> Pred <br/> ikat </th>
                    <th> Deskripsi </th>
                    <th class="score"> Angka </th>
                    <th class="grade"> Pred <br/> ikat </th>
                    <th> Deskripsi </th>
                </tr>
    
                <tr class="border-md">
                    <th colspan="8" style="text-align: left"> Kelompok A </th>
                </tr>
            </thead>
            <tbody>
                @if(isset($course_groups['A'])) 
                    @foreach($course_groups['A'] as $course)
                    <tr>
                        <td> {{ $loop->iteration }} </td>
                        <td> {{ $course->name }} </td>
                        <td class="score"> {{ $knowledge_grades->get($course->id) }} </td>
                        <td class="grade"> {{ \App\Helper::grade($knowledge_grades->get($course->id)) }} </td>
                        <td class="description"> {{ $descriptions[$course->id]->knowledge_description }} </td>
                        <td class="score"> {{ number_format($skill_grades[$course->id], 2) ?? 0 }} </td>
                        <td class="grade"> {{ \App\Helper::grade($skill_grades[$course->id] ?? 0) }} </td>
                        <td class="description"> {{ $descriptions[$course->id]->skill_description }} </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </section>

    <section class="sheet padding-10mm">
        <table class="report" style="width: 100%">
            <thead>
                <tr>
                    <th rowspan="2"> No. </th>
                    <th rowspan="2"> Mata Pelajaran </th>
                    <th colspan="3"> Pengetahuan </th>
                    <th colspan="3"> Keterampilan </th>
                </tr>
    
                <tr>
                    {{-- Empty --}}
                    {{-- Empty --}}
                    <th class="score"> Angka </th>
                    <th class="grade"> Pred <br/> ikat </th>
                    <th> Deskripsi </th>
                    <th class="score"> Angka </th>
                    <th class="grade"> Pred <br/> ikat </th>
                    <th> Deskripsi </th>
                </tr>
    
                <tr class="border-md">
                    <th colspan="8" style="text-align: left"> Kelompok B </th>
                </tr>
            </thead>
            <tbody>
                @if(isset($course_groups['B'])) 
                    @foreach($course_groups['B'] as $course)
                    <tr>
                        <td> {{ $loop->iteration }} </td>
                        <td> {{ $course->name }} </td>
                        <td class="score"> {{ $knowledge_grades[$course->id] ?? '-' }} </td>
                        <td class="grade"> {{ \App\Helper::grade($knowledge_grades[$course->id] ?? 0) }} </td>
                        <td class="description"> {{ $descriptions[$course->id]->knowledge_description ?? '-' }} </td>
                        <td class="score"> {{ number_format($skill_grades[$course->id], 2) ?? 0 }} </td>
                        <td class="grade"> {{ \App\Helper::grade($skill_grades[$course->id] ?? 0) }} </td>
                        <td class="description"> {{ $descriptions[$course->id]->skill_description ?? '-' }} </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

        <table style="width: 100%" class="mt">
            <tbody>
                <tr>
                    <td style="width: 2rem"> <h4 class="title"> C. </h4> </td>
                    <td> <h4 class="title"> Ekstrakurikuler </h4> </td>
                </tr>

                <tr>
                    <td></td>
                    <td>
                        <table class="box" style="width: 100%; text-align: center">
                            <thead>
                                <tr>
                                    <th> No </th>
                                    <th> Kegiatan Ekstrakurikuler </th>
                                    <th> Keterangan </th>
                                </tr>
                            </thead>
                
                            <tbody>
                                @foreach ($extracurriculars as $extracurricular)
                                <tr>
                                    <td> {{ $loop->iteration }} </td>
                                    <td> {{ $extracurricular->name }} </td>
                                    <td> {{ \App\ExtracurricularReport::GRADES[$extracurricular->score] ?? '-' }} </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>

        <table class="mt">
            <tbody>
                <tr>
                    <td style="width: 2rem"> <h4 class="title"> D. </h4> </td>
                    <td> <h4 class="title"> Ketidakhadiran </h4> </td>
                </tr>

                <tr>
                    <td></td>
                    <td>
                        <table class="box">
                            <thead>
                                <tr> <td> Sakit </td> <td> {{ $report->absence_sick }} hari </td> </tr>
                                <tr> <td> Izin </td> <td> {{ $report->absence_permit }} hari </td> </tr>
                                <tr> <td> Tanpa Keterangan </td> <td> {{ $report->absence_unknown }} hari </td> </tr>
                            </thead>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>

        <table class="signature" style="margin-top: 5rem">
            <tbody>
                <tr>
                    <td> Mengetahui: </td>
                    <td> </td>
                    <td> </td>
                </tr>
                <tr>
                    <td> Orang Tua/Wali </td>
                    <td> Wali Kelas, </td>
                    <td> Kepala Sekolah </td>
                </tr>
                <tr style="height: 5rem">
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td style="vertical-align: bottom"> <div style="border-bottom: medium solid black"> </div> </td>
                    <td style="vertical-align: bottom">
                        <div style="border-bottom: medium solid black">
                            <strong> {{ $report->room_term->teacher->user->name }} </strong>
                        </div>
                    </td>
                    <td style="vertical-align: bottom">
                        <div style="border-bottom: medium solid black">
                            <strong> {{ $headmaster->name }} </strong>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td></td>
                    <td> {{ $report->room_term->teacher->teacher_id }} </td>
                    <td> {{ $headmaster->teacher->teacher_id }} </td>
                </tr>
            </tbody>
        </table>
        
    </section>
</body>
</html>