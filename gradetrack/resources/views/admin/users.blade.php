@extends('layouts.app')
@section('title','Users & Approval')

@section('content')

{{-- Filters --}}
<form method="GET" class="row g-2 mb-3">
    <div class="col-auto">
        <select name="role" class="form-select form-select-sm">
            <option value="">All Roles</option>
            @foreach(['admin','teacher','student'] as $r)
            <option value="{{ $r }}" {{ request('role')===$r?'selected':'' }}>{{ ucfirst($r) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-auto">
        <select name="status" class="form-select form-select-sm">
            <option value="">All Status</option>
            @foreach(['pending','approved','rejected'] as $s)
            <option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-auto">
        <button class="btn btn-sm btn-outline-secondary">Filter</button>
        <a href="{{ route('admin.users') }}" class="btn btn-sm btn-ghost">Clear</a>
    </div>
    <div class="col-auto ms-auto">
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="bi bi-person-plus me-1"></i>Add User
        </button>
    </div>
</form>

<div class="data-card">
    <div class="dc-header"><span class="dctitle">All Users ({{ $users->total() }})</span></div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr>
                <th>#</th><th>Name</th><th>Email</th><th>Role</th>
                <th>Status</th><th>Registered</th><th>Actions</th>
            </tr></thead>
            <tbody>
            @forelse($users as $u)
            <tr>
                <td class="text-muted">{{ $loop->iteration }}</td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:30px;height:30px;border-radius:50%;background:#dbeafe;color:#1d4ed8;font-size:.7rem;font-weight:700;display:flex;align-items:center;justify-content:center">
                            {{ $u->initials }}
                        </div>
                        {{ $u->name }}
                    </div>
                </td>
                <td>{{ $u->email }}</td>
                <td><span class="b-{{ $u->role }}">{{ ucfirst($u->role) }}</span></td>
                <td>
                    <span class="b-{{ $u->status === 'approved' ? 'pass' : ($u->status === 'rejected' ? 'fail' : 'pending') }}">
                        {{ ucfirst($u->status) }}
                    </span>
                </td>
                <td class="text-muted" style="font-size:.8rem">{{ $u->created_at->format('M d, Y') }}</td>
                <td>
                    <div class="d-flex gap-1 flex-wrap">
                        @if($u->status === 'pending')
                        <form method="POST" action="{{ route('admin.users.approve',$u) }}" style="display:inline">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm btn-success" title="Approve">
                                <i class="bi bi-check-lg"></i>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.users.reject',$u) }}" style="display:inline">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm btn-outline-danger" title="Reject">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </form>
                        @elseif($u->status === 'rejected')
                        <form method="POST" action="{{ route('admin.users.approve',$u) }}" style="display:inline">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm btn-outline-success btn-sm" title="Re-approve">
                                <i class="bi bi-arrow-clockwise"></i>
                            </button>
                        </form>
                        @endif
                        <a href="{{ route('admin.users.edit',$u) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.users.destroy',$u) }}" style="display:inline"
                              onsubmit="return confirm('Delete {{ $u->name }}?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center text-muted py-4">No users found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="p-3">{{ $users->appends(request()->query())->links() }}</div>
    @endif
</div>

{{-- Add User Modal --}}
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-person-plus me-1"></i>Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="admin">Admin</option>
                            <option value="teacher" selected>Teacher</option>
                            <option value="student">Student</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                               placeholder="Min. 8 characters" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="alert alert-info py-2" style="font-size:.82rem">
                        <i class="bi bi-info-circle me-1"></i>Admin-created accounts are auto-approved.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add User</button>
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
