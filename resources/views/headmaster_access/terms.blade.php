@extends('layouts.admin')
@section('title', "Seluruh Tahun Ajaran")
@section('content')

@javascript('terms', $terms)
@javascript('student_genders', $student_genders)

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

        <h5> Perbandingan Gender Siswa per Tahun Ajaran </h5>

        <div id="chart_{{ $loop->iteration }}"></div>

        <div class="alert alert-info">
            Total Siswa: {{ ($male_student_count[$term->id] ?? 0) + ($male_student_count[$term->id] ?? 0) }}
        </div>

        <hr>

        <h5 class="mb-5"> Perbandingan Gender Siswa per Angkatan </h5>
            <div class="row">
                @foreach($grades AS $grade_level)
                <div class="col">
                    
                    <h5> Kelas {{ $grade_level }} </h5>
                    
                    <div id="student_genders_chart_{{ $term->id }}_{{ $grade_level }}"></div>

                    <div class="alert alert-info">
                        Total Siswa: {{ ($student_genders[$term->id][$grade_level]['male'][0]->count ?? 0) + ($student_genders[$term->id][$grade_level]['female'][0]->count ?? 0) }}
                    </div>

                    @push("scripts")
                    <script>
                        const student_genders_chart_{{ $term->id }}_{{ $grade_level }} = new frappe.Chart("#student_genders_chart_{{ $term->id }}_{{ $grade_level }}", {
                            data: {
                                labels: ["Laki-Laki", "Perempuan"],
                                datasets: [
                                    {
                                        name: "Pengetahuan",
                                        values: [
                                            _.get(student_genders, ['{{ $term->id }}', '{{ $grade_level }}', 'male', 0, 'count'], 0),
                                            _.get(student_genders, ['{{ $term->id }}', '{{ $grade_level }}', 'female', 0, 'count'], 0)
                                        ]
                                    }
                                ],
                                yMarkers: [{ label: "", value: 100,
                                options: { labelPos: 'left' }}],
                            },
                            type: "percentage", // or 'bar', 'line', 'scatter', 'pie', 'percentage'
                            height: 250,
                            colors: ['#7cd6fd', '#743ee2'],
                        })
                    </script>
                    @endpush
                </div>
                @endforeach
            </div>
        <hr>

        <div class="row">
            <div class="col">
                <h5> Rata-Rata Nilai Kelas Semester Ganjil </h5>
                <div id="chart_room_term_grade_odd_{{ $loop->iteration }}"></div>

                @push('scripts')
                <script>

                    const chart_room_term_grade_odd_{{ $loop->iteration }} = new frappe.Chart("#chart_room_term_grade_odd_{{ $loop->iteration }}", {
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

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            @foreach($grades as $grade_level)
            <li class="nav-item">
                <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="grade_tab_{{ $term->id }}_{{ $grade_level }}-tab" data-toggle="tab" href="#grade_tab_{{ $term->id }}_{{ $grade_level }}" role="tab" aria-controls="home" aria-selected="true"> Kelas {{ $grade_level }} </a>
            </li>
            @endforeach
        </ul>
        <div class="tab-content" id="myTabContent">
            @foreach($grades as $grade_level)
            <div class="mt-4 tab-pane fade show active" id="grade_tab_{{ $term->id }}_{{ $grade_level }}" role="tabpanel" aria-labelledby="grade_tab_{{ $grade_level }}">
                <div class="row" style="font-size: 0.7rem">
                    <div class="col">
                        <h5> Semester Ganjil </h5>
        
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
        
                        <div id="chart_best_grade_odd_{{ $term->id }}_{{ $grade_level }}"></div>
        
                        @push('scripts')
                        <script>
        
                            const chart_best_grade_odd_{{ $term->id }}_{{ $grade_level }} = new frappe.Chart("#chart_best_grade_odd_{{ $term->id }}_{{ $grade_level }}", {
                                data: {
                                    labels: terms['{{ $term->id }}']['best_odd_grades']['{{ $grade_level }}'].map(record => record.data.student_name),
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
                        <h5> Semester Genap </h5>
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
        
                        <div id="chart_best_grade_even_{{ $term->id }}_{{ $grade_level }}"></div>
        
                        @push('scripts')
                        <script>
        
                            const chart_best_grade_even_{{ $term->id }}_{{ $grade_level }} = new frappe.Chart("#chart_best_grade_even_{{ $term->id }}_{{ $grade_level }}", {
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
            </div>
            @endforeach
        </div>

    </div>
</div>
@endforeach

@endsection

@section('pre-script')
    <script src="{{ asset('js/frappe-charts.min.iife.js') }}"></script>
    <script src="{{ asset('js/pre.js') }}"></script>

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

    @stack("scripts")
@endsection

@section("script")
<script>
    $(".tab-pane:not(:first-child)").removeClass("active");
</script>
@endsection

