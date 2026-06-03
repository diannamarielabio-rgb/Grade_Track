@extends('layouts.app')
@section('title','My Profile')

@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="data-card text-center p-4">
            @if($user->profile_picture)
                <img src="{{ asset('storage/'.$user->profile_picture) }}"
                     class="rounded-circle mb-3" width="86" height="86" style="object-fit:cover">
            @else
                <div style="width:80px;height:80px;border-radius:50%;background:#dbeafe;color:#1d4ed8;font-size:1.5rem;font-weight:700;display:flex;align-items:center;justify-content:center;margin:0 auto 14px">
                    {{ $user->initials }}
                </div>
            @endif
            <h5 class="mb-1">{{ $user->name }}</h5>
            <span class="b-{{ $user->role }}">{{ ucfirst($user->role) }}</span>
            <span class="ms-1 b-{{ $user->status==='approved'?'pass':($user->status==='rejected'?'fail':'pending') }}">
                {{ ucfirst($user->status) }}
            </span>
            <div class="mt-3 d-flex gap-2 justify-content-center">
                <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-pencil me-1"></i>Edit Profile
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="data-card mb-3">
            <div class="dc-header"><span class="dctitle">Profile Information</span></div>
            <div class="p-4">
                @foreach([
                    ['bi-person','Full Name',$user->name],
                    ['bi-envelope','Email',$user->email],
                    ['bi-telephone','Phone',$user->phone??'—'],
                    ['bi-geo-alt','Address',$user->address??'—'],
                    ['bi-gender-ambiguous','Gender',$user->gender??'—'],
                    ['bi-calendar','Member Since',$user->created_at->format('F d, Y')],
                ] as [$icon,$label,$val])
                <div class="d-flex py-2 border-bottom" style="font-size:.88rem">
                    <div style="width:130px;color:#64748b;flex-shrink:0"><i class="bi {{ $icon }} me-1"></i>{{ $label }}</div>
                    <div>{{ $val }}</div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="data-card">
            <div class="dc-header"><span class="dctitle">Change Password</span></div>
            <div class="p-4">
            <form method="POST" action="{{ route('profile.password') }}">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password"
                               class="form-control @error('current_password') is-invalid @enderror" required>
                        @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password"
                               class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-warning btn-sm">
                            <i class="bi bi-lock me-1"></i>Update Password
                        </button>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
@endsection
