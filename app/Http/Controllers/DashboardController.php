<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Reputation score
        $reputationScore = $user->reputation_score ?? 0;

        // Active Colocation (the latest active one)
        $activeColocation = $user->colocations()
            ->where('status', 'active')
            ->wherePivotNull('left_at')
            ->with(['users' => function($query) {
                $query->wherePivotNull('left_at');
            }])
            ->first();

        // Current month expenses (user paid)
        $currentMonthExpensesSum = 0;
        if ($activeColocation) {
            $currentMonthExpensesSum = Expense::where('colocation_id', $activeColocation->id)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount');
        }

        // Recent expenses in user's colocations
        $colocationIds = $user->colocations()->pluck('colocation_id');
        $recentExpenses = Expense::whereIn('colocation_id', $colocationIds)
            ->with(['user', 'category', 'colocation'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Pending Invitations
        $pendingInvitations = \App\Models\Invitation::where('email', $user->email)
            ->where('status', 'pending')
            ->with('colocation')
            ->get();

        return view('dashboard', compact(
            'reputationScore',
            'activeColocation',
            'currentMonthExpensesSum',
            'recentExpenses',
            'pendingInvitations'
        ));
    }
}
