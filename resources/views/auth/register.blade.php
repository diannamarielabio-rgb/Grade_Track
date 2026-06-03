@extends('layouts.auth')
@section('title','Register') @section('subtitle','Create a new account')

@section('content')
<form method="POST" action="{{ route('register') }}">
    @csrf
    <div class="mb-3">
        <label class="form-label fw-semibold">Full Name</label>
        <input type="text" name="name" value="{{ old('name') }}" autofocus
               class="form-control @error('name') is-invalid @enderror"
               placeholder="Juan Dela Cruz" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label class="form-label fw-semibold">Email address</label>
        <input type="email" name="email" value="{{ old('email') }}"
               class="form-control @error('email') is-invalid @enderror"
               placeholder="you@school.edu.ph" required>
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label class="form-label fw-semibold">I am a…</label>
        <select name="role" class="form-select @error('role') is-invalid @enderror" required>
            <option value="">Select role</option>
            <option value="teacher" {{ old('role')==='teacher'?'selected':'' }}>Teacher</option>
            <option value="student" {{ old('role')==='student'?'selected':'' }}>Student</option>
        </select>
        <div class="form-text text-muted">Admin accounts are created by existing admins only.</div>
        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label class="form-label fw-semibold">Password</label>
        <input type="password" name="password"
               class="form-control @error('password') is-invalid @enderror"
               placeholder="Min. 8 characters" required>
        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label class="form-label fw-semibold">Confirm Password</label>
        <input type="password" name="password_confirmation" class="form-control"
               placeholder="Re-enter password" required>
    </div>

    <div class="alert alert-info py-2 mb-3" style="font-size:.83rem">
        <i class="bi bi-info-circle me-1"></i>
        Your account will be <strong>reviewed by an admin</strong> before you can log in.
    </div>

    <button type="submit" class="btn btn-primary w-100 py-2">
        <i class="bi bi-person-plus me-1"></i>Create Account
    </button>
</form>
<p class="text-center mt-3 mb-0" style="font-size:.88rem">
    Already have an account? <a href="{{ route('login') }}">Sign in</a>
</p>
@endsection
