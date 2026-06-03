<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GradeTrack – @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --sidebar-w: 220px; }
        body  { background:#f1f5f9; min-height:100vh; }

        /* Sidebar */
        #sidebar {
            position:fixed; top:0; left:0; height:100vh; width:var(--sidebar-w);
            background:#0f172a; display:flex; flex-direction:column; z-index:200;
        }
        .sb-logo {
            padding:18px 16px 14px; font-size:1.05rem; font-weight:700; color:#fff;
            border-bottom:1px solid rgba(255,255,255,.07);
            display:flex; align-items:center; gap:9px;
        }
        .sb-logo i { color:#60a5fa; font-size:1.2rem; }
        .sb-role-badge {
            font-size:10px; padding:2px 8px; border-radius:20px; font-weight:600;
            margin-left:auto; letter-spacing:.04em;
        }
        .sb-role-admin   { background:#1e40af; color:#bfdbfe; }
        .sb-role-teacher { background:#065f46; color:#a7f3d0; }
        .sb-role-student { background:#5b21b6; color:#ddd6fe; }
        .sb-section {
            padding:12px 16px 3px; font-size:10px; text-transform:uppercase;
            letter-spacing:.07em; color:rgba(255,255,255,.28); font-weight:600;
        }
        .sb-link {
            color:rgba(255,255,255,.6); padding:9px 16px; font-size:.86rem;
            display:flex; align-items:center; gap:9px; border-left:3px solid transparent;
            transition:all .15s; text-decoration:none;
        }
        .sb-link:hover  { color:#fff; background:rgba(255,255,255,.06); }
        .sb-link.active { color:#fff; background:rgba(255,255,255,.1); border-left-color:#60a5fa; }
        .sb-footer { margin-top:auto; border-top:1px solid rgba(255,255,255,.07); }

        /* Main */
        #main { margin-left:var(--sidebar-w); }
        .topbar {
            background:#fff; border-bottom:1px solid #e2e8f0;
            padding:11px 24px; display:flex; align-items:center;
            justify-content:space-between; position:sticky; top:0; z-index:100;
        }
        .topbar-title { font-weight:600; font-size:.95rem; }
        .avatar-sm {
            width:32px; height:32px; border-radius:50%; background:#dbeafe;
            color:#1d4ed8; font-size:.72rem; font-weight:700;
            display:flex; align-items:center; justify-content:center; flex-shrink:0;
        }
        .content { padding:22px 24px; }

        /* Cards */
        .stat-card { background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:16px 18px; }
        .stat-card .slabel { font-size:.78rem; color:#64748b; margin-bottom:3px; }
        .stat-card .sval   { font-size:1.7rem; font-weight:700; line-height:1; }
        .data-card { background:#fff; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; }
        .dc-header {
            padding:13px 18px; border-bottom:1px solid #e2e8f0;
            display:flex; align-items:center; justify-content:space-between;
        }
        .dc-header .dctitle { font-weight:600; font-size:.92rem; }
        table thead th {
            background:#f8fafc; font-size:.75rem; text-transform:uppercase;
            letter-spacing:.04em; color:#64748b; font-weight:600;
        }
        td { font-size:.87rem; vertical-align:middle; }

        /* Toasts */
        .toast-container { position:fixed; bottom:22px; right:22px; z-index:9999; }

        /* Badges */
        .b-pass    { background:#dcfce7; color:#166534; font-size:.75rem; padding:2px 9px; border-radius:20px; }
        .b-fail    { background:#fee2e2; color:#991b1b; font-size:.75rem; padding:2px 9px; border-radius:20px; }
        .b-pending { background:#fef9c3; color:#854d0e; font-size:.75rem; padding:2px 9px; border-radius:20px; }
        .b-admin   { background:#dbeafe; color:#1e40af; font-size:.75rem; padding:2px 9px; border-radius:20px; }
        .b-teacher { background:#d1fae5; color:#065f46; font-size:.75rem; padding:2px 9px; border-radius:20px; }
        .b-student { background:#ede9fe; color:#5b21b6; font-size:.75rem; padding:2px 9px; border-radius:20px; }
    </style>
    @stack('styles')
</head>
<body>

@php $user = Auth::user(); @endphp

<nav id="sidebar">
    <div class="sb-logo">
        <i class="bi bi-mortarboard-fill"></i> GradeTrack
        <span class="sb-role-badge sb-role-{{ $user->role }}">{{ ucfirst($user->role) }}</span>
    </div>

    @if($user->isAdmin())
        <div class="sb-section">Main</div>
        <a href="{{ route('admin.dashboard') }}" class="sb-link {{ request()->routeIs('admin.dashboard') ? 'active':'' }}">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>
        <div class="sb-section">Manage</div>
        <a href="{{ route('admin.users') }}" class="sb-link {{ request()->routeIs('admin.users*') ? 'active':'' }}">
            <i class="bi bi-people"></i> Users & Approval
        </a>
        <a href="{{ route('admin.grades') }}" class="sb-link {{ request()->routeIs('admin.grades') ? 'active':'' }}">
            <i class="bi bi-journal-text"></i> All Grades
        </a>

    @elseif($user->isTeacher())
        <div class="sb-section">Main</div>
        <a href="{{ route('teacher.dashboard') }}" class="sb-link {{ request()->routeIs('teacher.dashboard') ? 'active':'' }}">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>
        <div class="sb-section">Grades</div>
        <a href="{{ route('teacher.grades') }}" class="sb-link {{ request()->routeIs('teacher.grades*') ? 'active':'' }}">
            <i class="bi bi-journal-text"></i> Manage Grades
        </a>

    @elseif($user->isStudent())
        <div class="sb-section">Main</div>
        <a href="{{ route('student.dashboard') }}" class="sb-link {{ request()->routeIs('student.dashboard') ? 'active':'' }}">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>
        <div class="sb-section">My Grades</div>
        <a href="{{ route('student.grades') }}" class="sb-link {{ request()->routeIs('student.grades') ? 'active':'' }}">
            <i class="bi bi-journal-check"></i> View Grades
        </a>
    @endif

    <div class="sb-section">Account</div>
    <a href="{{ route('profile.show') }}" class="sb-link {{ request()->routeIs('profile.*') ? 'active':'' }}">
        <i class="bi bi-person-circle"></i> My Profile
    </a>

    <div class="sb-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sb-link w-100 border-0 bg-transparent text-start" style="cursor:pointer">
                <i class="bi bi-box-arrow-left"></i> Logout
            </button>
        </form>
    </div>
</nav>

<div id="main">
    <div class="topbar">
        <div class="topbar-title">@yield('title')</div>
        <div class="d-flex align-items-center gap-2">
            @if($user->profile_picture)
                <img src="{{ asset('storage/'.$user->profile_picture) }}" class="avatar-sm" style="object-fit:cover">
            @else
                <div class="avatar-sm">{{ $user->initials }}</div>
            @endif
            <span style="font-size:.85rem" class="d-none d-sm-inline">{{ $user->name }}</span>
        </div>
    </div>

    <div class="content">
        @yield('content')
    </div>
</div>

{{-- Toast --}}
<div class="toast-container">
    @foreach(['toast_success'=>'success','toast_error'=>'danger'] as $key=>$type)
    @if(session($key))
    <div class="toast align-items-center text-bg-{{ $type }} border-0" role="alert" data-bs-autohide="true" data-bs-delay="4000">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-{{ $type==='success'?'check':'exclamation' }}-circle me-2"></i>
                {{ session($key) }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
    @endif
    @endforeach
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('.toast').forEach(el => new bootstrap.Toast(el).show());
</script>
@stack('scripts')
</body>
</html>
