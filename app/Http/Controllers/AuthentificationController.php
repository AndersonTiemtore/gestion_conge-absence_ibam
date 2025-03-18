<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Illuminate\Support\Str;

class AuthentificationController extends Controller
{
    //
    public function loginForm(): View
    {
        $pageConfigs = ['myLayout' => 'blank'];
        return view('authentifcation.login', compact('pageConfigs'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Les informations saisies ne sont pas correctes.',
        ])->onlyInput('email');
    }

    public function passwordForgottenForm(): View
    {
        $pageConfigs = ['myLayout' => 'blank'];

        return view('authentifcation.passwordForgotten', compact('pageConfigs'));
    }

    public function passwordForgotten(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $result = Password::sendResetLink($request->only('email'));

        if ($result === Password::RESET_LINK_SENT) {
            return redirect()->route('login')->with(['status' => __($result)]);
        }

        return back()->withErrors(['email' => __($result)]);
    }

    public function resetPasswordForm(string $token)
    {
        return view('authentifcation.resetPassword', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PasswordReset
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    public function deconnexion(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function changePasswordForm()
    {
        $pageConfigs = ['myLayout' => 'blank'];

        if (!Hash::check('password', Auth::user()->password)) {
            return redirect()->route('dashboard');
        }
        return view('authentifcation.changePassword', compact('pageConfigs'));
    }

    public function changePassword(Request $request)
    {
        $valid = $request->validate([
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        if ($valid['password'] == 'password') {
            return back()->withErrors([
                'password' => 'Le nouveau mot de passe doit être différent de password',
            ]);
        }

        $user = User::query()->find(Auth::user()->id);

        $user->update([
            'password' => bcrypt($valid['password']),
        ]);
        return redirect()->route('dashboard');
    }
}
