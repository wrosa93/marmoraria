<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Serviço: ') . $servico->nome }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">Editar serviço</h3>

                    <form action="{{ route('servicos.update', $servico) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="codigo" :value="__('Código interno')" />
                                <x-text-input id="codigo" class="block mt-1 w-full uppercase" type="text" name="codigo" :value="old('codigo', $servico->codigo)" required />
                                <x-input-error :messages="$errors->get('codigo')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="nome" :value="__('Nome / descrição breve')" />
                                <x-text-input id="nome" class="block mt-1 w-full" type="text" name="nome" :value="old('nome', $servico->nome)" required />
                                <x-input-error :messages="$errors->get('nome')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="tipo_cobranca" :value="__('Tipo de cobrança')" />
                                <select id="tipo_cobranca" name="tipo_cobranca" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                                    @foreach (['area' => 'Por área (m²)', 'perimetro' => 'Por perímetro (ml)', 'peca' => 'Por peça/unidade', 'personalizado' => 'Personalizado'] as $valor => $label)
                                        <option value="{{ $valor }}" {{ old('tipo_cobranca', $servico->tipo_cobranca) === $valor ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('tipo_cobranca')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="unidade_medida" :value="__('Unidade de medida')" />
                                <x-text-input id="unidade_medida" class="block mt-1 w-full uppercase" type="text" name="unidade_medida" :value="old('unidade_medida', $servico->unidade_medida)" required />
                                <x-input-error :messages="$errors->get('unidade_medida')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="descricao" :value="__('Descrição detalhada')" />
                            <textarea id="descricao" name="descricao" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('descricao', $servico->descricao) }}</textarea>
                            <x-input-error :messages="$errors->get('descricao')" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-2">
                            <input id="ativo" name="ativo" type="checkbox" value="1" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-900 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" {{ old('ativo', $servico->ativo) ? 'checked' : '' }}>
                            <label for="ativo" class="text-sm text-gray-700 dark:text-gray-300">Serviço ativo</label>
                        </div>

                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6 space-y-4">
                            <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200">Nova tabela de preço</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Preencha apenas para iniciar uma nova vigência de preço.</p>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <x-input-label for="preco" :value="__('Preço base (R$)')" />
                                    <x-text-input id="preco" class="block mt-1 w-full" type="number" step="0.01" min="0" name="preco" :value="old('preco')" />
                                    <x-input-error :messages="$errors->get('preco')" class="mt-2" />
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

                        <div class="flex items-center justify-end">
                            <a href="{{ route('servicos.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 mr-4">
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
