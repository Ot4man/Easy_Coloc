<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-slate-800 dark:text-gray-200 leading-tight">
                {{ __('Modifier la dépense') }}
            </h2>
            <a href="{{ route('expenses.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-700 transition-colors">
                &larr; Retour aux dépenses
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-cyan-50 shadow-sm rounded-3xl border border-cyan-100 overflow-hidden">
                <div class="p-8">
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-lg shadow-sm">
                            <ul class="list-disc list-inside text-sm font-medium">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('expenses.update', $expense) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Titre -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-slate-700 mb-2">Title</label>
                            <input type="text" name="title" id="title" class="w-full rounded-xl border-cyan-200 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 font-medium" value="{{ old('title', $expense->title) }}" required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Montant -->
                            <div>
                                <label for="amount" class="block text-sm font-medium text-slate-700 mb-2">Amount (Dh)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-slate-400 sm:text-lg font-semibold">€</span>
                                    </div>
                                    <input type="number" step="0.01" min="0.01" name="amount" id="amount" class="w-full pl-10 rounded-xl border-cyan-200 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 font-semibold text-slate-800" value="{{ old('amount', $expense->amount) }}" required>
                                </div>
                            </div>

                            <!-- Date -->
                            <div>
                                <label for="date" class="block text-sm font-medium text-slate-700 mb-2">Purshase date</label>
                                <input type="date" name="date" id="date" class="w-full rounded-xl border-cyan-200 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 font-medium" value="{{ old('date', $expense->date) }}" required>
                            </div>
                        </div>

                        <!-- Catégorie -->
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-slate-700 mb-2">Catégorie associée</label>
                            <select name="category_id" id="category_id" class="w-full rounded-xl border-cyan-200 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 font-medium" required>
                                <option value="" disabled>Sélectionner une catégorie</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $expense->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="pt-6 flex items-center justify-end space-x-3 border-t border-cyan-100 mt-8">
                            <a href="{{ route('expenses.index') }}" class="px-6 py-3 bg-white hover:bg-slate-50 text-slate-700 font-medium rounded-xl transition-colors border border-cyan-100">
                                Annuler
                            </a>
                            <button type="submit" class="px-6 py-3 bg-cyan-600 hover:bg-cyan-700 text-white font-medium rounded-xl shadow-md shadow-cyan-500/30 transition-all transform hover:scale-105">
                                Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
