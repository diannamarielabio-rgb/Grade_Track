@extends('layouts.app')
@section('title','My Grades')

@section('content')

{{-- Filters --}}
<form method="GET" class="row g-2 mb-3">
    <div class="col-auto">
        <input type="text" name="subject" class="form-control form-control-sm"
               value="{{ request('subject') }}" placeholder="Filter by subject…">
    </div>
    <div class="col-auto">
        <select name="semester" class="form-select form-select-sm">
            <option value="">All Semesters</option>
            @foreach($semesters as $s)
            <option value="{{ $s }}" {{ request('semester')===$s?'selected':'' }}>{{ $s }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-auto">
        <select name="school_year" class="form-select form-select-sm">
            <option value="">All School Years</option>
            @foreach($schoolYears as $y)
            <option value="{{ $y }}" {{ request('school_year')===$y?'selected':'' }}>{{ $y }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-auto">
        <button class="btn btn-sm btn-outline-secondary">Filter</button>
        <a href="{{ route('student.grades') }}" class="btn btn-sm btn-ghost">Clear</a>
    </div>
</form>

<div class="data-card">
    <div class="dc-header">
        <span class="dctitle">My Grade Records ({{ $grades->total() }})</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr>
                <th>#</th><th>Subject</th><th>Score</th><th>Letter Grade</th>
                <th>Semester</th><th>School Year</th><th>Teacher</th><th>Status</th><th>Date</th>
            </tr></thead>
            <tbody>
            @forelse($grades as $g)
            <tr>
                <td class="text-muted">{{ $loop->iteration }}</td>
                <td><strong>{{ $g->subject }}</strong></td>
                <td>
                    <span class="fw-bold fs-6 text-{{ $g->letter_color }}">{{ $g->score }}</span>
                </td>
                <td>
                    <span class="badge fs-6 bg-{{ $g->letter_color }} bg-opacity-10 text-{{ $g->letter_color }} border border-{{ $g->letter_color }} border-opacity-25">
                        {{ $g->letter_grade }}
                    </span>
                </td>
                <td>{{ $g->semester }}</td>
                <td>{{ $g->school_year }}</td>
                <td class="text-muted" style="font-size:.83rem">{{ $g->teacher->name ?? '—' }}</td>
                <td><span class="{{ $g->score>=75?'b-pass':'b-fail' }}">{{ $g->status }}</span></td>
                <td class="text-muted" style="font-size:.8rem">{{ $g->created_at->format('M d, Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center text-muted py-5">
                    <i class="bi bi-journal-x d-block mb-2" style="font-size:2rem"></i>
                    No grades recorded yet.
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($grades->hasPages())
    <div class="p-3">{{ $grades->appends(request()->query())->links() }}</div>
    @endif
</div>
@endsection
