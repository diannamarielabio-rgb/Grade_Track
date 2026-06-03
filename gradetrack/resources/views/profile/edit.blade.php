@extends('layouts.app')
@section('title','Edit Profile')

@section('content')
<div class="row justify-content-center">
<div class="col-md-7">
    <div class="data-card mb-4">
        <div class="dc-header">
            <span class="dctitle">Edit Profile Information</span>
            <a href="{{ route('profile.show') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back
            </a>
        </div>
        <div class="p-4">
        <form method="POST" action="{{ route('profile.update') }}">
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
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control"
                       value="{{ old('phone',$user->phone) }}" placeholder="+63 917 xxx xxxx">
            </div>
            <div class="mb-3">
                <label class="form-label">Address</label>
                <input type="text" name="address" class="form-control"
                       value="{{ old('address',$user->address) }}" placeholder="City, Province">
            </div>
            <div class="mb-4">
                <label class="form-label">Gender</label>
                <select name="gender" class="form-select">
                    <option value="">Prefer not to say</option>
                    @foreach(['Male','Female','Other'] as $g)
                    <option value="{{ $g }}" {{ old('gender',$user->gender)===$g?'selected':'' }}>{{ $g }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg me-1"></i>Save Changes
            </button>
        </form>
        </div>
    </div>

    <div class="data-card">
        <div class="dc-header"><span class="dctitle">Profile Photo</span></div>
        <div class="p-4">
            @if($user->profile_picture)
            <img src="{{ asset('storage/'.$user->profile_picture) }}"
                 class="rounded-circle mb-3 d-block" width="70" height="70" style="object-fit:cover">
            @endif
            <form method="POST" action="{{ route('profile.picture') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Upload New Photo</label>
                    <input type="file" name="profile_picture" accept="image/*"
                           class="form-control @error('profile_picture') is-invalid @enderror">
                    <div class="form-text">JPG or PNG, max 2 MB</div>
                    @error('profile_picture')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-upload me-1"></i>Upload Photo
                </button>
            </form>
        </div>
    </div>
</div>
</div>
@endsection
