@extends('layouts.auth')
@section('title','Login') @section('subtitle','Sign in to your account')

@section('content')
<form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="mb-3">
        <label class="form-label fw-semibold">Email address</label>
        <input type="email" name="email" value="{{ old('email') }}" autofocus
               class="form-control @error('email') is-invalid @enderror"
               placeholder="you@school.edu.ph" required>
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label class="form-label fw-semibold">Password</label>
        <input type="password" name="password"
               class="form-control @error('password') is-invalid @enderror"
               placeholder="••••••••" required>
        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3 form-check">
        <input type="checkbox" name="remember" class="form-check-input" id="rem">
        <label class="form-check-label" for="rem">Remember me</label>
    </div>
    <button type="submit" class="btn btn-primary w-100 py-2">
        <i class="bi bi-box-arrow-in-right me-1"></i>Sign In
    </button>
</form>
<p class="text-center mt-3 mb-0" style="font-size:.88rem">
    No account? <a href="{{ route('register') }}">Register here</a>
</p>
@endsection