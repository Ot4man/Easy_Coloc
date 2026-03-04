<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreColocationRequest;
use App\Models\Colocation;
use App\Models\User;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ColocationController extends Controller
{

    /**
     * Display a listing of the user's colocations.
     */
    public function index()
    {
        $user = Auth::user();
        $colocations = $user->colocations()->wherePivotNull('left_at')->orderBy('status', 'asc')->orderBy('created_at', 'desc')->get();

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


        $user = Auth::user();

        // Check if user already has active colocation
        $hasActive = $user->colocations()->wherePivotNull('left_at')->where('status', 'active')->exists();
        if ($hasActive) {
            return back()->with('error', 'You already in colocation');
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
        $isMember = $colocation->users()->where('user_id', Auth::id())->wherePivotNull('left_at')->exists();

        if (!$isMember) {
            abort(403);
        }

        $isOwner = $colocation->users()
            ->where('user_id', Auth::id())
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
     * Cancel colocation
     */
    public function cancel(Colocation $colocation)
    {
        $userId = Auth::id();

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
        $colocation->users()->updateExistingPivot(
            $userId,
            ['left_at' => now()]
        );


            // dd($isOwner);

        return redirect()->route('colocations.index')->with('status', 'Colocation cancelled');
    }

    /**
     * Delete colocation
     */
    public function destroy(Colocation $colocation)
    {
        $userId = Auth::id();

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
    public function update(StoreColocationRequest $request, Colocation $colocation)
    {
        $userId = Auth::id();
        // Check if current user is owner
        $isOwner = $colocation->users()->where('user_id', $userId)->wherePivot('internal_role', 'owner')->wherePivotNull('left_at')->exists();
        if (!$isOwner) {
            abort(403);
        }

        $colocation->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return back()->with('status', 'Updated');
    }

    /**
     * Leave colocation
     */
    public function leave(Colocation $colocation)
    {
        $user = Auth::user();

        // Ensure user is a member and not the owner
        $pivot = $colocation->users()->where('user_id', $user->id)->wherePivotNull('left_at')->first();

        if ($pivot->pivot->internal_role === 'owner') {
            return back()->with('error', 'Owner cant leave the colocation');
        }

        //  Reputation System Logic
        $members = $colocation->users()->wherePivotNull('left_at')->get();
        $memberCount = $members->count();
        $unpaidExpenses = Expense::where('colocation_id', $colocation->id)->where('is_paid', false)->get();

        $totalPaid = $unpaidExpenses->where('user_id', $user->id)->sum('amount');
        $totalShare = 0;
        foreach ($unpaidExpenses as $expense) {
            $totalShare += ($expense->amount / $memberCount);
        }

        $balance = $totalPaid - $totalShare;

        if ($balance < 0) {
            $user->reputation_score -= 1;
        } else {
            $user->reputation_score += 1;
        }
        $user->save();

        $colocation->users()->updateExistingPivot($user->id, [
            'left_at' => now(),
        ]);

        return redirect()->route('colocations.index')->with('status', 'You leaved the colocation');
    }

    /**
     * Remove member
     */
    public function removeMember(Colocation $colocation, User $user)
    {
        $owner = Auth::user();

        // Ensure current user is the owner
        $isOwner = $colocation->users()->where('user_id', $owner->id)->wherePivot('internal_role', 'owner')->wherePivotNull('left_at')->exists();

        if (!$isOwner) {
            abort(403);
        }

        // Ensure user to be removed is a member and not the owner
        $pivot = $colocation->users()->where('user_id', $user->id)->wherePivotNull('left_at')->first();

        if (!$pivot || $pivot->id === $owner->id) {
            return back()->with('error', 'Impossible de retirer ce membre.');
        }

        // --- Reputation System Logic ---
        $members = $colocation->users()->wherePivotNull('left_at')->get();
        $memberCount = $members->count();
        $unpaidExpenses = Expense::where('colocation_id', $colocation->id)
            ->where('is_paid', false)
            ->get();

        $totalPaid = $unpaidExpenses->where('user_id', $user->id)->sum('amount');
        $totalShare = 0;
        foreach ($unpaidExpenses as $expense) {
            $totalShare += ($expense->amount / $memberCount);
        }

        $balance = $totalPaid - $totalShare;

        if ($balance < 0) {
            $user->reputation_score -= 1;

            // Transfer debt to owne

            Expense::create([
                'title' => "Dette reprise de " . $user->name,
                'amount' => abs($balance),
                'date' => now(),
                'colocation_id' => $colocation->id,
                'user_id' => $owner->id,
                'category_id' => $colocation->categories->first()->id ?? 1,
                'is_paid' => false
            ]);
        } else {
            $user->reputation_score += 1;
        }
        $user->save();
        

        $colocation->users()->updateExistingPivot($user->id, [
            'left_at' => now(),
        ]);

        return back()->with('status', 'Membre retiré et réputation mise à jour.');
    }
}
