<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GradeTrack – @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background:linear-gradient(135deg,#1e3a5f 0%,#0f172a 100%); min-height:100vh; display:flex; align-items:center; justify-content:center; }
        .auth-wrap { width:100%; max-width:430px; padding:16px; }
        .auth-card { background:#fff; border-radius:16px; padding:36px 32px; box-shadow:0 20px 60px rgba(0,0,0,.3); }
        .brand     { font-size:1.5rem; font-weight:800; color:#1d4ed8; letter-spacing:-.02em; }
        .brand i   { margin-right:6px; }
    </style>
</head>
<body>
<div class="auth-wrap">
    <div class="auth-card">
        <div class="text-center mb-4">
            <div class="brand"><i class="bi bi-mortarboard-fill"></i>GradeTrack</div>
            <div class="text-muted mt-1" style="font-size:.88rem">@yield('subtitle')</div>
        </div>

        @if(session('toast_success'))
            <div class="alert alert-success py-2 mb-3">
                <i class="bi bi-check-circle me-1"></i>{{ session('toast_success') }}
            </div>
        @endif

        @yield('content')
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
