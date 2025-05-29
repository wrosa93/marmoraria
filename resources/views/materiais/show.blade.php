<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __("Detalhes do Material: ") . $material->nome }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">Detalhes do Material: {{ $material->nome }}</h3>

                    <div class="space-y-4">
                        <div>
                            <strong class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome:</strong>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $material->nome }}</p>
                        </div>
                        <div>
                            <strong class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo:</strong>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $material->tipo }}</p>
                        </div>
                        <div>
                            <strong class="block text-sm font-medium text-gray-700 dark:text-gray-300">Preço/m²:</strong>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">R$ {{ number_format($material->preco_m2, 2, ",", ".") }}</p>
                        </div>
                        <div>
                            <strong class="block text-sm font-medium text-gray-700 dark:text-gray-300">Espessura (mm):</strong>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $material->espessura_mm }}</p>
                        </div>
                         <div>
                            <strong class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data de Cadastro:</strong>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $material->created_at->format("d/m/Y H:i") }}</p>
                        </div>
                    </div>

                    {{-- Botões --}}
                    <div class="flex items-center justify-end mt-6 space-x-4">
                        <a href="{{ route("materiais.index") }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                            Voltar para Lista
                        </a>
                        <a href="{{ route("materiais.edit", $material) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-400 focus:bg-yellow-400 active:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Editar
                        </a>
                         <form action="{{ route("materiais.destroy", $material) }}" method="POST" class="inline-block">
                            @csrf
                            @method("DELETE")
                            <x-danger-button type="submit" onclick="return confirm("Tem certeza que deseja remover este material?")">
                                Remover
                            </x-danger-button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
