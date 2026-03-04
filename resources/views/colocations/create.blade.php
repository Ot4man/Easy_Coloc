<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Créer une nouvelle colocation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-cyan-50 overflow-hidden shadow-sm rounded-3xl border border-cyan-100">
                <div class="p-10">
                    <div class="flex items-center space-x-4 mb-10">
                       
                        <div>
                            <h3 class="text-2xl font-semibold text-slate-800">New Colocation</h3>
                            <p class="text-slate-500">Fill in the details</p>
                        </div>
                    </div>

                    @if(session('error'))
                        <div class="mb-8 p-4 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 text-red-700 dark:text-red-300 rounded-r-xl">
                            <div class="flex">
                                <i class="fa-solid fa-circle-exclamation h-5 w-5 text-red-500 mr-2"></i>
                                {{ session('error') }}
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('colocations.store') }}" class="space-y-8">
                        @csrf

                        <div class="space-y-2">
                            <label for="name" class="block text-sm font-semibold text-slate-700 uppercase tracking-widest">Name of colocation</label>
                            <input type="text" name="name" id="name" required
                                class="mt-1 block w-full rounded-2xl border-cyan-200 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 px-5 py-4 transition-all duration-200"
                                placeholder="Ex: House"
                                value="{{ old('name') }}">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="description" class="block text-sm font-semibold text-slate-700 uppercase tracking-widest">Description</label>
                            <textarea name="description" id="description" rows="5" required
                                class="mt-1 block w-full rounded-2xl border-cyan-200 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 px-5 py-4 transition-all duration-200"
                                placeholder="Briefly describe your shared apartment, its atmosphere, its rules, etc."></textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end pt-6 space-x-6 border-t border-cyan-100">
                            <a href="{{ route('colocations.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-800 ">
                                Cancle
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-8 py-4 bg-cyan-600 border border-transparent rounded-2xl font-semibold text-sm text-white uppercase tracking-widest hover:bg-cyan-700   ">
                                Create Now
                                <i class="fa-solid fa-arrow-right ml-2 w-5 h-5"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
