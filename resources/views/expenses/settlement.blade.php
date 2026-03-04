<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8">
                <h2 class="font-semibold text-2xl text-slate-800 tracking-tight">
                    {{ __('Règlement des dettes') }} - {{ $activeColocation->name }}
                </h2>
                <a href="{{ route('expenses.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest shadow-md hover:bg-slate-700 active:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="fa-solid fa-arrow-left w-4 h-4 mr-2"></i>
                    {{ __('Retour aux dépenses') }}
                </a>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-lg shadow-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-cyan-50 shadow-sm rounded-3xl border border-cyan-100 overflow-hidden">
                <div class="p-6 sm:p-8">
                    @if(empty($settlements))
                        <div class="flex flex-col items-center justify-center py-12 px-4 rounded-2xl bg-white border-2 border-dashed border-cyan-200">
                            <div class="w-16 h-16 bg-cyan-100 rounded-2xl shadow-sm flex items-center justify-center mb-4">
                                <i class="fa-solid fa-check-double text-2xl text-cyan-400"></i>
                            </div>
                            <p class="text-slate-500 font-medium text-center">Toutes les dettes sont réglées !</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead class="bg-cyan-100/50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Qui doit</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">À qui</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Combien</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Détail (Dépense)</th>
                                        <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-cyan-50">
                                    @foreach($settlements as $settlement)
                                        <tr class="hover:bg-slate-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="text-sm font-medium text-slate-800">{{ $settlement->from }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="text-sm font-medium text-slate-800">{{ $settlement->to }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="text-sm font-semibold text-rose-600">{{ number_format($settlement->amount, 2, ',', ' ') }} DH</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="text-sm text-slate-500 italic">{{ $settlement->expense->title }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                @if($settlement->can_mark_paid)
                                                    <form method="POST" action="{{ route('expenses.mark-as-paid', $settlement->expense) }}" class="inline">
                                                        @csrf
                                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-emerald-600 text-white rounded-lg text-xs font-medium hover:bg-emerald-700 transition-colors shadow-sm">
                                                            <i class="fa-solid fa-check w-3 h-3 mr-1.5"></i>
                                                            Marquer comme payé
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-xs text-slate-400">Attente du créateur</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
