<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): View
    {
        return view('auth.register', ['email' => $request->query('email')]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
  public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    $isFirstUser = User::count() === 0;
    $roleName = $isFirstUser ? 'admin' : 'user';

    $role = Role::where('name', $roleName)->firstOrFail();

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role_id' => $role->id,
    ]);

    event(new Registered($user));

    Auth::login($user);

    // Handle Invitation Token after Registration
    if (session()->has('invitation_token')) {
        $token = session()->get('invitation_token');
        $invitation = \App\Models\Invitation::where('token', $token)
            ->where('status', 'pending')
            ->first();

        if ($invitation && $invitation->email === $user->email) {
            $invitation->colocation->users()->attach($user->id, [
                'internal_role' => 'member',
                'joined_at' => now(),
            ]);
            $invitation->update(['status' => 'accepted']);
            session()->forget('invitation_token');

            return redirect()->route('dashboard')->with('status', 'Bienvenue ! Vous avez rejoint la colocation ' . $invitation->colocation->name);
        }
    }

    return redirect(route('dashboard', absolute: false));
}
}
