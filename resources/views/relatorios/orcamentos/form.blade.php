<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Relatório de Orçamentos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="GET" action="{{ route('orcamentos.relatorio.buscar') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <x-input-label for="numero" :value="__('Número')" />
                            <x-text-input id="numero" name="numero" type="text" class="mt-1 block w-full" :value="$input['numero'] ?? ''" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="nome_cliente" :value="__('Nome do cliente')" />
                            <x-text-input id="nome_cliente" name="nome_cliente" type="text" class="mt-1 block w-full" :value="$input['nome_cliente'] ?? ''" />
                        </div>
                        <div>
                            <x-input-label for="status" :value="__('Status')" />
                            <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">Todos</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}" {{ ($input['status'] ?? '') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="data_inicio" :value="__('Data inicial')" />
                            <x-text-input id="data_inicio" name="data_inicio" type="date" class="mt-1 block w-full" :value="$input['data_inicio'] ?? ''" />
                        </div>
                        <div>
                            <x-input-label for="data_fim" :value="__('Data final')" />
                            <x-text-input id="data_fim" name="data_fim" type="date" class="mt-1 block w-full" :value="$input['data_fim'] ?? ''" />
                        </div>
                        <div class="md:col-span-2 flex items-end">
                            <x-primary-button>
                                {{ __('Filtrar') }}
                            </x-primary-button>
                            <a href="{{ route('orcamentos.relatorio.form') }}" class="ml-3 inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Limpar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            @isset($orcamentos)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Número</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cliente</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Data</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total líquido</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse ($orcamentos as $orcamento)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $orcamento->numero }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $orcamento->cliente->nome }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $orcamento->data->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">R$ {{ number_format($orcamento->total_liquido, 2, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                    @class([
                                                        'bg-gray-200 text-gray-700' => $orcamento->status === 'rascunho',
                                                        'bg-blue-100 text-blue-800' => $orcamento->status === 'enviado',
                                                        'bg-green-100 text-green-800' => $orcamento->status === 'aprovado',
                                                        'bg-red-100 text-red-800' => $orcamento->status === 'reprovado',
                                                        'bg-zinc-200 text-zinc-700' => $orcamento->status === 'cancelado',
                                                    ])
                                                ">
                                                    {{ ucfirst($orcamento->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-3">
                                                <a href="{{ route('orcamentos.show', $orcamento) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">Ver</a>
                                                <a href="{{ route('orcamentos.pdf', $orcamento) }}" class="text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-slate-100">PDF</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">
                                                Nenhum orçamento encontrado com os filtros informados.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $orcamentos->links() }}
                        </div>
                    </div>
                </div>
            @endisset
        </div>
    </div>
</x-app-layout>
