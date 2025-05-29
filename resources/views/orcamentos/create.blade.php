<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __("Novo Orçamento") }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">Criar Novo Orçamento</h3>

                    {{-- Formulário --}}
                    <form action="{{ route("orcamentos.store") }}" method="POST">
                        @csrf

                        {{-- Cliente --}}
                        <div class="mb-4">
                            <x-input-label for="cliente_id" :value="__("Cliente")" />
                            <select id="cliente_id" name="cliente_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="" disabled {{ old("cliente_id") ? "" : "selected" }}>Selecione um cliente</option>
                                @foreach ($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" {{ old("cliente_id") == $cliente->id ? "selected" : "" }}>
                                        {{ $cliente->nome }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get("cliente_id")" class="mt-2" />
                        </div>

                        {{-- Data --}}
                        <div class="mb-4">
                            <x-input-label for="data" :value="__("Data")" />
                            <x-text-input id="data" class="block mt-1 w-full" type="date" name="data" :value="old("data", date("Y-m-d"))" required />
                            <x-input-error :messages="$errors->get("data")" class="mt-2" />
                        </div>

                        {{-- Botões --}}
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route("orcamentos.index") }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150 mr-4">
                                Cancelar
                            </a>
                            <x-primary-button>
                                {{ __("Criar Orçamento e Adicionar Itens") }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
