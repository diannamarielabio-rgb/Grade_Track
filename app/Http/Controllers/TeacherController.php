<?php
namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    public function dashboard()
    {
        $tid = Auth::id();

        $totalGrades  = Grade::where('teacher_id', $tid)->count();
        $totalStudents = Grade::where('teacher_id', $tid)
            ->distinct('student_id')->count('student_id');
        $passingCount = Grade::where('teacher_id', $tid)->where('score','>=',75)->count();
        $passingRate  = $totalGrades > 0 ? round(($passingCount/$totalGrades)*100) : 0;
        $avgGrade     = round(Grade::where('teacher_id', $tid)->avg('score') ?? 0, 1);

        $gradeDist = [
            'A' => Grade::where('teacher_id',$tid)->where('score','>=',90)->count(),
            'B' => Grade::where('teacher_id',$tid)->whereBetween('score',[80,89.99])->count(),
            'C' => Grade::where('teacher_id',$tid)->whereBetween('score',[75,79.99])->count(),
            'D' => Grade::where('teacher_id',$tid)->whereBetween('score',[70,74.99])->count(),
            'F' => Grade::where('teacher_id',$tid)->where('score','<',70)->count(),
        ];

        $subjectStats = Grade::where('teacher_id',$tid)
            ->select('subject',
                DB::raw('ROUND(AVG(score),1) as avg_score'),
                DB::raw('COUNT(*) as total'))
            ->groupBy('subject')->get();

        $recentGrades = Grade::where('teacher_id', $tid)
            ->with('student')->orderBy('created_at','desc')->take(5)->get();

        return view('teacher.dashboard', compact(
            'totalGrades','totalStudents','passingRate','avgGrade',
            'gradeDist','subjectStats','recentGrades'
        ));
    }

    // ── Grades CRUD ────────────────────────────────────────────
    public function grades()
    {
        $grades = Grade::where('teacher_id', Auth::id())
            ->with('student')
            ->orderBy('created_at','desc')
            ->paginate(15);

        $students = User::where('role','student')
            ->where('status','approved')
            ->orderBy('name')->get();

        return view('teacher.grades', compact('grades','students'));
    }

    public function storeGrade(Request $request)
    {
        $request->validate([
            'student_id'  => ['required','exists:users,id'],
            'subject'     => ['required','string','max:255'],
            'score'       => ['required','numeric','min:0','max:100'],
            'semester'    => ['required','string'],
            'school_year' => ['required','string'],
        ]);

        Grade::create([
            'student_id'  => $request->student_id,
            'teacher_id'  => Auth::id(),
            'subject'     => $request->subject,
            'score'       => $request->score,
            'semester'    => $request->semester,
            'school_year' => $request->school_year,
        ]);

        return back()->with('toast_success', 'Grade added successfully!');
    }

    public function editGrade(Grade $grade)
    {
        abort_if($grade->teacher_id !== Auth::id(), 403);
        $students = User::where('role','student')->where('status','approved')->orderBy('name')->get();
        return view('teacher.edit-grade', compact('grade','students'));
    }

    public function updateGrade(Request $request, Grade $grade)
    {
        abort_if($grade->teacher_id !== Auth::id(), 403);

        $request->validate([
            'student_id'  => ['required','exists:users,id'],
            'subject'     => ['required','string','max:255'],
            'score'       => ['required','numeric','min:0','max:100'],
            'semester'    => ['required','string'],
            'school_year' => ['required','string'],
        ]);

        $grade->update($request->only('student_id','subject','score','semester','school_year'));

        return redirect()->route('teacher.grades')
            ->with('toast_success', 'Grade updated successfully!');
    }

    public function destroyGrade(Grade $grade)
    {
        abort_if($grade->teacher_id !== Auth::id(), 403);
        $grade->delete();
        return back()->with('toast_success', 'Grade deleted.');
    }
}
