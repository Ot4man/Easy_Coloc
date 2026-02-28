<x-app-layout>
    <x-slot name="header">
        Tableau de Bord
    </x-slot>

    <div class="space-y-10">
        <!-- Header / Stats Banner -->
        <div class="bg-gradient-to-r from-cyan-500 to-cyan-400 rounded-3xl p-8 text-white shadow-sm flex flex-col md:flex-row justify-between items-center space-y-6 md:space-y-0 relative overflow-hidden">
            <!-- Decorative circle -->
            <div class="absolute -right-10 -top-10 w-48 h-48 bg-white/20 rounded-full blur-2xl"></div>

            <div class="z-10 w-full md:w-auto text-center md:text-left">
                <h3 class="text-sm font-medium text-cyan-100 mb-1">Score Réputation</h3>
                <div class="text-4xl md:text-5xl font-semibold">{{ $reputationScore }}</div>
            </div>

            <div class="hidden md:block w-px h-16 bg-cyan-400/50 z-10"></div>

            <div class="z-10 w-full md:w-auto text-center md:text-left">
                <h3 class="text-sm font-medium text-cyan-100 mb-1">Dépenses Globales ({{ now()->format('M') }})</h3>
                <div class="text-4xl md:text-5xl font-semibold">{{ number_format($currentMonthExpensesSum, 0, ',', ' ') }} €</div>
            </div>
        </div>

        @if($pendingInvitations->count() > 0)
            <div class="space-y-4">
                <h3 class="text-lg font-bold text-slate-800 flex items-center">
                    <span class="flex h-2 w-2 rounded-full bg-cyan-500 mr-2"></span>
                    Invitations en attente
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($pendingInvitations as $invitation)
                        <div class="bg-white rounded-3xl shadow-sm border-2 border-cyan-100 p-6 flex justify-between items-center transition-all hover:border-cyan-200">
                            <div class="flex items-center space-x-4">
                                <div class="h-12 w-12 bg-cyan-50 rounded-2xl flex items-center justify-center text-cyan-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-800">{{ $invitation->colocation->name }}</h4>
                                    <p class="text-xs text-slate-500">Vous avez été invité à rejoindre cette colocation.</p>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <form method="POST" action="{{ route('invitations.refuse', $invitation->token) }}">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 text-sm font-bold text-slate-500 hover:text-red-600 transition-colors">Refuser</button>
                                </form>
                                <a href="{{ route('invitations.accept', $invitation->token) }}" class="px-6 py-2 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition-all">Accepter</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Recent Expenses (Left Large) -->
            <div class="lg:col-span-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-slate-800">Dépenses récentes</h3>
                    <a href="#" class="text-xs font-semibold text-cyan-600 hover:text-cyan-800 flex items-center">
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>

                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-white border-b border-slate-100">
                                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-400 w-1/3">Titre / Catégorie</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-400">Montant</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-400">Coloc</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($recentExpenses as $expense)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-slate-800">{{ $expense->title }}</div>
                                            <div class="text-xs text-slate-400 mt-1">{{ $expense->category->name ?? 'Sans catégorie' }}</div>
                                        </td>

                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-slate-900">{{ number_format($expense->amount, 0, ',', ' ') }} €</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-xs text-slate-500">{{ $expense->colocation->name }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-slate-400 text-sm italic">
                                            Aucune dépense récente.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Partners Sidebar (Right Small) -->
            <div class="lg:col-span-1">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Membres de la coloc</h3>

                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <span class="text-xs font-medium text-slate-500">Membres actifs</span>
                        <span class="px-2 py-1 bg-slate-50 text-slate-500 rounded text-[10px] font-bold uppercase">
                            {{ $activeColocation ? 'ACTIF' : 'VIDE' }}
                        </span>
                    </div>

                    @if($activeColocation)
                        <div class="space-y-4">
                            @foreach($activeColocation->users as $member)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="h-10 w-10 bg-cyan-100 rounded-xl flex items-center justify-center text-sm font-semibold text-cyan-600">
                                            {{ substr($member->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-slate-800 leading-none">{{ $member->name }}</div>
                                            <div class="text-[10px] font-medium text-slate-400 uppercase mt-1">{{ $member->pivot->internal_role }}</div>
                                        </div>
                                    </div>
                                    <div class="h-2 w-2 rounded-full {{ $member->pivot->internal_role === 'owner' ? 'bg-amber-400' : 'bg-slate-200' }}"></div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-8 text-center">
                            <svg class="w-10 h-10 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <p class="text-slate-400 text-xs">Aucune colocation active.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
