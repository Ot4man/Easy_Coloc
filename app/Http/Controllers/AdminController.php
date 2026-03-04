<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Colocation;
use App\Models\Expense;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    public function index() {
        $stats = [
            'total_users' => User::count(),
            'total_colocations' => Colocation::count(),
            'total_expenses' => Expense::sum('amount'),
            'total_banned' => User::where('is_banned', true)->count(),
        ];

        $users = User::with('role')->get();
        return view('admin.dashboard', compact('users', 'stats'));
    }

    public function export()
    {
        $stats = [
            ['Statistique', 'Valeur'],
            ['Total Utilisateurs', User::count()],
            ['Total Colocations', Colocation::count()],
            ['Total Dépenses', Expense::sum('amount') . ' DH'],
            ['Utilisateurs Bannis', User::where('is_banned', true)->count()],
        ];

        $response = new StreamedResponse(function () use ($stats) {
            $handle = fopen('php://output', 'w');
            foreach ($stats as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="statistiques_admin.csv"');

        return $response;
    }

    public function toggleBan(User $user) {
        if ($user->id === auth()->id())
            return back()->with('error', 'Impossible to ban yourself');

        $user->update(['is_banned' => !$user->is_banned]);

        return back()->with('status', 'Statut mis à jour');
    }
}
