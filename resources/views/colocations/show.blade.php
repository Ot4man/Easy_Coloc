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
                                    <i class="fa-solid fa-circle-xmark w-4 h-4 mr-2"></i>
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
                                        <button type="button" @click="openEdit = false" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md text-sm font-bold">Cancel</button>
                                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-bold">Enregistrer</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
                <a href="{{ route('colocations.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="fa-solid fa-arrow-left w-4 h-4 mr-2"></i>
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
                    <div class="bg-cyan-50 shadow-sm rounded-3xl overflow-hidden border border-cyan-100">
                        <div class="p-8">
                            <div class="flex items-center space-x-3 mb-6">

                                <h3 class="text-xl font-semibold text-slate-800">About</h3>
                            </div>
                            <p class="text-slate-600 leading-relaxed text-lg italic">
                                "{{ $colocation->description ?? 'Aucune description disponible.' }}"
                            </p>
                        </div>
                    </div>

                    <!-- Expenses Section Placeholder with better design -->
                    <div class="bg-cyan-50 shadow-sm rounded-3xl border border-cyan-100 overflow-hidden">
                        <div class="p-8">
                            <div class="flex items-center justify-between mb-8">
                                <div class="flex items-center space-x-3">

                                    <h3 class="text-xl font-semibold text-slate-800">Recent Expenses</h3>
                                </div>
                                <a href="{{ route('expenses.index') }}" class="text-sm font-medium text-cyan-600 hover:text-cyan-800 transition-colors">See all</a>
                            </div>

                            <div class="space-y-4">
                                @forelse($colocation->expenses->sortByDesc('date')->take(5) as $expense)
                                    <div class="flex items-center justify-between p-4 rounded-2xl bg-white border border-cyan-100 hover:shadow-md transition-all">
                                        <div class="flex items-center space-x-4">
                                            <div class="p-3 bg-cyan-100 rounded-xl shadow-sm">
                                                <i class="fa-solid fa-coins w-5 h-5 text-cyan-600"></i>
                                            </div>
                                            <div>
                                                <h4 class="text-sm font-medium text-slate-800">{{ $expense->title }}</h4>
                                                <div class="flex items-center space-x-2 mt-1">
                                                    <span class="text-[10px] font-semibold uppercase px-2 py-0.5 rounded-md bg-cyan-100 text-cyan-700">
                                                        {{ $expense->category->name ?? 'N/A' }}
                                                    </span>
                                                    <span class="text-xs text-gray-400 font-medium">{{ $expense->date->format('d/m/Y') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-sm font-semibold text-slate-800">{{ number_format($expense->amount, 2, ',', ' ') }} DH</div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="flex flex-col items-center justify-center py-12 px-4 rounded-2xl bg-white border-2 border-dashed border-cyan-200">
                                        <div class="w-16 h-16 bg-cyan-100 rounded-2xl shadow-sm flex items-center justify-center mb-4">
                                            <i class="fa-solid fa-clipboard text-2xl text-cyan-400"></i>
                                        </div>
                                        <p class="text-gray-500 font-medium">No expenses recorded</p>
                                        <a href="{{ route('expenses.create') }}" class="mt-6 px-4 py-2 bg-cyan-600 text-white rounded-lg text-sm font-medium  hover:bg-cyan-700  text-center">Add Expense</a>
                                    </div>
                                @endforelse

                                @if($colocation->expenses->count() > 0)
                                    <div class="mt-6 text-center">
                                        <a href="{{ route('expenses.create') }}" class="inline-flex items-center px-6 py-3 bg-cyan-600 hover:bg-cyan-700 text-white font-medium rounded-xl shadow-lg ">
                                            <i class="fa-solid fa-plus w-5 h-5 mr-2"></i>
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
                    <div class="bg-cyan-50 shadow-sm rounded-3xl overflow-hidden border border-cyan-100">
                        <div class="p-8">
                            <div class="flex items-center justify-between mb-8" x-data="{ openInvite: false }">
                                <h3 class="text-xl font-semibold text-slate-800">Membres ({{ $colocation->users->count() }})</h3>
                                @if($isOwner ?? false)
                                    <button @click="openInvite = true" class="p-2  hover:bg-cyan-200 transition-colors group" title="Invite membre">
                                        <i class="fa-solid fa-plus w-5 h-5 text-cyan-600 group-hover:scale-110 transition-transform"></i>
                                    </button>

                                    <!-- Invitation Modal -->
                                    <div x-show="openInvite" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50" style="display: none;" x-transition>
                                        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg p-8 mx-4" @click.away="openInvite = false">
                                            <div class="flex items-center justify-between mb-6">
                                                <h3 class="text-xl font-semibold text-slate-800">Inviter un nouveau membre</h3>
                                                <button @click="openInvite = false" class="text-slate-400 hover:text-slate-600">
                                                    <i class="fa-solid fa-xmark w-6 h-6"></i>
                                                </button>
                                            </div>
                                            <form method="POST" action="{{ route('invitations.send') }}" class="space-y-4">
                                                @csrf
                                                <div>
                                                    <label class="block text-sm font-medium text-slate-700 mb-2">Adresse Email</label>
                                                    <input type="email" name="email" required placeholder="nom@exemple.com" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 font-medium">
                                                    <p class="mt-2 text-xs text-slate-500 italic">L'utilisateur recevra une notification sur son tableau de bord.</p>
                                                </div>
                                                <div class="flex justify-end space-x-3 pt-4">
                                                    <button type="button" @click="openInvite = false" class="px-6 py-2.5 bg-slate-100 text-slate-600 rounded-xl text-sm font-medium hover:bg-slate-200 transition-colors">Annuler</button>
                                                    <button type="submit" class="px-6 py-2.5 bg-cyan-600 text-white rounded-xl text-sm font-medium hover:bg-cyan-700 shadow-lg shadow-cyan-200 transition-all">Envoyer l'invitation</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="space-y-4">
                                @foreach($colocation->users as $user)
                                    <div class="relative group p-4 rounded-2xl border border-cyan-100  transition-all duration-300">
                                        <div class="flex items-center space-x-4">
                                            <div class="relative flex-shrink-0">
                                                <div class="w-12 h-12 rounded-2xl bg-cyan-200 flex items-center justify-center text-cyan-700 font-semibold text-lg shadow-sm">
                                                    {{ substr($user->name, 0, 1) }}
                                                </div>
                                                @if($user->pivot->internal_role === 'owner')
                                                    <div class="absolute -top-2 -right-2 bg-amber-400 text-white p-1 rounded-lg border-2 border-white dark:border-gray-800 shadow-sm" title="Propriétaire">
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-md font-medium text-slate-800 truncate">
                                                    {{ $user->name }}
                                                </p>
                                                <div class="flex items-center space-x-2 mt-1">
                                                    <span class="text-[10px] font-semibold uppercase tracking-wider px-2 py-0.5 rounded-md {{ $user->pivot->internal_role === 'owner' ? 'bg-amber-100 text-amber-700' : 'bg-cyan-100 text-cyan-700' }}">
                                                        {{ $user->pivot->internal_role === 'owner' ? 'owner' : 'membre' }}
                                                    </span>
                                                    <span class="text-xs text-gray-400 ">Rep: {{ $user->reputation_score }}</span>
                                                </div>
                                            </div>
                                            @if($user->id === auth()->id())
                                                <span class="text-[10px] font-medium text-slate-400 uppercase tracking-tighter">Me</span>
                                            @endif

                                            @if(($isOwner ?? false) && $user->pivot->internal_role !== 'owner')
                                                <form method="POST" action="{{ route('colocations.removeMember', [$colocation, $user]) }}" class="opacity-0 group-hover:opacity-100 transition-opacity" onsubmit="return confirm('Retirer ce membre ? Son solde sera calculé pour mettre à jour sa réputation.');">
                                                    @csrf
                                                    <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 transition-colors" title="Retirer de la colocation">
                                                        <i class="fa-solid fa-user-minus"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar: Categories Management -->
                    <div class="bg-cyan-50 shadow-sm rounded-3xl border border-cyan-100 overflow-hidden" x-data="{ showForm: false }">
                        <div class="p-8">
                            @if(session('error'))
                                <p class="text-[10px] text-red-500 font-medium mb-2">{{ session('error') }}</p>
                                @endif
                            <div class="flex items-center justify-between mb-8">
                                <div class="flex items-center space-x-3">
                                    <div class="p-2 bg-cyan-100 rounded-lg">
                                        <i class="fa-solid fa-th-large w-5 h-5 text-cyan-600"></i>
                                    </div>
                                    <h3 class="text-xl font-semibold text-slate-800">Categries</h3>

                                </div>
                                @if($isOwner ?? false)
                                    <button @click="showForm = !showForm" class="p-2 bg-cyan-100 rounded-xl hover:bg-cyan-200 transition-colors group" title="Add category  ">
                                        <i class="fa-solid fa-plus w-5 h-5 text-cyan-600 group-hover:scale-110 transition-transform"></i>
                                    </button>
                                @endif
                            </div>

                            <!-- Add Category Form (Hidden by default) -->
                            <div x-show="showForm" x-transition class="mb-6 p-4 bg-white rounded-2xl border border-dashed border-cyan-200">

                                <form method="POST" action="{{ route('categories.store', $colocation) }}" class="flex items-center space-x-2">
                                    @csrf
                                    <input type="text" name="name" required maxlength="30" placeholder="New" class="w-full text-xs rounded-xl border-cyan-200 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
                                    <button type="submit" class="p-2 bg-cyan-600 text-white rounded-xl hover:bg-cyan-700 shadow-md transition-all">
                                        <i class="fa-solid fa-check w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>

                            <!-- Categories Pills -->
                            <div class="flex flex-wrap gap-2">
                                @forelse($colocation->categories as $category)
                                    <div class="group flex items-center bg-white border border-cyan-100 text-slate-700 rounded-full {{ ($isOwner ?? false) ? 'pl-4 pr-1' : 'px-4' }} py-1.5 transition-all hover:border-cyan-300">
                                        <span class="text-[10px] font-semibold uppercase tracking-wider">{{ $category->name }}</span>
                                        @if($isOwner ?? false)
                                            <form method="POST" action="{{ route('categories.destroy', $category) }}" class="inline ml-1" onsubmit="return confirm('Supprimer la catégorie « {{ $category->name }} » ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="flex items-center justify-center w-6 h-6 rounded-full text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 transition-all">
                                                    <i class="fa-solid fa-xmark w-3.5 h-3.5"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500 italic">No category</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Meta data -->
                    <div class="bg-cyan-600 rounded-3xl shadow-lg p-6 text-white">
                        <h4 class="text-sm font-medium uppercase tracking-wider mb-2 opacity-80">Colocation Status</h4>
                        <div class="text-2xl font-semibold mb-4">{{ ucfirst($colocation->status) }}</div>

                        <div class="space-y-3 pt-4 border-t border-white/20">
                            @if($isOwner && $colocation->status === 'active')
                                <form method="POST" action="{{ route('colocations.cancel', $colocation) }}" onsubmit="return confirm('Are you suree , cancel colocation?');">
                                    @csrf
                                    <button type="submit" class="w-full py-2 bg-white/20 hover:bg-white/30 rounded-lg text-sm font-medium transition-all flex items-center justify-center">
                                        <i class="fa-solid fa-ban w-4 h-4 mr-2"></i>
                                        Cancel Colocation
                                    </button>
                                </form>
                            @elseif(!$isOwner)
                                <form method="POST" action="{{ route('colocations.leave', $colocation) }}" onsubmit="return confirm('Are u sure leave colocation ?');">
                                    @csrf
                                    <button type="submit" class="w-full py-2 bg-white/20 hover:bg-white/30 rounded-lg text-sm font-medium transition-all flex items-center justify-center">
                                        <i class="fa-solid fa-right-from-bracket w-4 h-4 mr-2"></i>
                                        Leave colocation
                                    </button>
                                </form>
                            @endif
                            <div class="text-xs opacity-80 pt-2">
                                Created at {{ $colocation->created_at->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
