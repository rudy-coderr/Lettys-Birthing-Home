<?php
namespace App\Http\Controllers\Staff;

use App\Models\Branch;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends BaseStaffController
{
    public function staffProfile()
    {
        $user = Auth::user();

        $staff = Staff::with(['user', 'workDays', 'branch'])
            ->where('user_id', $user->id)
            ->firstOrFail();

        $branches = Branch::all();

        return view('staff.profile.staffProfile', compact('staff', 'branches'));
    }
    public function updateProfile(Request $request)
    {
        $user  = Auth::user();
        $staff = Staff::with('user')->where('user_id', $user->id)->firstOrFail();

        $validatedStaff = $request->validate([
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'phone'         => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'gender'        => 'required|in:male,female,other',
            'address'       => 'required|string|max:500',
        ]);

        $validatedUser = $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        // Update staff info
        $staff->update($validatedStaff);

        // Update user email
        $user->update($validatedUser);

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    //  public function updateProfile(Request $request)
    // {
    //     $request->validate([
    //         'staff_id'      => 'required|string|max:255|unique:staff,staff_id,' . Auth::user()->staff->id,
    //         'first_name'    => 'required|string|max:255',
    //         'last_name'     => 'required|string|max:255',
    //         'email'         => 'required|email|max:255|unique:users,email,' . Auth::id(),
    //         'phone'         => 'nullable|string|max:20|regex:/^\+?\d{10,15}$/',
    //         'date_of_birth' => 'required|date',
    //         'gender'        => 'required|in:male,female,other',
    //         'address'       => 'nullable|string|max:255',
    //     ]);

    //     $user  = Auth::user();
    //     $staff = $user->staff;

    //     // Update user email
    //     $user->email = $request->email;
    //     $user->save();

    //     // Update only personal info on staff
    //     $staff->staff_id      = $request->staff_id;
    //     $staff->first_name    = $request->first_name;
    //     $staff->last_name     = $request->last_name;
    //     $staff->phone         = $request->phone;
    //     $staff->date_of_birth = $request->date_of_birth;
    //     $staff->gender        = $request->gender;
    //     $staff->address       = $request->address;
    //     $staff->save();

    //     return redirect()->back()
    //         ->with('message', 'Personal information updated successfully!')
    //         ->with('type', 'success');
    // }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $user  = Auth::user();
        $staff = $user->staff; // Make sure User has a 'staff' relationship

        if (! $staff) {
            return redirect()->back()->with('message', 'Staff profile not found.')->with('type', 'error');
        }

        // Delete old avatar if it exists
        if ($staff->avatar_path && file_exists(public_path($staff->avatar_path))) {
            unlink(public_path($staff->avatar_path));
        }

        // Save new avatar
        $file     = $request->file('avatar');
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        $path     = 'images/avatars/' . $filename;
        $file->move(public_path('images/avatars'), $filename);

        $staff->avatar_path = $path;
        $staff->save();

        return redirect()->back()->with('message', 'Profile picture updated!')->with('type', 'success');
    }

    public function changePassword(Request $request)
    {
        try {
            $request->validate([
                'current_password' => 'required',
                'new_password'     => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',

                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).+$/',
                ],
            ], [
                'new_password.regex'     => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*?&).',
                'new_password.confirmed' => 'New password and confirm password do not match.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->with('swal', [
                'icon'  => 'error',
                'title' => 'Invalid Password',
                'text'  => $e->validator->errors()->first('new_password'),
            ]);
        }

        $user = Auth::user();

        if (! Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('swal', [
                'icon'  => 'error',
                'title' => 'Incorrect Password',
                'text'  => 'The current password you entered is incorrect.',
            ]);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('swal', [
            'icon'  => 'success',
            'title' => 'Password Changed!',
            'text'  => 'Your password has been updated successfully.',
        ]);
    }

}
