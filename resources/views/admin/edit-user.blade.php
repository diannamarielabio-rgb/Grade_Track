@extends('layouts.app')
@section('title','Edit User')

@section('content')
<div class="row justify-content-center">
<div class="col-md-6">
<div class="data-card">
    <div class="dc-header">
        <span class="dctitle">Edit User — {{ $user->name }}</span>
        <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
    </div>
    <div class="p-4">
    <form method="POST" action="{{ route('admin.users.update',$user) }}">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name',$user->name) }}" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email',$user->email) }}" required>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role" class="form-select">
                @foreach(['admin','teacher','student'] as $r)
                <option value="{{ $r }}" {{ old('role',$user->role)===$r?'selected':'' }}>{{ ucfirst($r) }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                @foreach(['pending','approved','rejected'] as $s)
                <option value="{{ $s }}" {{ old('status',$user->status)===$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
    </div>
</div>
</div>
</div>
@endsection
