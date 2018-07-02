<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> Rapor </title>

    <style>
        table {
            border: medium solid black;
            border-collapse: collapse;
        }

        thead {
            border: medium solid black;
        }

        td, th {
            padding: 0.5rem;
            border: thin solid black;
        }

        .border-md {
            border: medium solid black;
        }
    </style>
</head>
<body>
    
    <h4> B. Pengetahuan dan Keterampilan </h4>
    <h4> <span style="color: white"> B. </span> Ketuntasan Belajar Minimal: 76 </h4>
    <table>
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
                <th> Angka </th>
                <th> Predikat </th>
                <th> Deskripsi </th>
                <th> Angka </th>
                <th> Predikat </th>
                <th> Deskripsi </th>
            </tr>

            <tr class="border-md">
                <th colspan="8"> Kelompok A </th>
            </tr>
        </thead>
        <tbody>
            @foreach($course_report_groups['A'] as $course_report)
            <tr>
                <td> {{ $loop->iteration }} </td>
                <td> {{ $course_report->name }} </td>
                <td> {{ $course_report->knowledge_grade }} </td>
                <td> {{ \App\Helper::grade($course_report->knowledge_grade) }} </td>
                <td> {{ $course_report->knowledge_description }} </td>
                <td>  </td>
                <td>  </td>
                <td> {{ $course_report->skill_description }} </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>