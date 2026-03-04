<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-slate-800 dark:text-gray-200 leading-tight">
                {{ __('Ajouter une dépense') }}
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



                    <form method="POST" action="{{ route('expenses.store') }}" class="space-y-6">
                        @csrf

                        <!-- Titre -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-slate-700 mb-2">Title of Expense</label>
                            <input type="text" name="title" id="title" class="w-full rounded-xl border-cyan-200 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 font-medium" value="{{ old('title') }}" required placeholder="Ex: Groceries,bills....">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Montant -->
                            <div>
                                <label for="amount" class="block text-sm font-medium text-slate-700 mb-2">Amount (DH)</label>
                                <div class="relative">

                                    <input type="number" step="0.01" min="0.01" name="amount" id="amount" class="w-full pl-10 rounded-xl border-cyan-200 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 font-semibold text-slate-800" value="{{ old('amount') }}" required placeholder="0.00">
                                </div>
                            </div>

                            <!-- Date -->
                            <div>
                                <label for="date" class="block text-sm font-medium text-slate-700 mb-2">Purchase date</label>
                                <input type="date" name="date" id="date" class="w-full rounded-xl border-cyan-200 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 font-medium" value="{{ old('date', date('Y-m-d')) }}" required>
                            </div>
                        </div>

                        <!-- Catégorie -->
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-slate-700 mb-2">Categoriy</label>
                            @if($categories->isEmpty())
                                <div class="p-4 bg-orange-50 border border-orange-200 rounded-xl text-sm text-orange-800 mb-2">
                                Attention : There is no category yet,create one.   
                                </div>
                            @else
                                <select name="category_id" id="category_id" class="w-full rounded-xl border-cyan-200 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 font-medium" required>
                                    <option value="" disabled selected>Select a category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        </div>

                        <div class="pt-6 flex items-center justify-end space-x-3 border-t border-cyan-100 mt-8">
                            <a href="{{ route('expenses.index') }}" class="px-6 py-3 bg-white hover:bg-slate-50 text-slate-700 font-medium rounded-xl transition-colors border border-cyan-100">
                                Cancel
                            </a>
                            <button type="submit" class="px-6 py-3 bg-cyan-600 hover:bg-cyan-700 text-white font-medium rounded-xl shadow-md shadow-cyan-500/30 transition-all transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed" @if($categories->isEmpty()) disabled @endif>
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
