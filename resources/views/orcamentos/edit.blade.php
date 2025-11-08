<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Orçamento: ') . $orcamento->numero }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Dados gerais</h3>
                        <a href="{{ route('orcamentos.pdf', $orcamento) }}" class="inline-flex items-center px-4 py-2 bg-slate-700 dark:bg-slate-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-slate-800 uppercase tracking-widest hover:bg-slate-600 dark:hover:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Gerar PDF
                        </a>
                    </div>

                    <form action="{{ route('orcamentos.update', $orcamento) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="cliente_id" :value="__('Cliente')" />
                                <select id="cliente_id" name="cliente_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                                    @foreach ($clientes as $cliente)
                                        <option value="{{ $cliente->id }}" {{ old('cliente_id', $orcamento->cliente_id) == $cliente->id ? 'selected' : '' }}>
                                            {{ $cliente->nome }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('cliente_id')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="payment_method_id" :value="__('Condição de pagamento')" />
                                <select id="payment_method_id" name="payment_method_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    <option value="">Selecionar...</option>
                                    @foreach ($paymentMethods as $method)
                                        <option value="{{ $method->id }}" {{ old('payment_method_id', $orcamento->payment_method_id) == $method->id ? 'selected' : '' }}>
                                            {{ $method->nome }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('payment_method_id')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="data" :value="__('Data do orçamento')" />
                                <x-text-input id="data" class="block mt-1 w-full" type="date" name="data" :value="old('data', $orcamento->data?->format('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('data')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="data_validade" :value="__('Validade da proposta')" />
                                <x-text-input id="data_validade" class="block mt-1 w-full" type="date" name="data_validade" :value="old('data_validade', optional($orcamento->data_validade)->format('Y-m-d'))" />
                                <x-input-error :messages="$errors->get('data_validade')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <x-input-label for="status" :value="__('Status')" />
                                <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}" {{ old('status', $orcamento->status) === $status ? 'selected' : '' }}>
                                            {{ ucfirst($status) }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="desconto_tipo" :value="__('Tipo de desconto')" />
                                <select id="desconto_tipo" name="desconto_tipo" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    @foreach (['nenhum' => 'Nenhum', 'percentual' => 'Percentual (%)', 'valor' => 'Valor (R$)'] as $valor => $label)
                                        <option value="{{ $valor }}" {{ old('desconto_tipo', $orcamento->desconto_tipo) === $valor ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('desconto_tipo')" class="mt-2" />
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="desconto_percentual" :value="__('Desconto %')" />
                                    <x-text-input id="desconto_percentual" class="block mt-1 w-full" type="number" step="0.01" min="0" max="100" name="desconto_percentual" :value="old('desconto_percentual', $orcamento->desconto_percentual)" />
                                    <x-input-error :messages="$errors->get('desconto_percentual')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="desconto_valor" :value="__('Desconto R$')" />
                                    <x-text-input id="desconto_valor" class="block mt-1 w-full" type="number" step="0.01" min="0" name="desconto_valor" :value="old('desconto_valor', $orcamento->desconto_valor)" />
                                    <x-input-error :messages="$errors->get('desconto_valor')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <x-input-label for="acrescimo_valor" :value="__('Acréscimo / encargos (R$)')" />
                                <x-text-input id="acrescimo_valor" class="block mt-1 w-full" type="number" step="0.01" min="0" name="acrescimo_valor" :value="old('acrescimo_valor', $orcamento->acrescimo_valor)" />
                                <x-input-error :messages="$errors->get('acrescimo_valor')" class="mt-2" />
                            </div>
                            <div class="md:col-span-2">
                                <x-input-label for="responsavel" :value="__('Responsável pelo orçamento')" />
                                <x-text-input id="responsavel" class="block mt-1 w-full" type="text" name="responsavel" :value="old('responsavel', $orcamento->responsavel)" />
                                <x-input-error :messages="$errors->get('responsavel')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="condicoes_pagamento" :value="__('Condições de pagamento detalhadas')" />
                            <textarea id="condicoes_pagamento" name="condicoes_pagamento" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('condicoes_pagamento', $orcamento->condicoes_pagamento) }}</textarea>
                            <x-input-error :messages="$errors->get('condicoes_pagamento')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="observacoes" :value="__('Observações')" />
                            <textarea id="observacoes" name="observacoes" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('observacoes', $orcamento->observacoes) }}</textarea>
                            <x-input-error :messages="$errors->get('observacoes')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end">
                            <x-primary-button>
                                {{ __('Salvar alterações') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Ambientes do orçamento</h3>
                        <form action="{{ route('orcamentos.locais.store', $orcamento) }}" method="POST" class="flex items-end gap-3">
                            @csrf
                            <div>
                                <x-input-label for="novo_local_nome" :value="__('Nome do ambiente')" />
                                <x-text-input id="novo_local_nome" class="block mt-1 w-full" type="text" name="nome" required />
                                <x-input-error :messages="$errors->get('nome')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="novo_local_ordem" :value="__('Ordem')" />
                                <x-text-input id="novo_local_ordem" class="block mt-1 w-full" type="number" min="1" name="ordem" />
                            </div>
                            <div>
                                <x-input-label for="novo_local_observacoes" :value="__('Observações')" />
                                <x-text-input id="novo_local_observacoes" class="block mt-1 w-full" type="text" name="observacoes" />
                            </div>
                            <x-primary-button class="mt-6">
                                {{ __('Adicionar ambiente') }}
                            </x-primary-button>
                        </form>
                    </div>

                    @forelse ($orcamento->locais as $local)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between bg-gray-50 dark:bg-gray-900 px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                <div>
                                    <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100">{{ $local->nome }}</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Total: R$ {{ number_format($local->total, 2, ',', '.') }} • Material: R$ {{ number_format($local->total_material, 2, ',', '.') }} • Serviços: R$ {{ number_format($local->total_servico, 2, ',', '.') }}
                                    </p>
                                </div>
                                <form action="{{ route('orcamentos.locais.destroy', [$orcamento, $local]) }}" method="POST" class="mt-3 md:mt-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300" onclick="return confirm('Remover ambiente {{ $local->nome }} e todas as suas peças?')">
                                        Remover ambiente
                                    </button>
                                </form>
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
                                                <th class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Área (m²)</th>
                                                <th class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Material (R$)</th>
                                                <th class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Serviços (R$)</th>
                                                <th class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Extras (R$)</th>
                                                <th class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total (R$)</th>
                                                <th class="px-4 py-2 text-center font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @forelse ($local->pecas as $peca)
                                                <tr>
                                                    <td class="px-4 py-2 font-semibold text-gray-900 dark:text-gray-100">
                                                        {{ $peca->identificador ?? 'Peça #' . $peca->id }}
                                                        @if ($peca->observacoes)
                                                            <span class="block text-xs text-gray-500 dark:text-gray-400">{{ $peca->observacoes }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-2 text-gray-500 dark:text-gray-300">
                                                        {{ $peca->material->nome }}
                                                        <span class="block text-xs text-gray-400 dark:text-gray-500">R$ {{ number_format($peca->preco_material_unitario, 2, ',', '.') }} /m²</span>
                                                    </td>
                                                    <td class="px-4 py-2 text-gray-500 dark:text-gray-300">
                                                        {{ number_format($peca->largura_mm / 10, 1, ',', '.') }} x {{ number_format($peca->comprimento_mm / 10, 1, ',', '.') }} cm
                                                        <span class="block text-xs text-gray-400 dark:text-gray-500">Esp.: {{ $peca->espessura_mm ? number_format($peca->espessura_mm, 2, ',', '.') . ' mm' : '-' }}</span>
                                                    </td>
                                                    <td class="px-4 py-2 text-gray-500 dark:text-gray-300">{{ $peca->quantidade }}</td>
                                                    <td class="px-4 py-2 text-gray-500 dark:text-gray-300">{{ number_format($peca->area_m2, 3, ',', '.') }}</td>
                                                    <td class="px-4 py-2 text-gray-500 dark:text-gray-300">R$ {{ number_format($peca->preco_material_total, 2, ',', '.') }}</td>
                                                    <td class="px-4 py-2 text-gray-500 dark:text-gray-300">R$ {{ number_format($peca->preco_servico_total, 2, ',', '.') }}</td>
                                                    <td class="px-4 py-2 text-gray-500 dark:text-gray-300">R$ {{ number_format($peca->custos_extras, 2, ',', '.') }}</td>
                                                    <td class="px-4 py-2 text-gray-900 dark:text-gray-100 font-semibold">R$ {{ number_format($peca->total, 2, ',', '.') }}</td>
                                                    <td class="px-4 py-2 text-center">
                                                        <form action="{{ route('orcamentos.pecas.destroy', [$orcamento, $peca]) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-sm text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300" onclick="return confirm('Remover peça {{ $peca->identificador ?? ('#' . $peca->id) }}?')">
                                                                Remover
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="10" class="px-4 py-4 bg-gray-50 dark:bg-gray-900">
                                                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                                            <div class="flex-1">
                                                                <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Serviços aplicados</h5>
                                                                <div class="overflow-x-auto">
                                                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-xs">
                                                                        <thead class="bg-gray-100 dark:bg-gray-800">
                                                                            <tr>
                                                                                <th class="px-3 py-2 text-left font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Serviço</th>
                                                                                <th class="px-3 py-2 text-left font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Qtd</th>
                                                                                <th class="px-3 py-2 text-left font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Valor unit.</th>
                                                                                <th class="px-3 py-2 text-left font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                                                                                <th class="px-3 py-2 text-center font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ações</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
                                                                            @forelse ($peca->servicos as $itemServico)
                                                                                <tr>
                                                                                    <td class="px-3 py-2 text-gray-500 dark:text-gray-300">{{ $itemServico->descricao }}</td>
                                                                                    <td class="px-3 py-2 text-gray-500 dark:text-gray-300">{{ number_format($itemServico->quantidade, 3, ',', '.') }} {{ $itemServico->tipo_cobranca }}</td>
                                                                                    <td class="px-3 py-2 text-gray-500 dark:text-gray-300">R$ {{ number_format($itemServico->preco_unitario, 2, ',', '.') }}</td>
                                                                                    <td class="px-3 py-2 text-gray-900 dark:text-gray-100 font-semibold">R$ {{ number_format($itemServico->total, 2, ',', '.') }}</td>
                                                                                    <td class="px-3 py-2 text-center">
                                                                                        <form action="{{ route('orcamentos.pecas.servicos.destroy', [$orcamento, $itemServico]) }}" method="POST">
                                                                                            @csrf
                                                                                            @method('DELETE')
                                                                                            <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300" onclick="return confirm('Remover serviço {{ $itemServico->descricao }} da peça?')">
                                                                                                Remover
                                                                                            </button>
                                                                                        </form>
                                                                                    </td>
                                                                                </tr>
                                                                            @empty
                                                                                <tr>
                                                                                    <td colspan="5" class="px-3 py-2 text-center text-gray-500 dark:text-gray-400">Nenhum serviço adicionado.</td>
                                                                                </tr>
                                                                            @endforelse
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div class="w-full md:w-80">
                                                                <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Adicionar serviço</h5>
                                                                <form action="{{ route('orcamentos.pecas.servicos.store', [$orcamento, $peca]) }}" method="POST" class="space-y-3">
                                                                    @csrf
                                                                    <div>
                                                                        <x-input-label for="servico_id_{{ $peca->id }}" :value="__('Serviço')" />
                                                                        <select id="servico_id_{{ $peca->id }}" name="servico_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                                                                            <option value="">Selecionar...</option>
                                                                            @foreach ($servicos as $servico)
                                                                                <option value="{{ $servico->id }}">{{ $servico->nome }} ({{ strtoupper($servico->unidade_medida) }})</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="grid grid-cols-2 gap-3">
                                                                        <div>
                                                                            <x-input-label :for="'quantidade_' . $peca->id" :value="__('Quantidade')" />
                                                                            <x-text-input id="{{ 'quantidade_' . $peca->id }}" class="block mt-1 w-full" type="number" step="0.001" min="0.001" name="quantidade" required />
                                                                        </div>
                                                                        <div>
                                                                            <x-input-label :for="'preco_unitario_' . $peca->id" :value="__('Valor unit. (opcional)')" />
                                                                            <x-text-input id="{{ 'preco_unitario_' . $peca->id }}" class="block mt-1 w-full" type="number" step="0.01" min="0" name="preco_unitario" />
                                                                        </div>
                                                                    </div>
                                                                    <div>
                                                                        <x-input-label :for="'descricao_servico_' . $peca->id" :value="__('Descrição personalizada')" />
                                                                        <x-text-input id="{{ 'descricao_servico_' . $peca->id }}" class="block mt-1 w-full" type="text" name="descricao" />
                                                                    </div>
                                                                    <div>
                                                                        <x-input-label :for="'tipo_cobranca_' . $peca->id" :value="__('Tipo de cobrança customizada')" />
                                                                        <x-text-input id="{{ 'tipo_cobranca_' . $peca->id }}" class="block mt-1 w-full uppercase" type="text" name="tipo_cobranca" placeholder="(opcional)"/>
                                                                    </div>
                                                                    <x-primary-button>
                                                                        {{ __('Adicionar serviço') }}
                                                                    </x-primary-button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="10" class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">Nenhuma peça neste ambiente.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <div>
                                    <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Adicionar peça ao ambiente</h5>
                                    <form action="{{ route('orcamentos.locais.pecas.store', [$orcamento, $local]) }}" method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                                        @csrf
                                        <div class="md:col-span-2">
                                            <x-input-label :for="'material_id_' . $local->id" :value="__('Material')" />
                                            <select id="{{ 'material_id_' . $local->id }}" name="material_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                                                <option value="">Selecionar...</option>
                                                @foreach ($materiais as $material)
                                                    <option value="{{ $material->id }}">{{ $material->nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <x-input-label :for="'identificador_' . $local->id" :value="__('Identificação')" />
                                            <x-text-input id="{{ 'identificador_' . $local->id }}" class="block mt-1 w-full" type="text" name="identificador" />
                                        </div>
                                        <div>
                                            <x-input-label :for="'largura_mm_' . $local->id" :value="__('Largura (mm)')" />
                                            <x-text-input id="{{ 'largura_mm_' . $local->id }}" class="block mt-1 w-full" type="number" step="1" min="1" name="largura_mm" required />
                                        </div>
                                        <div>
                                            <x-input-label :for="'comprimento_mm_' . $local->id" :value="__('Comprimento (mm)')" />
                                            <x-text-input id="{{ 'comprimento_mm_' . $local->id }}" class="block mt-1 w-full" type="number" step="1" min="1" name="comprimento_mm" required />
                                        </div>
                                        <div>
                                            <x-input-label :for="'espessura_mm_' . $local->id" :value="__('Espessura (mm)')" />
                                            <x-text-input id="{{ 'espessura_mm_' . $local->id }}" class="block mt-1 w-full" type="number" step="0.1" min="0" name="espessura_mm" />
                                        </div>
                                        <div>
                                            <x-input-label :for="'quantidade_' . $local->id" :value="__('Quantidade')" />
                                            <x-text-input id="{{ 'quantidade_' . $local->id }}" class="block mt-1 w-full" type="number" min="1" step="1" name="quantidade" value="1" required />
                                        </div>
                                        <div>
                                            <x-input-label :for="'custos_extras_' . $local->id" :value="__('Custos extras (R$)')" />
                                            <x-text-input id="{{ 'custos_extras_' . $local->id }}" class="block mt-1 w-full" type="number" step="0.01" min="0" name="custos_extras" />
                                        </div>
                                        <div class="md:col-span-2">
                                            <x-input-label :for="'observacoes_peca_' . $local->id" :value="__('Observações da peça')" />
                                            <x-text-input id="{{ 'observacoes_peca_' . $local->id }}" class="block mt-1 w-full" type="text" name="observacoes" />
                                        </div>
                                        <div class="md:col-span-1 flex items-end">
                                            <x-primary-button>
                                                {{ __('Adicionar peça') }}
                                            </x-primary-button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">Nenhum ambiente cadastrado. Utilize o formulário acima para adicionar o primeiro ambiente.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Resumo financeiro</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 text-sm">
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
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
