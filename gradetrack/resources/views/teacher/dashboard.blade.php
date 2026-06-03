@extends('layouts.app')
@section('title','Teacher Dashboard')
@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
@endpush

@section('content')
<div class="row g-3 mb-4">
    @foreach([
        ['bi-journal-text','Grade Records',$totalGrades,'primary'],
        ['bi-people','Students Graded',$totalStudents,'success'],
        ['bi-bar-chart','Passing Rate',$passingRate.'%','warning'],
        ['bi-graph-up','Average Grade',$avgGrade,'info'],
    ] as [$icon,$label,$val,$color])
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="slabel"><i class="bi {{ $icon }} me-1"></i>{{ $label }}</div>
            <div class="sval text-{{ $color }}">{{ $val }}</div>
        </div>
    </div>
    @endforeach
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="data-card">
            <div class="dc-header"><span class="dctitle">Grade Distribution</span></div>
            <div style="padding:16px;height:240px"><canvas id="gradeChart"></canvas></div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="data-card">
            <div class="dc-header"><span class="dctitle">Avg Score by Subject</span></div>
            <div style="padding:16px;height:240px"><canvas id="subjectChart"></canvas></div>
        </div>
    </div>
</div>

<div class="data-card">
    <div class="dc-header">
        <span class="dctitle">Recent Entries</span>
        <a href="{{ route('teacher.grades') }}" class="btn btn-sm btn-outline-primary">Manage All</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Student</th><th>Subject</th><th>Score</th><th>Grade</th><th>Status</th><th>Date</th></tr></thead>
            <tbody>
            @forelse($recentGrades as $g)
            <tr>
                <td>{{ $g->student->name ?? '—' }}</td>
                <td>{{ $g->subject }}</td>
                <td><strong>{{ $g->score }}</strong></td>
                <td><span class="fw-bold text-{{ $g->letter_color }}">{{ $g->letter_grade }}</span></td>
                <td><span class="{{ $g->score>=75?'b-pass':'b-fail' }}">{{ $g->status }}</span></td>
                <td class="text-muted" style="font-size:.8rem">{{ $g->created_at->format('M d, Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center text-muted py-3">No grades entered yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
const colors=['#3b82f6','#22c55e','#f59e0b','#f97316','#ef4444'];
new Chart(document.getElementById('gradeChart'),{
    type:'bar',
    data:{
        labels:['A (90+)','B (80–89)','C (75–79)','D (70–74)','F (<70)'],
        datasets:[{label:'Students',data:[{{ $gradeDist['A'] }},{{ $gradeDist['B'] }},{{ $gradeDist['C'] }},{{ $gradeDist['D'] }},{{ $gradeDist['F'] }}],backgroundColor:colors,borderRadius:5}]
    },
    options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,ticks:{stepSize:1}}}}
});
new Chart(document.getElementById('subjectChart'),{
    type:'doughnut',
    data:{labels:@json($subjectStats->pluck('subject')),datasets:[{data:@json($subjectStats->pluck('avg_score')),backgroundColor:colors,borderWidth:2}]},
    options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'right',labels:{font:{size:11},padding:8}}}}
});
</script>
@endpush
