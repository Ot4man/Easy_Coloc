<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Colocation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class InvitationController extends Controller
{
    /**
     * Voir le formulaire d'invitation
     */
    public function create()
    {
        if (Auth::user()->isBanned()) {
            abort(403, "Votre compte est banni.");
        }
        return view('invitations.create');
    }

    /**
     * Envoyer l'invitation via Mailtrap
     */
    public function send(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = Auth::user();

        //verify if user is banned
        if ($user->isBanned()) {
            return back()->with('error', 'You are banned');
        }

        // find active colocation of user
        $activeColocation = $user->colocations()
            ->where('status', 'active')
            ->wherePivotNull('left_at')
            ->first();

        if (!$activeColocation) {
            return back()->with('error', 'Vous devez faire partie d\'une colocation active pour inviter quelqu\'un.');
        }

        // create invitation with token
        $token = Str::random(60);
        Invitation::create([
            'email' => $request->email,
            'token' => $token,
            'colocation_id' => $activeColocation->id,
            'status' => 'pending'
        ]);

        // send email via mailtrap
        $link = route('invitations.accept', $token);

        try {
            Mail::raw("Hello you are invited to the colocation : {$activeColocation->name}. Click here to jpoin: $link", function ($message) use ($request) {
                $message->to($request->email)->subject('Invitaion of Colocation - Easy Coloc');
            });
        } catch (\Exception $e) {

            return back()->with('error', "invitation saved but not sent");
        }

        return back()->with('status', 'Invitation sent ');
    }

    /**
     * Accepter l'invitation -
     */
    public function accept($token)
    {
        $invitation = Invitation::where('token', $token)
            ->where('status', 'pending')
            ->firstOrFail();

        //  if user not connected ,redirect to register with email
        if (!Auth::check()) {
            session(['invitation_token' => $token]);
            return redirect()->route('register', ['email' => $invitation->email])
                ->with('status', 'Veuillez créer un compte pour rejoindre la colocation.');
        }

        $user = Auth::user();

        //verify if user is banned
        if ($user->isBanned()) {
            abort(403, "Action interdite : votre compte est banni.");
        }

        // verfier if user email match invitation email
        if ($user->email !== $invitation->email) {
            return redirect()->route('dashboard')->with('error', 'Cette invitation est destinée à un autre email.');
        }

        // verifier if user has already an active colocation
        $hasActive = $user->colocations()
            ->where('status', 'active')
            ->wherePivotNull('left_at')
            ->exists();

        if ($hasActive) {
            return redirect()->route('dashboard')->with('error', 'Vous avez déjà une colocation active.');
        }

        // rejoin colocation
        $invitation->colocation->users()->attach($user->id, [
            'internal_role' => 'member',
            'joined_at' => now(),
        ]);

        // marq invitation as accepted
        $invitation->update(['status' => 'accepted']);
        session()->forget('invitation_token');

        return redirect()->route('dashboard')->with('status', 'Bienvenue dans la colocation ' . $invitation->colocation->name . ' !');
    }

    /**
     * Refuser l'invitation 
     */
    public function refuse($token)
    {
        $invitation = Invitation::where('token', $token)
            ->where('status', 'pending')
            ->firstOrFail();

        $invitation->update(['status' => 'refused']);

        return redirect()->route('dashboard')->with('status', 'Invitation refusée.');
    }
}

