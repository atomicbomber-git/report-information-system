@extends('layouts.admin')
@section('title', "Seluruh Tahun Ajaran")
@section('content')

@javascript('terms', $terms)

<h1>
    <i class="fa fa-list"></i>
    Daftar Seluruh Tahun Ajaran
</h1>

@if( session('message-success') )
    <div class="alert alert-success">
        {{ session('message-success') }}
    </div>
@endif

<hr>

<div class="text-right my-3">
    <a href="{{ route('headmaster_access.students') }}" class="btn btn-dark">
        Data Siswa <i class="fa fa-users"></i>
    </a>
</div>


@foreach ($terms as $term)
<div class="card mb-4">
    <div class="card-header font-weight-bold">
        <i class="fa fa-icon"></i>
        {{ $term->code }}
    </div>
    <div class="card-body">
        <div>
            @foreach (\App\RoomTerm::EVEN_ODD as $even_odd => $semester)
            <a href="{{ route('headmaster_access.room_terms', [$term->id, $even_odd]) }}" class="btn btn-dark btn-sm">
                Nilai Semester {{ $semester }}
                <i class="fa fa-list"></i>
            </a>
            @endforeach
            
            <div class="d-inline-block mr-5"></div>

            @foreach (\App\RoomTerm::EVEN_ODD as $even_odd => $semester)
            <a href="{{ route('headmaster_access.teachers', [$term->id, $even_odd]) }}" class="btn btn-dark btn-sm">
                Guru Semester {{ $semester }}
                <i class="fa fa-list"></i>
            </a>
            @endforeach
        </div>

        <hr>

        <h5> Perbandingan Gender Siswa </h5>
        <div id="chart_{{ $loop->iteration }}"></div> 
        
        <hr>

        <div class="row">
            <div class="col">
                <h5> Rata-Rata Nilai Kelas Semester Ganjil </h5>
                <div id="chart_room_term_grade_odd_{{ $loop->iteration }}"></div>

                @push('scripts')
                <script>

                    const chart_room_term_grade_odd_{{ $loop->iteration }} = new frappe.Chart("#chart_room_term_grade_odd_{{ $loop->iteration }}", {
                        title: "Grafik Perbandingan Nilai Rata-Rata Kelas",
                        data: {
                            labels: terms['{{ $term->id }}']['room_term_odd_grades']['rooms'],
                            datasets: [
                                {
                                    name: "Pengetahuan",
                                    values: terms['{{ $term->id }}']['room_term_odd_grades']['knowledge']
                                },
                                {
                                    name: "Keterampilan",
                                    values: terms['{{ $term->id }}']['room_term_odd_grades']['skill']
                                },
                            ],

                            yMarkers: [{ label: "", value: 100,
                            options: { labelPos: 'left' }}],
                        },
                        type: 'bar', // or 'bar', 'line', 'scatter', 'pie', 'percentage'
                        height: 250,
                        colors: ['#7cd6fd', '#743ee2'],
                    })

                    console.log(terms['{{ $term->id }}']['room_term_odd_grades'])
                </script>
                @endpush
            </div>
            <div class="col">
                <h5> Rata-Rata Nilai Kelas Semester Genap </h5>
                <div id="chart_room_term_grade_even_{{ $loop->iteration }}"></div>

                @push('scripts')
                <script>

                    const chart_room_term_grade_even_{{ $loop->iteration }} = new frappe.Chart("#chart_room_term_grade_even_{{ $loop->iteration }}", {
                        data: {
                            labels: terms['{{ $term->id }}']['room_term_even_grades']['rooms'],
                            datasets: [
                                {
                                    name: "Pengetahuan",
                                    values: terms['{{ $term->id }}']['room_term_even_grades']['knowledge']
                                },
                                {
                                    name: "Keterampilan",
                                    values: terms['{{ $term->id }}']['room_term_even_grades']['skill']
                                },
                            ],

                            yMarkers: [{ label: "", value: 100,
                            options: { labelPos: 'left' }}],
                        },
                        type: 'bar', // or 'bar', 'line', 'scatter', 'pie', 'percentage'
                        height: 250,
                        colors: ['#7cd6fd', '#743ee2'],
                    })

                    console.log(terms['{{ $term->id }}']['room_term_odd_grades'])
                </script>
                @endpush
            </div>
        </div>

        <hr>
        

        <div class="row">
            <div class="col">
                <h5> Nilai Terbaik Semester Ganjil </h5>
            </div>
            <div class="col">
                <h5> Nilai Terbaik Semester Genap </h5>
            </div>
        </div>

        @foreach($grades as $grade_level)

        <div class="row" style="font-size: 0.7rem">
            <div class="col">
                <h5> Kelas {{ $grade_level }} </h5>

                <div class='table-responsive'>
                    <table class='table table-sm table-bordered table-striped'>
                       <thead>
                            <tr>
                                <td> Nama </td>
                                <td> NIS </td>
                                <td> Kelas </td>
                                <td> Nilai Pengetahuan </td>
                                <td> Nilai Keterampilan </td>
                                <td> Rata-Rata </td>
                            </tr>
                       </thead>
                       <tbody>
                           @foreach ($term->best_odd_grades[$grade_level] as $grade)
                            <tr>
                                <td> {{ $grade['data']->student_name }} </td>
                                <td> {{ $grade['data']->student_code }} </td>
                                <td> {{ $grade['data']->room_name }} </td>
                                <td> {{ number_format($grade['knowledge_grade'], 2) }} </td>
                                <td> {{ number_format($grade['skill_grade'], 2) }} </td>
                                <td> {{ number_format($grade['grade'], 2) }} </td>
                            </tr>
                           @endforeach
                       </tbody>
                    </table>
                </div>

                <div id="chart_best_grade_odd_{{ $loop->iteration }}_{{ $grade_level }}"></div>

                @push('scripts')
                <script>

                    const chart_best_grade_odd_{{ $loop->iteration }}_{{ $grade_level }} = new frappe.Chart("#chart_best_grade_odd_{{ $loop->iteration }}_{{ $grade_level }}", {
                        data: {
                            labels: terms['{{ $term->id }}']['best_odd_grades'].map(record => record.data.student_name),
                            datasets: [
                                {
                                    name: "Pengetahuan",
                                    values: terms['{{ $term->id }}']['best_odd_grades']['{{ $grade_level }}'].map(record => parseFloat(record.knowledge_grade.toFixed(2)))
                                },

                                {
                                    name: "Keterampilan",
                                    values: terms['{{ $term->id }}']['best_odd_grades']['{{ $grade_level }}'].map(record => parseFloat(record.skill_grade.toFixed(2)))
                                },

                                {
                                    name: "Rata-Rata",
                                    values: terms['{{ $term->id }}']['best_odd_grades']['{{ $grade_level }}'].map(record => parseFloat(record.grade.toFixed(2)))
                                },
                            ],

                            yMarkers: [{ label: "", value: 100,
                            options: { labelPos: 'left' }}],
                        },
                        type: 'bar', // or 'bar', 'line', 'scatter', 'pie', 'percentage'
                        height: 250,
                    })
                </script>
                @endpush


            </div>
            <div class="col">
                <h5> Kelas {{ $grade_level }} </h5>
                <div class='table-responsive'>
                    <table class='table table-sm table-bordered table-striped'>
                        <thead>
                            <tr>
                                <td> Nama </td>
                                <td> NIS </td>
                                <td> Kelas </td>
                                <td> Nilai Pengetahuan </td>
                                <td> Nilai Keterampilan </td>
                                <td> Rata-Rata </td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($term->best_even_grades[$grade_level] as $grade)
                            <tr>
                                <td> {{ $grade['data']->student_name }} </td>
                                <td> {{ $grade['data']->student_code }} </td>
                                <td> {{ $grade['data']->room_name }} </td>
                                <td> {{ number_format($grade['knowledge_grade'], 2) }} </td>
                                <td> {{ number_format($grade['skill_grade'], 2) }} </td>
                                <td> {{ number_format($grade['grade'], 2) }} </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div id="chart_best_grade_even_{{ $loop->iteration }}_{{ $grade_level }}"></div>

                @push('scripts')
                <script>

                    const chart_best_grade_even_{{ $loop->iteration }}_{{ $grade_level }} = new frappe.Chart("#chart_best_grade_even_{{ $loop->iteration }}_{{ $grade_level }}", {
                        data: {
                            labels: terms['{{ $term->id }}']['best_even_grades']['{{ $grade_level }}'].map(record => record.data.student_name),
                            datasets: [
                                {
                                    name: "Pengetahuan",
                                    values: terms['{{ $term->id }}']['best_even_grades']['{{ $grade_level }}'].map(record => parseFloat(record.knowledge_grade.toFixed(2)))
                                },

                                {
                                    name: "Keterampilan",
                                    values: terms['{{ $term->id }}']['best_even_grades']['{{ $grade_level }}'].map(record => parseFloat(record.skill_grade.toFixed(2)))
                                },

                                {
                                    name: "Rata-Rata",
                                    values: terms['{{ $term->id }}']['best_even_grades']['{{ $grade_level }}'].map(record => parseFloat(record.grade.toFixed(2)))
                                },
                            ],

                            yMarkers: [{ label: "", value: 100,
                            options: { labelPos: 'left' }}],
                        },
                        type: 'bar',
                        height: 250,
                    })
                </script>
                @endpush

            </div>
        </div>

        @endforeach


    </div>
</div>
@endforeach

{{-- <table class='table table-striped table-responsive-xl table-sm'>
    <thead class='thead-dark'>
        <tr>
            <th> # </th>
            <th> Tahun Ajaran </th>
            <th> Nilai Siswa </th>
            <th> Data Guru </th>
            <th> Jumlah Siswa </th>
            <th> Jumlah Guru </th>
        </tr>
    </thead>

    <tbody>
        @foreach ($terms as $term)
        <tr>
            <td> {{ $loop->iteration }}. </td>
            <td> {{ $term->code }} </td>
            <td>
                @foreach (\App\RoomTerm::EVEN_ODD as $even_odd => $semester)
                <a href="{{ route('headmaster_access.room_terms', [$term->id, $even_odd]) }}" class="btn btn-dark btn-sm">
                    Semester {{ $semester }}
                    <i class="fa fa-list"></i>
                </a>
                @endforeach
            </td>
            <td>
                @foreach (\App\RoomTerm::EVEN_ODD as $even_odd => $semester)
                <a href="{{ route('headmaster_access.teachers', [$term->id, $even_odd]) }}" class="btn btn-dark btn-sm">
                    Semester {{ $semester }}
                    <i class="fa fa-list"></i>
                </a>
                @endforeach
            </td>
            <td> {{ ($male_student_count[$term->id] ?? 0) + ($female_student_count[$term->id] ?? 0) }} ({{ $male_student_count[$term->id] ?? 0 }} Laki-Laki, {{ $female_student_count[$term->id] ?? 0 }} Perempuan) </td>
            <td> {{ $teacher_count[$term->id] ?? 0 }} </td>
        @endforeach
    </tbody>
</table> --}}
@endsection

@section('script')
    @foreach ($terms as $term)
        <script>
            const chart_{{ $loop->iteration }} = new frappe.Chart("#chart_{{ $loop->iteration }}", {
                data: {
                    labels: ["Laki-Laki", "Perempuan"],
                    datasets: [
                        {
                            name: "Test",
                            values: [
                                {{ $male_student_count[$term->id] ?? 0 }},
                                {{ $female_student_count[$term->id] ?? 0 }}
                            ]
                        }
                    ],
                },
                type: 'percentage'
            })
        </script>
    @endforeach

    @stack('scripts')
@endsection

