<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $colocation->name }}
            </h2>
            <div class="flex space-x-2">
                @if(($isOwner ?? false) && $colocation->status === 'active')
                    <div x-data="{ openEdit: false }" class="inline-flex space-x-2">
                        <button @click="openEdit = true" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 transform hover:scale-105">
                            Modifier
                        </button>
                        <form method="POST" action="{{ route('colocations.cancel', $colocation) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette colocation ?');">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 transform hover:scale-105">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Annuler
                            </button>
                        </form>

                        <!-- Edit Modal -->
                        <div x-show="openEdit" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50" style="display: none;">
                            <div class="bg-white rounded-lg shadow-xl w-full max-w-lg p-6" @click.away="openEdit = false">
                                <h3 class="text-lg font-bold mb-4">Modifier la colocation</h3>
                                <form method="POST" action="{{ route('colocations.update', $colocation) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Nom</label>
                                        <input type="text" name="name" value="{{ $colocation->name }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Description</label>
                                        <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ $colocation->description }}</textarea>
                                    </div>
                                    <div class="flex justify-end space-x-2">
                                        <button type="button" @click="openEdit = false" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md text-sm font-bold">Annuler</button>
                                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-bold">Enregistrer</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
                <a href="{{ route('colocations.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('status'))
                <div class="p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg shadow-sm">
                    {{ session('status') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Colocation Overview -->
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-3xl overflow-hidden border border-gray-100 dark:border-gray-700">
                        <div class="p-8">
                            <div class="flex items-center space-x-3 mb-6">
                                <div class="p-2 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg">
                                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-black text-gray-900 dark:text-white">À propos</h3>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400 leading-relaxed text-lg italic">
                                "{{ $colocation->description ?? 'Aucune description disponible.' }}"
                            </p>
                        </div>
                    </div>

                    <!-- Expenses Section Placeholder with better design -->
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-3xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="p-8">
                            <div class="flex items-center justify-between mb-8">
                                <div class="flex items-center space-x-3">
                                    <div class="p-2 bg-emerald-50 dark:bg-emerald-900/30 rounded-lg">
                                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zM12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-black text-gray-900 dark:text-white">Dépenses Récentes</h3>
                                </div>
                                <a href="{{ route('expenses.index') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 transition-colors">Tout voir</a>
                            </div>

                            <div class="space-y-4">
                                @forelse($colocation->expenses->sortByDesc('date')->take(5) as $expense)
                                    <div class="flex items-center justify-between p-4 rounded-2xl bg-gray-50 dark:bg-gray-900/40 border border-gray-100 dark:border-gray-700 hover:shadow-md transition-all">
                                        <div class="flex items-center space-x-4">
                                            <div class="p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm">
                                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zM12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 class="text-sm font-bold text-gray-900 dark:text-white">{{ $expense->title }}</h4>
                                                <div class="flex items-center space-x-2 mt-1">
                                                    <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-600 dark:bg-indigo-900/40 dark:text-indigo-400">
                                                        {{ $expense->category->name ?? 'N/A' }}
                                                    </span>
                                                    <span class="text-xs text-gray-400 font-medium">{{ $expense->date->format('d/m/Y') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-sm font-black text-gray-900 dark:text-white">{{ number_format($expense->amount, 2, ',', ' ') }} €</div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="flex flex-col items-center justify-center py-12 px-4 rounded-2xl bg-gray-50 dark:bg-gray-900/40 border-2 border-dashed border-gray-200 dark:border-gray-700">
                                        <div class="w-16 h-16 bg-white dark:bg-gray-800 rounded-2xl shadow-sm flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                            </svg>
                                        </div>
                                        <p class="text-gray-500 font-medium">Aucune dépense enregistrée pour le moment.</p>
                                        <a href="{{ route('expenses.create') }}" class="mt-6 px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-bold shadow-md hover:bg-indigo-700 transition-all text-center">Ajouter une dépense</a>
                                    </div>
                                @endforelse

                                @if($colocation->expenses->count() > 0)
                                    <div class="mt-6 text-center">
                                        <a href="{{ route('expenses.create') }}" class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-200 transition-all transform hover:scale-105">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                            Nouvelle dépense
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar: Members -->
                <div class="space-y-8">
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-3xl overflow-hidden border border-gray-100 dark:border-gray-700">
                        <div class="p-8">
                            <div class="flex items-center justify-between mb-8" x-data="{ openInvite: false }">
                                <h3 class="text-xl font-black text-gray-900 dark:text-white">Membres ({{ $colocation->users->count() }})</h3>
                                @if($isOwner ?? false)
                                    <button @click="openInvite = true" class="p-2 bg-indigo-50 dark:bg-indigo-900/30 rounded-xl hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition-colors group" title="Inviter un membre">
                                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </button>

                                    <!-- Invitation Modal -->
                                    <div x-show="openInvite" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50" style="display: none;" x-transition>
                                        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg p-8 mx-4" @click.away="openInvite = false">
                                            <div class="flex items-center justify-between mb-6">
                                                <h3 class="text-xl font-black text-slate-800">Inviter un nouveau membre</h3>
                                                <button @click="openInvite = false" class="text-slate-400 hover:text-slate-600">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                </button>
                                            </div>
                                            <form method="POST" action="{{ route('invitations.send') }}" class="space-y-4">
                                                @csrf
                                                <div>
                                                    <label class="block text-sm font-bold text-slate-700 mb-2">Adresse Email</label>
                                                    <input type="email" name="email" required placeholder="nom@exemple.com" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 font-medium">
                                                    <p class="mt-2 text-xs text-slate-500 italic">L'utilisateur recevra une notification sur son tableau de bord.</p>
                                                </div>
                                                <div class="flex justify-end space-x-3 pt-4">
                                                    <button type="button" @click="openInvite = false" class="px-6 py-2.5 bg-slate-100 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-200 transition-colors">Annuler</button>
                                                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all">Envoyer l'invitation</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="space-y-4">
                                @foreach($colocation->users as $user)
                                    <div class="relative group p-4 rounded-2xl border border-gray-50 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-900/40 transition-all duration-300">
                                        <div class="flex items-center space-x-4">
                                            <div class="relative flex-shrink-0">
                                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center text-white font-black text-lg shadow-lg shadow-indigo-500/20">
                                                    {{ substr($user->name, 0, 1) }}
                                                </div>
                                                @if($user->pivot->internal_role === 'owner')
                                                    <div class="absolute -top-2 -right-2 bg-amber-400 text-white p-1 rounded-lg border-2 border-white dark:border-gray-800 shadow-sm" title="Propriétaire">
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-md font-bold text-gray-900 dark:text-white truncate">
                                                    {{ $user->name }}
                                                </p>
                                                <div class="flex items-center space-x-2 mt-1">
                                                    <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-md {{ $user->pivot->internal_role === 'owner' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300' }}">
                                                        {{ $user->pivot->internal_role === 'owner' ? 'Propriétaire' : 'Membre' }}
                                                    </span>
                                                    <span class="text-xs text-gray-400 font-medium">Rep: {{ $user->reputation_score }}</span>
                                                </div>
                                            </div>
                                            @if($user->id === auth()->id())
                                                <span class="text-[10px] font-bold text-gray-300 dark:text-gray-600 uppercase tracking-tighter">Moi</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar: Categories Management -->
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-3xl border border-gray-100 dark:border-gray-700 overflow-hidden" x-data="{ showForm: false }">
                        <div class="p-8">
                            <div class="flex items-center justify-between mb-8">
                                <div class="flex items-center space-x-3">
                                    <div class="p-2 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg">
                                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 11h.01M7 15h.01M11 7h.01M11 11h.01M11 15h.01M15 7h.01M15 11h.01M15 15h.01M19 7h.01M19 11h.01M19 15h.01M4 3h16a1 1 0 011 1v16a1 1 0 01-1 1H4a1 1 0 01-1-1V4a1 1 0 011-1z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-black text-gray-900 dark:text-white">Catégories</h3>
                                </div>
                                @if($isOwner ?? false)
                                    <button @click="showForm = !showForm" class="p-2 bg-emerald-50 dark:bg-emerald-900/30 rounded-xl hover:bg-emerald-100 dark:hover:bg-emerald-900/50 transition-colors group" title="Ajouter une catégorie">
                                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </button>
                                @endif
                            </div>

                            <!-- Add Category Form (Hidden by default) -->
                            <div x-show="showForm" x-transition class="mb-6 p-4 bg-gray-50 dark:bg-gray-900/40 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700">
                                @if(session('error'))
                                    <p class="text-[10px] text-red-500 font-bold mb-2">{{ session('error') }}</p>
                                @endif
                                <form method="POST" action="{{ route('categories.store', $colocation) }}" class="flex items-center space-x-2">
                                    @csrf
                                    <input type="text" name="name" required maxlength="30" placeholder="Nouveau..." class="w-full text-xs rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:text-gray-300">
                                    <button type="submit" class="p-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 shadow-md transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </button>
                                </form>
                            </div>

                            <!-- Categories Pills -->
                            <div class="flex flex-wrap gap-2">
                                @forelse($colocation->categories as $category)
                                    <div class="group flex items-center bg-gray-50 dark:bg-gray-900/40 border border-gray-100 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-full {{ ($isOwner ?? false) ? 'pl-4 pr-1' : 'px-4' }} py-1.5 transition-all hover:border-gray-300 dark:hover:border-gray-600">
                                        <span class="text-[10px] font-black uppercase tracking-wider">{{ $category->name }}</span>
                                        @if($isOwner ?? false)
                                            <form method="POST" action="{{ route('categories.destroy', $category) }}" class="inline ml-1" onsubmit="return confirm('Supprimer la catégorie « {{ $category->name }} » ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="flex items-center justify-center w-6 h-6 rounded-full text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 transition-all">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500 italic">Aucune catégorie.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Meta data -->
                    <div class="bg-indigo-600 rounded-lg shadow-lg p-6 text-white">
                        <h4 class="text-sm font-bold uppercase tracking-wider mb-2 opacity-80">Statut de la colocation</h4>
                        <div class="text-2xl font-bold mb-4">{{ ucfirst($colocation->status) }}</div>

                        <div class="space-y-3 pt-4 border-t border-white/20">
                            @if($isOwner && $colocation->status === 'active')
                                <form method="POST" action="{{ route('colocations.cancel', $colocation) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette colocation ?');">
                                    @csrf
                                    <button type="submit" class="w-full py-2 bg-white/20 hover:bg-white/30 rounded-lg text-sm font-bold transition-all flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                        Annuler la colocation
                                    </button>
                                </form>
                            @elseif(!$isOwner)
                                <form method="POST" action="{{ route('colocations.leave', $colocation) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir quitter cette colocation ?');">
                                    @csrf
                                    <button type="submit" class="w-full py-2 bg-white/20 hover:bg-white/30 rounded-lg text-sm font-bold transition-all flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                        Quitter la colocation
                                    </button>
                                </form>
                            @endif
                            <div class="text-xs opacity-80 pt-2">
                                Créée le {{ $colocation->created_at->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
