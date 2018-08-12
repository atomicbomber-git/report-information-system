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
    </style>
</head>
<body class="A4">
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

        <table class="report">
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
                        <td class="score"> {{ $knowledge_grades[$course->id] }} </td>
                        <td class="grade"> {{ \App\Helper::grade($knowledge_grades[$course->id]) }} </td>
                        <td class="description"> {{ $descriptions[$course->id]->knowledge_description }} </td>
                        <td class="score"> {{ $skill_grades[$course->id] }} </td>
                        <td class="grade"> {{ \App\Helper::grade($skill_grades[$course->id]) }} </td>
                        <td class="description"> {{ $descriptions[$course->id]->skill_description }} </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </section>

    <section class="sheet padding-10mm">
        <table class="report">
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
                        <td class="score"> {{ $knowledge_grades[$course->id] }} </td>
                        <td class="grade"> {{ \App\Helper::grade($knowledge_grades[$course->id]) }} </td>
                        <td class="description"> {{ $descriptions[$course->id]->knowledge_description }} </td>
                        <td class="score"> {{ $skill_grades[$course->id] }} </td>
                        <td class="grade"> {{ \App\Helper::grade($skill_grades[$course->id]) }} </td>
                        <td class="description"> {{ $descriptions[$course->id]->skill_description }} </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

        <table>
            <tbody>
                <tr>
                    <td> <h4 class="title"> C. </h4> </td>
                    <td> <h4 class="title"> Ekstrakurikuler </h4> </td>
                    <td> </td>
                    <td> </td>
                </tr>
            </tbody>
        </table>

        <table class="report">
            <thead>
                <tr>
                    <th> No </th>
                    <th> Kegiatan Ekstrakurikuler </th>
                    <th> Keterangan </th>
                </tr>
            </thead>
        </table>

        <table>
            <tbody>
                <tr>
                    <td> <h4 class="title"> D. </h4> </td>
                    <td> <h4 class="title"> Ketidakhadiran </h4> </td>
                    <td> </td>
                    <td> </td>
                </tr>
            </tbody>
        </table>

        <table class="report">
            <thead>
                <tr> <td> Sakit </td> <td> {{ $report->absence_sick }} hari </td> </tr>
                <tr> <td> Izin </td> <td> {{ $report->absence_permit }} hari </td> </tr>
                <tr> <td> Tanpa Keterangan </td> <td> {{ $report->absence_unknown }} hari </td> </tr>
            </thead>
        </table>
        
    </section>
</body>
</html>