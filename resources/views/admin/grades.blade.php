@extends('layouts.app')
@section('title','All Grades')

@section('content')
<div class="data-card">
    <div class="dc-header"><span class="dctitle">All Grade Records ({{ $grades->total() }})</span></div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr>
                <th>#</th><th>Student</th><th>Subject</th><th>Score</th>
                <th>Letter</th><th>Semester</th><th>School Year</th>
                <th>Encoded By</th><th>Status</th><th>Date</th>
            </tr></thead>
            <tbody>
            @forelse($grades as $g)
            <tr>
                <td class="text-muted">{{ $loop->iteration }}</td>
                <td>{{ $g->student->name ?? '—' }}</td>
                <td>{{ $g->subject }}</td>
                <td><strong>{{ $g->score }}</strong></td>
                <td><span class="fw-bold text-{{ $g->letter_color }}">{{ $g->letter_grade }}</span></td>
                <td>{{ $g->semester }}</td>
                <td>{{ $g->school_year }}</td>
                <td class="text-muted" style="font-size:.8rem">{{ $g->teacher->name ?? '—' }}</td>
                <td><span class="{{ $g->score>=75?'b-pass':'b-fail' }}">{{ $g->status }}</span></td>
                <td class="text-muted" style="font-size:.8rem">{{ $g->created_at->format('M d, Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="10" class="text-center text-muted py-4">No grade records yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($grades->hasPages())
    <div class="p-3">{{ $grades->links() }}</div>
    @endif
</div>
@endsection
