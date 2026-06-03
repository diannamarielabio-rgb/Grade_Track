<?php
namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // ── Dashboard ──────────────────────────────────────────────
    public function dashboard()
    {
        $totalUsers    = User::where('role', '!=', 'admin')->count();
        $totalStudents = User::where('role', 'student')->count();
        $totalTeachers = User::where('role', 'teacher')->count();
        $pendingCount  = User::where('status', 'pending')->count();
        $totalGrades   = Grade::count();

        $gradeDist = [
            'A' => Grade::where('score', '>=', 90)->count(),
            'B' => Grade::whereBetween('score', [80, 89.99])->count(),
            'C' => Grade::whereBetween('score', [75, 79.99])->count(),
            'D' => Grade::whereBetween('score', [70, 74.99])->count(),
            'F' => Grade::where('score', '<', 70)->count(),
        ];

        $subjectStats = Grade::select('subject',
                DB::raw('ROUND(AVG(score),1) as avg_score'),
                DB::raw('COUNT(*) as total'))
            ->groupBy('subject')->get();

        $pendingUsers = User::where('status', 'pending')
            ->orderBy('created_at','desc')->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers','totalStudents','totalTeachers',
            'pendingCount','totalGrades','gradeDist','subjectStats','pendingUsers'
        ));
    }

    // ── User Management ────────────────────────────────────────
    public function users(Request $request)
    {
        $query = User::query();
        if ($request->filled('role'))   $query->where('role', $request->role);
        if ($request->filled('status')) $query->where('status', $request->status);
        $users = $query->orderBy('created_at','desc')->paginate(15);
        return view('admin.users', compact('users'));
    }

    public function approveUser(User $user)
    {
        $user->update(['status' => 'approved']);
        return back()->with('toast_success', $user->name . "'s account has been approved.");
    }

    public function rejectUser(User $user)
    {
        $user->update(['status' => 'rejected']);
        return back()->with('toast_success', $user->name . "'s account has been rejected.");
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name'     => ['required','string','max:255'],
            'email'    => ['required','email','unique:users,email'],
            'role'     => ['required','in:admin,teacher,student'],
            'password' => ['required','min:8'],
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => $request->role,
            'status'   => 'approved',          // admin-created accounts auto-approved
            'password' => Hash::make($request->password),
        ]);

        return back()->with('toast_success', 'User created and approved successfully!');
    }

    public function editUser(User $user)
    {
        return view('admin.edit-user', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name'   => ['required','string','max:255'],
            'email'  => ['required','email','unique:users,email,'.$user->id],
            'role'   => ['required','in:admin,teacher,student'],
            'status' => ['required','in:pending,approved,rejected'],
        ]);

        $user->update($request->only('name','email','role','status'));

        return redirect()->route('admin.users')
            ->with('toast_success', 'User updated successfully!');
    }

    public function destroyUser(User $user)
    {
        $user->delete();
        return back()->with('toast_success', 'User deleted.');
    }

    // ── All Grades View ────────────────────────────────────────
    public function grades()
    {
        $grades = Grade::with(['student','teacher'])
            ->orderBy('created_at','desc')->paginate(15);
        return view('admin.grades', compact('grades'));
    }
}
