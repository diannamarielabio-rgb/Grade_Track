@extends('layouts.app')
@section('title','My Dashboard')
@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
@endpush

@section('content')
<div class="row g-3 mb-4">
    @foreach([
        ['bi-journal-text','Total Subjects',$totalGrades,'primary'],
        ['bi-check-circle','Passing',$passingCount,'success'],
        ['bi-x-circle','Failing',$failingCount,'danger'],
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
    <div class="col-md-5">
        <div class="data-card h-100">
            <div class="dc-header"><span class="dctitle">My Grade Distribution</span></div>
            <div style="padding:16px;height:240px"><canvas id="gradeChart"></canvas></div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="data-card h-100">
            <div class="dc-header">
                <span class="dctitle">Recent Grades</span>
                <a href="{{ route('student.grades') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead><tr><th>Subject</th><th>Score</th><th>Letter</th><th>Status</th></tr></thead>
                    <tbody>
                    @forelse($recentGrades as $g)
                    <tr>
                        <td>{{ $g->subject }}</td>
                        <td><strong>{{ $g->score }}</strong></td>
                        <td><span class="fw-bold text-{{ $g->letter_color }}">{{ $g->letter_grade }}</span></td>
                        <td><span class="{{ $g->score>=75?'b-pass':'b-fail' }}">{{ $g->status }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted py-3">No grades recorded yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
new Chart(document.getElementById('gradeChart'),{
    type:'doughnut',
    data:{
        labels:['A (90+)','B (80–89)','C (75–79)','D (70–74)','F (<70)'],
        datasets:[{
            data:[{{ $gradeDist['A'] }},{{ $gradeDist['B'] }},{{ $gradeDist['C'] }},{{ $gradeDist['D'] }},{{ $gradeDist['F'] }}],
            backgroundColor:['#22c55e','#3b82f6','#f59e0b','#f97316','#ef4444'],
            borderWidth:2
        }]
    },
    options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'bottom',labels:{font:{size:11},padding:10}}}}
});
</script>
@endpush
