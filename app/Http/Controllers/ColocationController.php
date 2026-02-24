<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreColocationRequest;
use App\Models\Colocation;
use Illuminate\Http\Request;

class ColocationController extends Controller
{

    /**
     * Display a listing of the user's colocations.
     */
    public function index()
    {
        $user = auth()->user();
        $colocations = $user->colocations()
            ->wherePivotNull('left_at')
            ->orderBy('status', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('colocations.index', compact('colocations'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('colocations.create');
    }

    /**
     * Store new colocation
     */
    public function store(StoreColocationRequest $request)
    {


        $user = auth()->user();

        // Check if user already has active colocation
        $hasActive = $user->colocations()
            ->wherePivotNull('left_at')
            ->where('status', 'active')
            ->exists();

        if ($hasActive) {
            return back()->with('error', 'Vous avez deja une colocation active');
        }

        $colocation = Colocation::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => 'active',
        ]);

        //  Attach owner in pivot
        $colocation->users()->attach($user->id, [
            'internal_role' => 'owner',
            'joined_at' => now(),
        ]);

        return redirect()->route('colocations.show', $colocation);
    }

    /**
     * Show colocation page
     */
    public function show(Colocation $colocation)
    {
        $isMember = $colocation->users()
            ->where('user_id', auth()->id())
            ->wherePivotNull('left_at')
            ->exists();

        if (!$isMember) {
            abort(403);
        }

        $isOwner = $colocation->users()
            ->where('user_id', auth()->id())
            ->wherePivot('internal_role', 'owner')
            ->wherePivotNull('left_at')
            ->exists();

        $colocation->load([
            'users' => function ($query) {
                $query->wherePivotNull('left_at')->with('role');
            },
            'invitations',
            'categories',
            'expenses.category'
        ]);

        return view('colocations.show', compact('colocation', 'isOwner'));
    }

    /**
     * Cancel colocation (owner only)
     */
    public function cancel(Colocation $colocation)
    {
        $userId = auth()->id();

        // Check if current user is owner
        $isOwner = $colocation->users()
            ->where('user_id', $userId)
            ->wherePivot('internal_role', 'owner')
            ->wherePivotNull('left_at')
            ->exists();

        if (!$isOwner) {
            abort(403);
        }

        $colocation->update([
            'status' => 'cancelled',
        ]);

        return back()->with('status', 'Colocation cancelled');
    }

    /**
     * Delete colocation (owner only)
     */
    public function destroy(Colocation $colocation)
    {
        $userId = auth()->id();

        // Check if current user is owner
        $isOwner = $colocation->users()
            ->where('user_id', $userId)
            ->wherePivot('internal_role', 'owner')
            ->wherePivotNull('left_at')
            ->exists();

        if (!$isOwner) {
            abort(403);
        }

        $colocation->delete();

        return redirect()->route('colocations.index')->with('status', 'Colocation supprimée avec succès');
    }

    /**
     * Update colocation (owner only)
     */
    public function update(Request $request, Colocation $colocation)
    {
        $userId = auth()->id();

        // Check if current user is owner
        $isOwner = $colocation->users()
            ->where('user_id', $userId)
            ->wherePivot('internal_role', 'owner')
            ->wherePivotNull('left_at')
            ->exists();

        if (!$isOwner) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $colocation->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return back()->with('status', 'Colocation mise à jour');
    }

    /**
     * Leave colocation (member only)
     */
    public function leave(Colocation $colocation)
    {
        $user = auth()->user();

        // Ensure user is a member and not the owner
        $pivot = $colocation->users()
            ->where('user_id', $user->id)
            ->wherePivotNull('left_at')
            ->first();

        if (!$pivot) {
            return back()->with('error', 'Vous n\'êtes pas membre de cette colocation.');
        }

        if ($pivot->pivot->internal_role === 'owner') {
            return back()->with('error', 'Le propriétaire ne peut pas quitter la colocation. Vous devez la supprimer ou l\'annuler.');
        }

        $colocation->users()->updateExistingPivot($user->id, [
            'left_at' => now(),
        ]);

        return redirect()->route('colocations.index')->with('status', 'Vous avez quitté la colocation.');
    }
}
