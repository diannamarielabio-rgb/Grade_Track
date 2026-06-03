<?php
namespace App\Http\Controllers;

use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function dashboard()
    {
        $sid = Auth::id();

        $totalGrades  = Grade::where('student_id', $sid)->count();
        $passingCount = Grade::where('student_id', $sid)->where('score','>=',75)->count();
        $failingCount = $totalGrades - $passingCount;
        $avgGrade     = round(Grade::where('student_id', $sid)->avg('score') ?? 0, 1);

        $gradeDist = [
            'A' => Grade::where('student_id',$sid)->where('score','>=',90)->count(),
            'B' => Grade::where('student_id',$sid)->whereBetween('score',[80,89.99])->count(),
            'C' => Grade::where('student_id',$sid)->whereBetween('score',[75,79.99])->count(),
            'D' => Grade::where('student_id',$sid)->whereBetween('score',[70,74.99])->count(),
            'F' => Grade::where('student_id',$sid)->where('score','<',70)->count(),
        ];

        $recentGrades = Grade::where('student_id', $sid)
            ->with('teacher')
            ->orderBy('created_at','desc')
            ->take(5)->get();

        return view('student.dashboard', compact(
            'totalGrades','passingCount','failingCount','avgGrade',
            'gradeDist','recentGrades'
        ));
    }

    public function grades(Request $request)
    {
        $query = Grade::where('student_id', Auth::id())->with('teacher');

        if ($request->filled('semester'))    $query->where('semester', $request->semester);
        if ($request->filled('school_year')) $query->where('school_year', $request->school_year);
        if ($request->filled('subject'))     $query->where('subject', 'like', '%'.$request->subject.'%');

        $grades = $query->orderBy('created_at','desc')->paginate(15);

        // For filter dropdowns
        $semesters   = Grade::where('student_id', Auth::id())->distinct()->pluck('semester');
        $schoolYears = Grade::where('student_id', Auth::id())->distinct()->pluck('school_year');

        return view('student.grades', compact('grades','semesters','schoolYears'));
    }
}
