<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Novo Orçamento') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">Dados iniciais do orçamento</h3>

                    <form action="{{ route('orcamentos.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="cliente_id" :value="__('Cliente')" />
                                <select id="cliente_id" name="cliente_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                                    <option value="">Selecione um cliente...</option>
                                    @foreach ($clientes as $cliente)
                                        <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>{{ $cliente->nome }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('cliente_id')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="data" :value="__('Data do orçamento')" />
                                <x-text-input id="data" class="block mt-1 w-full" type="date" name="data" :value="old('data', now()->format('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('data')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="data_validade" :value="__('Validade da proposta')" />
                                <x-text-input id="data_validade" class="block mt-1 w-full" type="date" name="data_validade" :value="old('data_validade')" />
                                <x-input-error :messages="$errors->get('data_validade')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="payment_method_id" :value="__('Condição de pagamento')" />
                                <select id="payment_method_id" name="payment_method_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    <option value="">Selecionar...</option>
                                    @foreach ($paymentMethods as $method)
                                        <option value="{{ $method->id }}" {{ old('payment_method_id') == $method->id ? 'selected' : '' }}>
                                            {{ $method->nome }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('payment_method_id')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <x-input-label for="status" :value="__('Status inicial')" />
                                <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}" {{ old('status', 'rascunho') === $status ? 'selected' : '' }}>
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
                                        <option value="{{ $valor }}" {{ old('desconto_tipo', 'nenhum') === $valor ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('desconto_tipo')" class="mt-2" />
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="desconto_percentual" :value="__('Desconto %')" />
                                    <x-text-input id="desconto_percentual" class="block mt-1 w-full" type="number" step="0.01" min="0" max="100" name="desconto_percentual" :value="old('desconto_percentual')" />
                                    <x-input-error :messages="$errors->get('desconto_percentual')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="desconto_valor" :value="__('Desconto R$')" />
                                    <x-text-input id="desconto_valor" class="block mt-1 w-full" type="number" step="0.01" min="0" name="desconto_valor" :value="old('desconto_valor')" />
                                    <x-input-error :messages="$errors->get('desconto_valor')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <x-input-label for="acrescimo_valor" :value="__('Acréscimo / encargos (R$)')" />
                                <x-text-input id="acrescimo_valor" class="block mt-1 w-full" type="number" step="0.01" min="0" name="acrescimo_valor" :value="old('acrescimo_valor')" />
                                <x-input-error :messages="$errors->get('acrescimo_valor')" class="mt-2" />
                            </div>
                            <div class="md:col-span-2">
                                <x-input-label for="responsavel" :value="__('Responsável pelo orçamento')" />
                                <x-text-input id="responsavel" class="block mt-1 w-full" type="text" name="responsavel" :value="old('responsavel')" />
                                <x-input-error :messages="$errors->get('responsavel')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="condicoes_pagamento" :value="__('Condições de pagamento detalhadas')" />
                            <textarea id="condicoes_pagamento" name="condicoes_pagamento" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('condicoes_pagamento') }}</textarea>
                            <x-input-error :messages="$errors->get('condicoes_pagamento')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="observacoes" :value="__('Observações')" />
                            <textarea id="observacoes" name="observacoes" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('observacoes') }}</textarea>
                            <x-input-error :messages="$errors->get('observacoes')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('orcamentos.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 mr-4">
                                Cancelar
                            </a>
                            <x-primary-button>
                                {{ __('Criar orçamento') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
