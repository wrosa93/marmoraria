<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Material: ') . $material->nome }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">Editar material</h3>

                    <form action="{{ route('materiais.update', $material) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="codigo" :value="__('Código interno')" />
                                <x-text-input id="codigo" class="block mt-1 w-full uppercase" type="text" name="codigo" :value="old('codigo', $material->codigo)" required />
                                <x-input-error :messages="$errors->get('codigo')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="nome" :value="__('Nome do material')" />
                                <x-text-input id="nome" class="block mt-1 w-full" type="text" name="nome" :value="old('nome', $material->nome)" required />
                                <x-input-error :messages="$errors->get('nome')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="tipo" :value="__('Tipo (Mármore, Granito, Quartzo...)')" />
                                <x-text-input id="tipo" class="block mt-1 w-full" type="text" name="tipo" :value="old('tipo', $material->tipo)" />
                                <x-input-error :messages="$errors->get('tipo')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="acabamento" :value="__('Acabamento padrão')" />
                                <x-text-input id="acabamento" class="block mt-1 w-full" type="text" name="acabamento" :value="old('acabamento', $material->acabamento)" />
                                <x-input-error :messages="$errors->get('acabamento')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="cor" :value="__('Cor predominante')" />
                                <x-text-input id="cor" class="block mt-1 w-full" type="text" name="cor" :value="old('cor', $material->cor)" />
                                <x-input-error :messages="$errors->get('cor')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="espessura_padrao_mm" :value="__('Espessura padrão (mm)')" />
                                <x-text-input id="espessura_padrao_mm" class="block mt-1 w-full" type="number" step="0.1" name="espessura_padrao_mm" :value="old('espessura_padrao_mm', $material->espessura_padrao_mm)" />
                                <x-input-error :messages="$errors->get('espessura_padrao_mm')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="descricao" :value="__('Descrição e observações')" />
                            <textarea id="descricao" name="descricao" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('descricao', $material->descricao) }}</textarea>
                            <x-input-error :messages="$errors->get('descricao')" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-2">
                            <input id="ativo" name="ativo" type="checkbox" value="1" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" {{ old('ativo', $material->ativo) ? 'checked' : '' }}>
                            <label for="ativo" class="text-sm text-gray-700 dark:text-gray-300">Material ativo para seleção em orçamentos</label>
                        </div>

                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6 space-y-4">
                            <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200">Adicionar nova tabela de preço</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Informe estes campos caso deseje iniciar uma nova vigência de preço. Caso contrário, mantenha-os em branco.</p>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <x-input-label for="preco_m2" :value="__('Preço por m² (R$)')" />
                                    <x-text-input id="preco_m2" class="block mt-1 w-full" type="number" step="0.01" min="0" name="preco_m2" :value="old('preco_m2')" />
                                    <x-input-error :messages="$errors->get('preco_m2')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="data_inicio_preco" :value="__('Início de vigência')" />
                                    <x-text-input id="data_inicio_preco" class="block mt-1 w-full" type="date" name="data_inicio_preco" :value="old('data_inicio_preco')" />
                                    <x-input-error :messages="$errors->get('data_inicio_preco')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="observacao_preco" :value="__('Observação do preço')" />
                                    <x-text-input id="observacao_preco" class="block mt-1 w-full" type="text" name="observacao_preco" :value="old('observacao_preco')" />
                                    <x-input-error :messages="$errors->get('observacao_preco')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200 mb-2">Histórico de preços</h4>
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
                                        @forelse ($material->prices()->orderByDesc('data_inicio')->get() as $preco)
                                            <tr>
                                                <td class="px-4 py-2">
                                                    {{ \Illuminate\Support\Carbon::parse($preco->data_inicio)->format('d/m/Y') }}
                                                    @if ($preco->data_fim)
                                                        <br><span class="text-xs text-gray-500 dark:text-gray-400">até {{ \Illuminate\Support\Carbon::parse($preco->data_fim)->format('d/m/Y') }}</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-2">R$ {{ number_format($preco->preco_m2, 2, ',', '.') }}</td>
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

                    <div class="flex items-center justify-end">
                        <a href="{{ route('materiais.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 mr-4">
                            Cancelar
                        </a>
                        <x-primary-button>
                            {{ __('Salvar alterações') }}
                        </x-primary-button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
