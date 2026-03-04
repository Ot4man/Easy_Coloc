<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\invitation;
class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Reputation score
        $reputationScore = $user->reputation_score ?? 0;

        // Active Colocation
        $activeColocation = $user->colocations()
            ->where('status', 'active')
            ->wherePivotNull('left_at')
            ->with(['users' => function($query) {
                $query->wherePivotNull('left_at');
            }])
            ->first();

        // Calculate user sold in the active colocation
        $userBalance = 0;
        if ($activeColocation) {
            $members = $activeColocation->users()->wherePivotNull('left_at')->get();
            $memberCount = $members->count();

            if ($memberCount > 0) {
                $unpaidExpenses = Expense::where('colocation_id', $activeColocation->id)
                    ->where('is_paid', false)
                    ->get();

                foreach ($unpaidExpenses as $expense) {
                    $share = $expense->amount / $memberCount;
                    if ($expense->user_id === $user->id) {
                        // User paid the full amount
                        $userBalance += ($expense->amount - $share);
                    } else {
                        // Someone else paid, user owes their share
                        $userBalance -= $share;
                    }
                }
            }
        }

        // Recent expenses in user's colocations
        $colocationIds = $user->colocations()->pluck('colocation_id');
        $recentExpenses = Expense::whereIn('colocation_id', $colocationIds)
            ->with(['user', 'category', 'colocation'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Pending Invitations
        $pendingInvitations = Invitation::where('email', $user->email)
            ->where('status', 'pending')
            ->with('colocation')
            ->get();

        return view('dashboard', compact(
            'reputationScore',
            'activeColocation',
            'userBalance',
            'recentExpenses',
            'pendingInvitations'
        ));
    }
}
