<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8">
                <h2 class="font-semibold text-2xl text-slate-800 tracking-tight">
                    {{ __('Dépenses : ') }} {{ $activeColocation->name }}
                </h2>
                <div class="flex space-x-3">
                    <a href="{{ route('expenses.create') }}" class="inline-flex items-center px-4 py-2.5 bg-cyan-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest shadow-md hover:bg-cyan-700 focus:bg-cyan-700 active:bg-cyan-900 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <i class="fa-solid fa-plus w-4 h-4 mr-2"></i>
                        {{ __('Nouvelle dépense') }}
                    </a>
                    <a href="{{ route('expenses.settlement') }}" class="inline-flex items-center px-4 py-2.5 bg-emerald-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest shadow-md hover:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <i class="fa-solid fa-calculator w-4 h-4 mr-2"></i>
                        {{ __('Settlement') }}
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-lg shadow-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-cyan-50 shadow-sm rounded-3xl border border-cyan-100 overflow-hidden">
                <div class="p-6 sm:p-8">
                    @if($expenses->isEmpty())
                        <div class="flex flex-col items-center justify-center py-12 px-4 rounded-2xl bg-white border-2 border-dashed border-cyan-200">
                            <div class="w-16 h-16 bg-cyan-100 rounded-2xl shadow-sm flex items-center justify-center mb-4">
                                <i class="fa-solid fa-coins text-2xl text-cyan-400"></i>
                            </div>
                            <p class="text-slate-500 font-medium mb-6 text-center">Aucune dépense enregistrée pour le moment.</p>
                            <a href="{{ route('expenses.create') }}" class="px-6 py-2.5 bg-cyan-600 text-white rounded-xl text-sm font-medium shadow-md hover:bg-cyan-700 transition-all">
                                Ajouter votre première dépense
                            </a>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200 dark:divide-gray-700">
                                <thead class="bg-cyan-100/50">
                                    <tr>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Titre</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Montant</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Catégorie</th>

                                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-cyan-50">
                                    @foreach($expenses as $expense)
                                        <tr class="hover:bg-slate-50 dark:hover:bg-gray-700/50 transition-colors group">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-500 dark:text-gray-400">
                                                {{ \Carbon\Carbon::parse($expense->date)->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-slate-800">{{ $expense->title }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-semibold text-emerald-600">{{ number_format($expense->amount, 2, ',', ' ') }} DH</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-cyan-50 text-cyan-700">
                                                    {{ $expense->category->name ?? 'N/A' }}
                                                </span>
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex items-center justify-end space-x-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <!-- On modère l'édition pour ceux qui ont payé la dépense, ou selon votre config, tout membre actif -->
                                                    <a href="{{ route('expenses.edit', $expense) }}" class="p-2 text-slate-400 hover:text-cyan-600 bg-white hover:bg-cyan-50 rounded-lg transition-colors border border-transparent hover:border-cyan-100 shadow-sm" title="Modifier">
                                                        <i class="fa-solid fa-pen-to-square w-5 h-5"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('expenses.destroy', $expense) }}" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette dépense ?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 bg-white hover:bg-rose-50 border border-transparent hover:border-rose-100 rounded-lg transition-colors shadow-sm" title="Supprimer">
                                                            <i class="fa-solid fa-trash w-5 h-5"></i>
                                                        </button>
                                                    </form>
                                                </div>
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
