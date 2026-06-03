<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()  { return view('profile.show',  ['user' => Auth::user()]); }
    public function edit()  { return view('profile.edit',  ['user' => Auth::user()]); }

    public function update(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name'    => ['required','string','max:255'],
            'email'   => ['required','email','unique:users,email,'.$user->id],
            'phone'   => ['nullable','string','max:20'],
            'address' => ['nullable','string','max:255'],
            'gender'  => ['nullable','in:Male,Female,Other'],
        ]);
        $user->update($request->only('name','email','phone','address','gender'));
        return redirect()->route('profile.show')
            ->with('toast_success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password'         => ['required','confirmed','min:8'],
        ]);
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }
        Auth::user()->update(['password' => Hash::make($request->password)]);
        return redirect()->route('profile.show')
            ->with('toast_success', 'Password updated successfully!');
    }

    public function uploadPicture(Request $request)
    {
        $request->validate([
            'profile_picture' => ['required','image','mimes:jpg,jpeg,png','max:2048'],
        ]);
        $user = Auth::user();
        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }
        $path = $request->file('profile_picture')->store('profiles','public');
        $user->update(['profile_picture' => $path]);
        return redirect()->route('profile.show')
            ->with('toast_success', 'Profile photo updated!');
    }
}
