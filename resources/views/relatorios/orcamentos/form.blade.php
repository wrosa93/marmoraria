<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __("Relatório de Orçamentos") }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Formulário de Busca --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">Buscar Orçamentos</h3>
                <form action="{{ route("orcamentos.relatorio.buscar") }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        {{-- Número do Orçamento --}}
                        <div>
                            <x-input-label for="numero_orcamento" :value="__("Número do Orçamento")" />
                            <x-text-input id="numero_orcamento" class="block mt-1 w-full" type="number" name="numero_orcamento" :value="request("numero_orcamento")" />
                        </div>

                        {{-- Nome do Cliente --}}
                        <div>
                            <x-input-label for="nome_cliente" :value="__("Nome do Cliente")" />
                            <x-text-input id="nome_cliente" class="block mt-1 w-full" type="text" name="nome_cliente" :value="request("nome_cliente")" />
                        </div>

                        {{-- Data Inicial --}}
                        <div>
                            <x-input-label for="data_inicial" :value="__("Data Inicial")" />
                            <x-text-input id="data_inicial" class="block mt-1 w-full" type="date" name="data_inicial" :value="request("data_inicial")" />
                        </div>

                        {{-- Data Final --}}
                        <div>
                            <x-input-label for="data_final" :value="__("Data Final")" />
                            <x-text-input id="data_final" class="block mt-1 w-full" type="date" name="data_final" :value="request("data_final")" />
                        </div>
                    </div>
                    <div class="flex items-center justify-end">
                        <x-primary-button>
                            {{ __("Buscar") }}
                        </x-primary-button>
                    </div>
                </form>
            </div>

            {{-- Resultados da Busca --}}
            @isset($orcamentos)
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">Resultados da Busca</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cliente</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Data</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($orcamentos as $orcamento)
                                    @php
                                        $totalOrcamento = $orcamento->items->sum("subtotal");
                                    @endphp
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $orcamento->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $orcamento->cliente->nome }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $orcamento->data->format("d/m/Y") }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">R$ {{ number_format($totalOrcamento, 2, ",", ".") }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <a href="{{ route("orcamentos.show", $orcamento) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mr-3">Ver</a>
                                            <a href="{{ route("orcamentos.edit", $orcamento) }}" class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-900 dark:hover:text-yellow-300 mr-3">Editar</a>
                                            <a href="{{ route("orcamentos.pdf", $orcamento) }}" target="_blank" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">PDF</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">
                                            Nenhum orçamento encontrado com os critérios informados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- Paginação --}}
                    @if ($orcamentos->hasPages())
                        <div class="mt-4">
                            {{ $orcamentos->appends(request()->query())->links() }} {{-- Mantém os parâmetros de busca na paginação --}}
                        </div>
                    @endif
                </div>
            @endisset
        </div>
    </div>
</x-app-layout>
