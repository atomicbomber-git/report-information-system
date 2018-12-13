@extends('layouts.admin')
@section('title', "Grafik Perbandingan Nilai Rata-Rata Kelas")
@section('content')

<h1>
    <i class="fa fa-list"></i>
    Grafik Perbandingan Nilai Rata-Rata Kelas
</h1>
<p class="lead"> Tahun Ajaran {{ $term->code }} Semester {{ \App\RoomTerm::EVEN_ODD[$even_odd] }} </p>

@if( session('message-success') )
    <div class="alert alert-success">
        {{ session('message-success') }}
    </div>
@endif

<hr>

<div class="container">
    <div class="row">
        <div class="col-md-3">
            <a href="{{ route('headmaster_access.terms') }}" class="btn btn-secondary btn-sm">
                <i class="fa fa-arrow-left"></i>
                Kembali
            </a>
        </div>
        <div class="col"></div>
        <div class="col-md-3 text-right">
        </div>
    </div>
</div>

<hr>

<div id="chart"></div>
@endsection

@section('script')
<script>

    let labels = []
    let knowledge_grades = []
    let skill_grades = []

    @foreach($knowledge_grade_averages as $knowledge_grade_average)
    
    labels.push('{{ $knowledge_grade_average->room_name ?? '-' }}')
    knowledge_grades.push('{{ $knowledge_grade_average->grade_average ?? 0 }}')

    @endforeach

    @foreach($skill_grade_averages as $skill_grade_average)
    
    skill_grades.push('{{ $skill_grade_average->grade_average ?? 0 }}')

    @endforeach

    const data = {
        labels: labels,
        datasets: [
            {
                name: "Pengetahuan",
                values: knowledge_grades
            },
            {
                name: "Keterampilan",
                values: skill_grades
            },
        ],

        yMarkers: [{ label: "", value: 100,
		options: { labelPos: 'left' }}],
    }

    const chart = new frappe.Chart("#chart", {  // or a DOM element,
                                                // new Chart() in case of ES6 module with above usage
        title: "Grafik Perbandingan Nilai Rata-Rata Kelas",
        data: data,
        type: 'bar', // or 'bar', 'line', 'scatter', 'pie', 'percentage'
        height: 250,
        colors: ['#7cd6fd', '#743ee2'],
    })
</script>
@endsection