<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __("Novo Serviço") }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">Criar Novo Serviço</h3>

                    {{-- Formulário --}}
                    <form action="{{ route("servicos.store") }}" method="POST">
                        @csrf

                        {{-- Descrição --}}
                        <div class="mb-4">
                            <x-input-label for="descricao" :value="__("Descrição do Serviço")" />
                            <x-text-input id="descricao" class="block mt-1 w-full" type="text" name="descricao" :value="old("descricao")" required autofocus />
                            <x-input-error :messages="$errors->get("descricao")" class="mt-2" />
                        </div>

                        {{-- Preço Unitário --}}
                        <div class="mb-4">
                            <x-input-label for="preco_unitario" :value="__("Preço Unitário (R$)")" />
                            <x-text-input id="preco_unitario" class="block mt-1 w-full" type="number" step="0.01" name="preco_unitario" :value="old("preco_unitario")" required />
                            <x-input-error :messages="$errors->get("preco_unitario")" class="mt-2" />
                        </div>

                        {{-- Botões --}}
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route("servicos.index") }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150 mr-4">
                                Cancelar
                            </a>
                            <x-primary-button>
                                {{ __("Salvar Serviço") }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
