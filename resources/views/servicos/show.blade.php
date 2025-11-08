<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Serviço: ') . $servico->nome }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">Detalhes do serviço</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <span class="block text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Código</span>
                            <p class="mt-1 text-base text-gray-900 dark:text-gray-100">{{ $servico->codigo }}</p>
                        </div>
                        <div>
                            <span class="block text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Nome</span>
                            <p class="mt-1 text-base text-gray-900 dark:text-gray-100">{{ $servico->nome }}</p>
                        </div>
                        <div>
                            <span class="block text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Tipo de cobrança</span>
                            <p class="mt-1 text-base text-gray-900 dark:text-gray-100">{{ ucfirst($servico->tipo_cobranca) }}</p>
                        </div>
                        <div>
                            <span class="block text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Unidade de medida</span>
                            <p class="mt-1 text-base text-gray-900 dark:text-gray-100">{{ strtoupper($servico->unidade_medida) }}</p>
                        </div>
                        <div>
                            <span class="block text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Status</span>
                            <p class="mt-1 text-base text-gray-900 dark:text-gray-100">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $servico->ativo ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-600' }}">
                                    {{ $servico->ativo ? 'Ativo' : 'Inativo' }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <span class="block text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Atualizado em</span>
                            <p class="mt-1 text-base text-gray-900 dark:text-gray-100">{{ $servico->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <span class="block text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Descrição</span>
                            <p class="mt-1 text-base text-gray-900 dark:text-gray-100">{{ $servico->descricao ?? 'Sem descrição' }}</p>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200 mb-4">Histórico de preços</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                <thead class="bg-gray-100 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Vigência</th>
                                        <th class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Preço (R$)</th>
                                        <th class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Observação</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse ($servico->prices()->orderByDesc('data_inicio')->get() as $preco)
                                        <tr>
                                            <td class="px-4 py-2">
                                                {{ \Illuminate\Support\Carbon::parse($preco->data_inicio)->format('d/m/Y') }}
                                                @if ($preco->data_fim)
                                                    <br><span class="text-xs text-gray-500 dark:text-gray-400">até {{ \Illuminate\Support\Carbon::parse($preco->data_fim)->format('d/m/Y') }}</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2">R$ {{ number_format($preco->preco, 2, ',', '.') }}</td>
                                            <td class="px-4 py-2 text-gray-500 dark:text-gray-300">{{ $preco->observacao ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-4 py-2 text-center text-gray-500 dark:text-gray-400">Sem histórico de preços.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6 space-x-4">
                        <a href="{{ route('servicos.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Voltar
                        </a>
                        <a href="{{ route('servicos.edit', $servico) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-400 focus:bg-yellow-400 active:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Editar
                        </a>
                        <form action="{{ route('servicos.destroy', $servico) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <x-danger-button type="submit" onclick="return confirm('Tem certeza que deseja remover este serviço?')">
                                Remover
                            </x-danger-button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
