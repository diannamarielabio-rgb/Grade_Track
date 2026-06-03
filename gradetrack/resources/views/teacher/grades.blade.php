@extends('layouts.app')
@section('title','Manage Grades')

@section('content')
<div class="data-card">
    <div class="dc-header">
        <span class="dctitle">Grade Records ({{ $grades->total() }})</span>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="bi bi-plus-lg me-1"></i>Add Grade
        </button>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr>
                <th>#</th><th>Student</th><th>Subject</th><th>Score</th>
                <th>Letter</th><th>Semester</th><th>School Year</th><th>Status</th><th>Actions</th>
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
                <td><span class="{{ $g->score>=75?'b-pass':'b-fail' }}">{{ $g->status }}</span></td>
                <td class="d-flex gap-1">
                    <a href="{{ route('teacher.grades.edit',$g) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form method="POST" action="{{ route('teacher.grades.destroy',$g) }}" style="display:inline"
                          onsubmit="return confirm('Delete this record?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" class="text-center text-muted py-4">No grade records yet. Click "Add Grade" to start.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($grades->hasPages())
    <div class="p-3">{{ $grades->links() }}</div>
    @endif
</div>

{{-- Add Modal --}}
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('teacher.grades.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-1"></i>Add Grade Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Student</label>
                        <select name="student_id" class="form-select @error('student_id') is-invalid @enderror" required>
                            <option value="">Select a student…</option>
                            @foreach($students as $s)
                            <option value="{{ $s->id }}" {{ old('student_id')==$s->id?'selected':'' }}>
                                {{ $s->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('student_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subject</label>
                        <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror"
                               value="{{ old('subject') }}" placeholder="e.g. Math 101" required>
                        @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Score (0–100)</label>
                        <input type="number" name="score" min="0" max="100" step="0.01"
                               class="form-control @error('score') is-invalid @enderror"
                               value="{{ old('score') }}" required>
                        @error('score')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label">Semester</label>
                            <select name="semester" class="form-select">
                                @foreach(['1st Semester','2nd Semester','Summer'] as $s)
                                <option value="{{ $s }}" {{ old('semester')===$s?'selected':'' }}>{{ $s }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">School Year</label>
                            <select name="school_year" class="form-select">
                                @foreach(['2024-2025','2025-2026','2026-2027'] as $y)
                                <option value="{{ $y }}" {{ old('school_year','2025-2026')===$y?'selected':'' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Add Grade</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    @if($errors->any()) new bootstrap.Modal(document.getElementById('addModal')).show(); @endif
</script>
@endpush
