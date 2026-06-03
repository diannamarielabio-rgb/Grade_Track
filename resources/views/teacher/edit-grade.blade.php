@extends('layouts.app')
@section('title','Edit Grade')

@section('content')
<div class="row justify-content-center">
<div class="col-md-6">
<div class="data-card">
    <div class="dc-header">
        <span class="dctitle">Edit Grade Record</span>
        <a href="{{ route('teacher.grades') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
    </div>
    <div class="p-4">
    <form method="POST" action="{{ route('teacher.grades.update',$grade) }}">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Student</label>
            <select name="student_id" class="form-select @error('student_id') is-invalid @enderror" required>
                @foreach($students as $s)
                <option value="{{ $s->id }}" {{ old('student_id',$grade->student_id)==$s->id?'selected':'' }}>
                    {{ $s->name }}
                </option>
                @endforeach
            </select>
            @error('student_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Subject</label>
            <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror"
                   value="{{ old('subject',$grade->subject) }}" required>
            @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Score (0–100)</label>
            <input type="number" name="score" min="0" max="100" step="0.01"
                   class="form-control @error('score') is-invalid @enderror"
                   value="{{ old('score',$grade->score) }}" required>
            @error('score')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="row g-2 mb-4">
            <div class="col-6">
                <label class="form-label">Semester</label>
                <select name="semester" class="form-select">
                    @foreach(['1st Semester','2nd Semester','Summer'] as $s)
                    <option value="{{ $s }}" {{ old('semester',$grade->semester)===$s?'selected':'' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6">
                <label class="form-label">School Year</label>
                <select name="school_year" class="form-select">
                    @foreach(['2024-2025','2025-2026','2026-2027'] as $y)
                    <option value="{{ $y }}" {{ old('school_year',$grade->school_year)===$y?'selected':'' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="{{ route('teacher.grades') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
    </div>
</div>
</div>
</div>
@endsection
