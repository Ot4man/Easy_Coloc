<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Mes Colocations') }}
            </h2>
            <a href="{{ route('colocations.create') }}" class="inline-flex items-center px-4 py-2 bg-cyan-600 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-cyan-700 focus:bg-cyan-700 active:bg-cyan-900 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Créer une colocation') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('status'))
                <div class="mb-4 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($colocations as $colocation)
                    <div class="group bg-cyan-50 overflow-hidden shadow-sm hover:shadow-lg rounded-3xl border border-cyan-100 transition-all duration-300">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-6">
                                <div class="bg-cyan-50 p-3 rounded-2xl">
                                    <i class="fa-solid fa-house text-xl text-cyan-500"></i>
                                </div>
                                <span class="px-3 py-1 text-[10px] font-semibold tracking-wider uppercase rounded-md {{ $colocation->status === 'active' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                    {{ $colocation->status }}
                                </span>
                            </div>

                            <h3 class="text-lg font-medium text-slate-800 mb-2 group-hover:text-cyan-600 transition-colors">
                                {{ $colocation->name }}
                            </h3>

                            <p class="text-slate-500 text-sm mb-6 line-clamp-2 leading-relaxed">
                                {{ $colocation->description ?? 'Aucune description disponible pour cette colocation.' }}
                            </p>

                            <div class="pt-6 border-t border-slate-50 flex items-center justify-between">
                                <div class="flex -space-x-3 overflow-hidden">
                                    @foreach($colocation->users->take(4) as $user)
                                        <div class=" h-8 w-8 rounded-full ring-2 ring-white bg-cyan-100 flex items-center justify-center text-cyan-600 text-xs font-semibold" title="{{ $user->name }}">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                    @endforeach
                                    @if($colocation->users->count() > 4)
                                        <div class=" h-8 w-8 rounded-full ring-2 ring-white bg-slate-100 flex items-center justify-center text-slate-500 text-xs font-medium">
                                            +{{ $colocation->users->count() - 4 }}
                                        </div>
                                    @endif
                                </div>
                                @if ($colocation->status == 'active')
                                <a href="{{ route('colocations.show', $colocation) }}" class="inline-flex items-center text-sm font-medium text-cyan-600 hover:text-cyan-800 transition-colors">
                                        Details
                                        <i class="fa-solid fa-arrow-right w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform"></i>
                                    </a>
                                @endif
                                <div class="flex items-center space-x-3">
                                

                                    @php
                                        $pivot = auth()->user()->colocations()->where('colocations.id', $colocation->id)->first()->pivot;
                                        $isOwner = $pivot->internal_role === 'owner';
                                    @endphp

                                    <div x-data="{ openEdit: false }" class="flex items-center space-x-2">
                                        @if($isOwner)
                                            @if($colocation->status === 'active')
                                                <!-- Edit button - Only if active -->
                                                <button @click="openEdit = true" class="p-2 text-slate-400 hover:text-cyan-600 bg-white hover:bg-cyan-50 rounded-lg transition-colors" title="Modifier">
                                                    <i class="fa-solid fa-pen-to-square w-5 h-5"></i>
                                                </button>

                                                <!-- Cancel Button - Only if active -->
                                                <form method="POST" action="{{ route('colocations.cancel', $colocation) }}" class="inline" onsubmit="return confirm('Are you sure , you want to cancel?');">
                                                    @csrf
                                                    <button type="submit" class="p-2 text-slate-400 hover:text-amber-600 bg-white hover:bg-amber-50 rounded-lg transition-colors" title="Cancel">
                                                        <i class="fa-solid fa-ban w-5 h-5"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            <!-- Delete Button -->
                                            <form method="POST" action="{{ route('colocations.destroy', $colocation) }}" class="inline" onsubmit="return confirm('Are you sure , you want to delete');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-slate-400 hover:text-red-600 bg-white hover:bg-red-50 rounded-lg transition-colors" title="Supprimer">
                                                    <i class="fa-solid fa-trash w-5 h-5"></i>
                                                </button>
                                            </form>
                                        @else
                                            <!-- Leave Button for Members -->
                                            <form method="POST" action="{{ route('colocations.leave', $colocation) }}" class="inline" onsubmit="return confirm('Are you sure , you want to leave ?');">
                                                @csrf
                                                <button type="submit" class="p-2 text-slate-400 hover:text-orange-600 bg-white hover:bg-orange-50 rounded-lg transition-colors" title="Quitter">
                                                    <i class="fa-solid fa-right-from-bracket w-5 h-5"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if($isOwner && $colocation->status === 'active')
                                            <!-- Edit Modal -->
                                            <div x-show="openEdit" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50" style="display: none;" x-transition>
                                                <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg p-8 mx-4" @click.away="openEdit = false">
                                                    <div class="flex items-center justify-between mb-6">
                                                        <h3 class="text-xl font-semibold text-slate-800">Update colocation</h3>
                                                        <button @click="openEdit = false" class="text-slate-400 hover:text-slate-600">
                                                            <i class="fa-solid fa-xmark w-6 h-6"></i>
                                                        </button>
                                                    </div>
                                                    <form method="POST" action="{{ route('colocations.update', $colocation) }}" class="space-y-4">
                                                        @csrf
                                                        @method('PUT')
                                                        <div>
                                                            <label class="block text-sm font-medium text-slate-700 mb-2">Name of colocation</label>
                                                            <input type="text" name="name" value="{{ $colocation->name }}" class="w-full rounded-xl border-cyan-200 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 font-medium">
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                                                            <textarea name="description" rows="3" class="w-full rounded-xl border-cyan-200 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 font-medium">{{ $colocation->description }}</textarea>
                                                        </div>
                                                        <div class="flex justify-end space-x-3 pt-4">
                                                            <button type="button" @click="openEdit = false" class="px-6 py-2.5 bg-slate-100 text-slate-600 rounded-xl text-sm font-medium hover:bg-slate-200 transition-colors">Annuler</button>
                                                            <button type="submit" class="px-6 py-2.5 bg-cyan-600 text-white rounded-xl text-sm font-medium hover:bg-cyan-700 shadow-lg shadow-cyan-200 transition-all">Update</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 bg-cyan-50 rounded-3xl border border-dashed border-cyan-200 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-cyan-50 rounded-2xl mb-6">
                            <p></p>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-800 mb-2">Join Colocation?</h3>
                        <p class="text-slate-500 text-sm mb-8 max-w-sm mx-auto">Create your first colocation , manage your expense and your membres</p>
                        <a href="{{ route('colocations.create') }}" class="inline-flex items-center px-6 py-3 bg-cyan-500 hover:bg-cyan-600 text-white font-medium rounded-xl transition-all shadow-sm">
                            Start
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
