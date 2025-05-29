<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __("Editar Material: ") . $material->nome }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">Editar Material: {{ $material->nome }}</h3>

                    {{-- Formulário --}}
                    <form action="{{ route("materiais.update", $material) }}" method="POST">
                        @csrf
                        @method("PUT")

                        {{-- Nome --}}
                        <div class="mb-4">
                            <x-input-label for="nome" :value="__("Nome do Material")" />
                            <x-text-input id="nome" class="block mt-1 w-full" type="text" name="nome" :value="old("nome", $material->nome)" required autofocus />
                            <x-input-error :messages="$errors->get("nome")" class="mt-2" />
                        </div>

                        {{-- Tipo --}}
                        <div class="mb-4">
                            <x-input-label for="tipo" :value="__("Tipo (ex: Granito, Mármore, Quartzo)")" />
                            <x-text-input id="tipo" class="block mt-1 w-full" type="text" name="tipo" :value="old("tipo", $material->tipo)" required />
                            <x-input-error :messages="$errors->get("tipo")" class="mt-2" />
                        </div>

                        {{-- Preço por m² --}}
                        <div class="mb-4">
                            <x-input-label for="preco_m2" :value="__("Preço por m² (R$)")" />
                            <x-text-input id="preco_m2" class="block mt-1 w-full" type="number" step="0.01" name="preco_m2" :value="old("preco_m2", $material->preco_m2)" required />
                            <x-input-error :messages="$errors->get("preco_m2")" class="mt-2" />
                        </div>

                        {{-- Espessura --}}
                        <div class="mb-4">
                            <x-input-label for="espessura_mm" :value="__("Espessura (mm)")" />
                            <x-text-input id="espessura_mm" class="block mt-1 w-full" type="number" step="1" name="espessura_mm" :value="old("espessura_mm", $material->espessura_mm)" required />
                            <x-input-error :messages="$errors->get("espessura_mm")" class="mt-2" />
                        </div>

                        {{-- Botões --}}
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route("materiais.index") }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150 mr-4">
                                Cancelar
                            </a>
                            <x-primary-button>
                                {{ __("Salvar Alterações") }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
