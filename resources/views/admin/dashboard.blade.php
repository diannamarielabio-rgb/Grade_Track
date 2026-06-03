@extends('layouts.app')
@section('title','Admin Dashboard')
@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
@endpush

@section('content')

{{-- Stats --}}
<div class="row g-3 mb-4">
    @foreach([
        ['bi-people','Total Users', $totalUsers,'primary'],
        ['bi-person-badge','Teachers', $totalTeachers,'success'],
        ['bi-person-workspace','Students', $totalStudents,'info'],
        ['bi-hourglass-split','Pending Approval', $pendingCount,'warning'],
        ['bi-journal-text','Total Grades', $totalGrades,'secondary'],
    ] as [$icon,$label,$val,$color])
    <div class="col-6 col-md">
        <div class="stat-card">
            <div class="slabel"><i class="bi {{ $icon }} me-1"></i>{{ $label }}</div>
            <div class="sval text-{{ $color }}">{{ $val }}</div>
        </div>
    </div>
    @endforeach
</div>

{{-- Charts --}}
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="data-card">
            <div class="dc-header"><span class="dctitle">Grade Distribution (All)</span></div>
            <div style="padding:16px;height:240px"><canvas id="gradeChart"></canvas></div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="data-card">
            <div class="dc-header"><span class="dctitle">Average Score by Subject</span></div>
            <div style="padding:16px;height:240px"><canvas id="subjectChart"></canvas></div>
        </div>
    </div>
</div>

{{-- Pending approvals --}}
@if($pendingCount > 0)
<div class="data-card mb-4">
    <div class="dc-header">
        <span class="dctitle"><i class="bi bi-hourglass-split me-1 text-warning"></i>Pending Approvals ({{ $pendingCount }})</span>
        <a href="{{ route('admin.users') }}?status=pending" class="btn btn-sm btn-outline-warning">View All</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Registered</th><th>Actions</th></tr></thead>
            <tbody>
            @foreach($pendingUsers as $u)
            <tr>
                <td>{{ $u->name }}</td>
                <td>{{ $u->email }}</td>
                <td><span class="b-{{ $u->role }}">{{ ucfirst($u->role) }}</span></td>
                <td class="text-muted" style="font-size:.8rem">{{ $u->created_at->diffForHumans() }}</td>
                <td class="d-flex gap-2">
                    <form method="POST" action="{{ route('admin.users.approve',$u) }}">@csrf @method('PATCH')
                        <button class="btn btn-sm btn-success"><i class="bi bi-check-lg"></i> Approve</button>
                    </form>
                    <form method="POST" action="{{ route('admin.users.reject',$u) }}">@csrf @method('PATCH')
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-x-lg"></i> Reject</button>
                    </form>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
const colors = ['#3b82f6','#22c55e','#f59e0b','#f97316','#ef4444'];
new Chart(document.getElementById('gradeChart'), {
    type:'bar',
    data:{
        labels:['A (90+)','B (80–89)','C (75–79)','D (70–74)','F (<70)'],
        datasets:[{
            label:'Students',
            data:[{{ $gradeDist['A'] }},{{ $gradeDist['B'] }},{{ $gradeDist['C'] }},{{ $gradeDist['D'] }},{{ $gradeDist['F'] }}],
            backgroundColor:colors, borderRadius:5,
        }]
    },
    options:{ responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}}, scales:{y:{beginAtZero:true,ticks:{stepSize:1}}} }
});
new Chart(document.getElementById('subjectChart'), {
    type:'doughnut',
    data:{
        labels: @json($subjectStats->pluck('subject')),
        datasets:[{ data: @json($subjectStats->pluck('avg_score')), backgroundColor:colors, borderWidth:2 }]
    },
    options:{ responsive:true, maintainAspectRatio:false, plugins:{legend:{position:'right',labels:{font:{size:11},padding:10}}} }
});
</script>
@endpush
