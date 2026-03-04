<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-slate-800 tracking-tight">
                {{ __('Admin Dashboard') }}
            </h2>
        </div>
    </x-slot>
 <!-- Bouton d'Export au bas de la page -->
            <div class="flex justify-end pt-4">
                <a href="{{ route('admin.export') }}" class="inline-flex items-center px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-2xl  transition-all ">
                    <i class="fa-solid fa-file-export w-5 h-5 mr-2"></i>
                    Export stats (CSV)
                </a>
            </div>
    <div class="py-12 bg-slate-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Users -->
                <div class="bg-cyan-50 p-6 rounded-3xl shadow-sm border border-cyan-100 flex items-center space-x-4">
                    <div class="p-4 bg-cyan-100 rounded-2xl text-cyan-600">
                        <i class="fa-solid fa-users text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-1">Total Utilisateurs</p>
                        <p class="text-2xl font-semibold text-slate-800">{{ $stats['total_users'] }}</p>
                    </div>
                </div>

                <!-- Total Colocations -->
                <div class="bg-cyan-50 p-6 rounded-3xl shadow-sm border border-cyan-100 flex items-center space-x-4">
                    <div class="p-4 bg-cyan-100 rounded-2xl text-cyan-600">
                        <i class="fa-solid fa-house text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-1">Colocations</p>
                        <p class="text-2xl font-semibold text-slate-800">{{ $stats['total_colocations'] }}</p>
                    </div>
                </div>

                <!-- Total Expenses -->
                <div class="bg-cyan-50 p-6 rounded-3xl shadow-sm border border-cyan-100 flex items-center space-x-4">
                    <div class="p-4 bg-cyan-100 rounded-2xl text-cyan-600">
                        <i class="fa-solid fa-wallet text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-1">Total Dépenses</p>
                        <p class="text-2xl font-semibold text-slate-800">{{ number_format($stats['total_expenses'], 0, ',', ' ') }} DH</p>
                    </div>
                </div>

                <!-- Banned Users -->
                <div class="bg-cyan-50 p-6 rounded-3xl shadow-sm border border-cyan-100 flex items-center space-x-4">
                    <div class="p-4 bg-red-50 rounded-2xl text-red-600">
                        <i class="fa-solid fa-ban text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-1">Bannis</p>
                        <p class="text-2xl font-semibold text-slate-800">{{ $stats['total_banned'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Users Table -->
            <div class="bg-cyan-50 shadow-sm rounded-3xl border border-cyan-100 overflow-hidden">
                <div class="p-8 border-b border-cyan-100 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-slate-800 tracking-tight">Gestion des Utilisateurs</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-cyan-100/30 text-slate-400 text-xs font-semibold uppercase tracking-widest">
                                <th class="px-8 py-4">Utilisateur</th>
                                <th class="px-8 py-4">Rôle</th>
                                <th class="px-8 py-4">Score</th>
                                <th class="px-8 py-4">Statut</th>
                                <th class="px-8 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($users as $user)
                            <tr class="hover:bg-slate-50/30 transition-colors">
                                <td class="px-8 py-5">
                                    <div class="flex items-center space-x-3">
                                        <div class="h-10 w-10 flex-shrink-0 bg-cyan-100 rounded-xl flex items-center justify-center font-semibold text-cyan-600">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-slate-800">{{ $user->name }}</div>
                                            <div class="text-xs text-slate-400">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="px-3 py-1 text-[10px] font-semibold uppercase rounded-lg {{ $user->isAdmin() ? 'bg-cyan-50 text-cyan-600' : 'bg-slate-50 text-slate-500' }}">
                                        {{ $user->role->name ?? 'User' }}
                                    </span>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="text-sm font-medium {{ $user->reputation_score >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                        {{ $user->reputation_score ?? 0 }} pts
                                    </span>
                                </td>
                                <td class="px-8 py-5">
                                    @if($user->is_banned)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Banni</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Actif</span>
                                    @endif
                                </td>
                                <td class="px-8 py-5 text-right">
                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.toggle-ban', $user) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-sm font-semibold {{ $user->is_banned ? 'text-emerald-600 hover:text-emerald-700' : 'text-red-500 hover:text-red-700' }}">
                                                {{ $user->is_banned ? 'Débannir' : 'Bannir' }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs font-medium text-slate-300 italic">Moi</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
           
        </div>
    </div>
</x-app-layout>
