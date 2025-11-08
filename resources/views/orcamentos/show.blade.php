<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Orçamento ') . $orcamento->numero }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Informações do orçamento</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Criado em {{ $orcamento->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('orcamentos.pdf', $orcamento) }}" class="inline-flex items-center px-4 py-2 bg-slate-700 dark:bg-slate-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-slate-800 uppercase tracking-widest hover:bg-slate-600 dark:hover:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Baixar PDF
                            </a>
                            <a href="{{ route('orcamentos.edit', $orcamento) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-400 focus:bg-yellow-400 active:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Editar
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase">Cliente</h4>
                            <p class="text-base text-gray-900 dark:text-gray-100">{{ $orcamento->cliente->nome }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $orcamento->cliente->telefone ?? 'Sem telefone' }} • {{ $orcamento->cliente->email ?? 'Sem e-mail' }}
                            </p>
                        </div>
                        <div class="space-y-2">
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase">Status e vigência</h4>
                            <p class="text-base text-gray-900 dark:text-gray-100">
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
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Emitido em {{ $orcamento->data->format('d/m/Y') }}
                                @if ($orcamento->data_validade)
                                    • Válido até {{ $orcamento->data_validade->format('d/m/Y') }}
                                @endif
                            </p>
                        </div>
                        <div class="space-y-2">
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase">Pagamento</h4>
                            <p class="text-base text-gray-900 dark:text-gray-100">
                                {{ $orcamento->paymentMethod?->nome ?? 'Não informado' }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $orcamento->condicoes_pagamento ?? 'Sem descrição adicional.' }}</p>
                        </div>
                        <div class="space-y-2">
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase">Responsável</h4>
                            <p class="text-base text-gray-900 dark:text-gray-100">{{ $orcamento->responsavel ?? 'Não informado' }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Número interno: {{ $orcamento->numero }}</p>
                        </div>
                    </div>

                    @if ($orcamento->observacoes)
                        <div class="mt-4">
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase">Observações</h4>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $orcamento->observacoes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                <div class="p-4 bg-gray-50 dark:bg-gray-900 rounded-md border border-gray-200 dark:border-gray-700">
                    <span class="text-xs uppercase text-gray-500 dark:text-gray-400">Material</span>
                    <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">R$ {{ number_format($orcamento->total_material, 2, ',', '.') }}</p>
                </div>
                <div class="p-4 bg-gray-50 dark:bg-gray-900 rounded-md border border-gray-200 dark:border-gray-700">
                    <span class="text-xs uppercase text-gray-500 dark:text-gray-400">Serviços</span>
                    <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">R$ {{ number_format($orcamento->total_servico, 2, ',', '.') }}</p>
                </div>
                <div class="p-4 bg-gray-50 dark:bg-gray-900 rounded-md border border-gray-200 dark:border-gray-700">
                    <span class="text-xs uppercase text-gray-500 dark:text-gray-400">Custos extras</span>
                    <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">R$ {{ number_format($orcamento->total_custos_extras, 2, ',', '.') }}</p>
                </div>
                <div class="p-4 bg-gray-50 dark:bg-gray-900 rounded-md border border-gray-200 dark:border-gray-700">
                    <span class="text-xs uppercase text-gray-500 dark:text-gray-400">Descontos / Acréscimos</span>
                    <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        - R$ {{ number_format($orcamento->total_desconto, 2, ',', '.') }} / + R$ {{ number_format($orcamento->total_acrescimo, 2, ',', '.') }}
                    </p>
                </div>
                <div class="p-4 bg-indigo-50 dark:bg-indigo-900 rounded-md border border-indigo-200 dark:border-indigo-700">
                    <span class="text-xs uppercase text-indigo-700 dark:text-indigo-200">Total líquido</span>
                    <p class="text-xl font-bold text-indigo-700 dark:text-indigo-200">R$ {{ number_format($orcamento->total_liquido, 2, ',', '.') }}</p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Ambientes e peças</h3>
                    @forelse ($orcamento->locais as $local)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg">
                            <div class="bg-gray-50 dark:bg-gray-900 px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex flex-col md:flex-row md:items-center md:justify-between">
                                <div>
                                    <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100">{{ $local->nome }}</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Total: R$ {{ number_format($local->total, 2, ',', '.') }} • Material: R$ {{ number_format($local->total_material, 2, ',', '.') }} • Serviços: R$ {{ number_format($local->total_servico, 2, ',', '.') }}
                                    </p>
                                </div>
                                @if ($local->observacoes)
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 md:mt-0">{{ $local->observacoes }}</p>
                                @endif
                            </div>
                            <div class="p-4 space-y-4">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                        <thead class="bg-gray-100 dark:bg-gray-900">
                                            <tr>
                                                <th class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Peça</th>
                                                <th class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Material</th>
                                                <th class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Dimensões</th>
                                                <th class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Qtd</th>
                                                <th class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Área</th>
                                                <th class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @forelse ($local->pecas as $peca)
                                                <tr>
                                                    <td class="px-4 py-2 text-gray-900 dark:text-gray-100">
                                                        {{ $peca->identificador ?? 'Peça #' . $peca->id }}
                                                        @if ($peca->observacoes)
                                                            <span class="block text-xs text-gray-500 dark:text-gray-400">{{ $peca->observacoes }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-2 text-gray-500 dark:text-gray-300">{{ $peca->material->nome }}</td>
                                                    <td class="px-4 py-2 text-gray-500 dark:text-gray-300">
                                                        {{ number_format($peca->largura_mm / 10, 1, ',', '.') }} x {{ number_format($peca->comprimento_mm / 10, 1, ',', '.') }} cm
                                                        <span class="block text-xs text-gray-400 dark:text-gray-500">Esp.: {{ $peca->espessura_mm ? number_format($peca->espessura_mm, 2, ',', '.') . ' mm' : '-' }}</span>
                                                    </td>
                                                    <td class="px-4 py-2 text-gray-500 dark:text-gray-300">{{ $peca->quantidade }}</td>
                                                    <td class="px-4 py-2 text-gray-500 dark:text-gray-300">{{ number_format($peca->area_m2, 3, ',', '.') }} m²</td>
                                                    <td class="px-4 py-2 text-gray-900 dark:text-gray-100 font-semibold">R$ {{ number_format($peca->total, 2, ',', '.') }}</td>
                                                </tr>
                                                @if ($peca->servicos->isNotEmpty())
                                                    <tr>
                                                        <td colspan="6" class="px-4 py-2 bg-gray-50 dark:bg-gray-900">
                                                            <p class="text-xs uppercase text-gray-500 dark:text-gray-400 mb-2">Serviços</p>
                                                            <ul class="space-y-1 text-sm text-gray-600 dark:text-gray-300">
                                                                @foreach ($peca->servicos as $itemServico)
                                                                    <li class="flex justify-between">
                                                                        <span>{{ $itemServico->descricao }} ({{ number_format($itemServico->quantidade, 3, ',', '.') }} {{ $itemServico->tipo_cobranca }})</span>
                                                                        <span>R$ {{ number_format($itemServico->total, 2, ',', '.') }}</span>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="px-4 py-2 text-center text-gray-500 dark:text-gray-400">Nenhuma peça cadastrada.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">Nenhum ambiente cadastrado.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
