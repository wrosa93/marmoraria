<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __("Editar Orçamento #") . $orcamento->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Detalhes do Orçamento --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Detalhes do Orçamento</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        <strong>Cliente:</strong> {{ $orcamento->cliente->nome }}
                    </p>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        <strong>Data:</strong> {{ $orcamento->data->format("d/m/Y") }}
                    </p>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        <strong>Total Atual:</strong> R$ {{ number_format($orcamento->items->sum("subtotal"), 2, ",", ".") }}
                    </p>
                    <div class="mt-4 flex space-x-4">
                         <a href="{{ route("orcamentos.index") }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                            Voltar para Lista
                        </a>
                        <a href="{{ route("orcamentos.pdf", $orcamento) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Gerar PDF
                        </a>
                    </div>
                </div>
            </div>

            {{-- Adicionar Item ao Orçamento --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Adicionar Item</h3>
                    <form action="{{ route("orcamentos.items.store", $orcamento) }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Tipo de Item --}}
                            <div>
                                <x-input-label for="tipo_item" :value="__("Tipo de Item")" />
                                <select id="tipo_item" name="tipo_item" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required onchange="toggleItemFields(this.value)">
                                    <option value="" disabled selected>Selecione...</option>
                                    <option value="material" {{ old("tipo_item") == "material" ? "selected" : "" }}>Material</option>
                                    <option value="servico" {{ old("tipo_item") == "servico" ? "selected" : "" }}>Serviço</option>
                                </select>
                            </div>

                            {{-- Campo Material (oculto por padrão) --}}
                            <div id="material_fields" style="display: {{ old("tipo_item") == "material" ? "block" : "none" }};">
                                <x-input-label for="material_id" :value="__("Material")" />
                                <select id="material_id" name="material_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="" disabled selected>Selecione...</option>
                                    @foreach ($materiais as $material)
                                        <option value="{{ $material->id }}" data-preco="{{ $material->preco_m2 }}" {{ old("material_id") == $material->id ? "selected" : "" }}>
                                            {{ $material->nome }} (R$ {{ number_format($material->preco_m2, 2, ",", ".") }}/m²)
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get("material_id")" class="mt-2" />
                            </div>

                            {{-- Campo Serviço (oculto por padrão) --}}
                            <div id="servico_fields" style="display: {{ old("tipo_item") == "servico" ? "block" : "none" }};">
                                <x-input-label for="servico_id" :value="__("Serviço")" />
                                <select id="servico_id" name="servico_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="" disabled selected>Selecione...</option>
                                    @foreach ($servicos as $servico)
                                        <option value="{{ $servico->id }}" data-preco="{{ $servico->preco_unitario }}" {{ old("servico_id") == $servico->id ? "selected" : "" }}>
                                            {{ $servico->descricao }} (R$ {{ number_format($servico->preco_unitario, 2, ",", ".") }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get("servico_id")" class="mt-2" />
                            </div>

                            {{-- Quantidade --}}
                            <div>
                                <x-input-label for="quantidade" :value="__("Quantidade")" />
                                <x-text-input id="quantidade" class="block mt-1 w-full" type="number" step="0.01" name="quantidade" :value="old("quantidade", 1)" required />
                                <x-input-error :messages="$errors->get("quantidade")" class="mt-2" />
                            </div>

                             {{-- Largura (apenas para material) --}}
                            <div id="largura_field" style="display: {{ old("tipo_item") == "material" ? "block" : "none" }};">
                                <x-input-label for="largura_m" :value="__("Largura (m)")" />
                                <x-text-input id="largura_m" class="block mt-1 w-full" type="number" step="0.01" name="largura_m" :value="old("largura_m")" />
                                <x-input-error :messages="$errors->get("largura_m")" class="mt-2" />
                            </div>

                            {{-- Comprimento (apenas para material) --}}
                            <div id="comprimento_field" style="display: {{ old("tipo_item") == "material" ? "block" : "none" }};">
                                <x-input-label for="comprimento_m" :value="__("Comprimento (m)")" />
                                <x-text-input id="comprimento_m" class="block mt-1 w-full" type="number" step="0.01" name="comprimento_m" :value="old("comprimento_m")" />
                                <x-input-error :messages="$errors->get("comprimento_m")" class="mt-2" />
                            </div>

                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __("Adicionar Item") }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Itens do Orçamento --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Itens do Orçamento</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Descrição</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Detalhes</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Qtd</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Preço Unit.</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Subtotal</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ação</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($orcamento->items as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $item->material->nome ?? $item->servico->descricao }}
                                    </td>
                                     <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        @if ($item->material)
                                            {{ $item->largura_m }}m x {{ $item->comprimento_m }}m
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $item->quantidade }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">R$ {{ number_format($item->preco_unitario, 2, ",", ".") }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">R$ {{ number_format($item->subtotal, 2, ",", ".") }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <form action="{{ route("orcamentos.items.destroy", [$orcamento, $item]) }}" method="POST">
                                            @csrf
                                            @method("DELETE")
                                            <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300" onclick="return confirm("Tem certeza que deseja remover este item?")">
                                                Remover
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">
                                        Nenhum item adicionado a este orçamento.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleItemFields(tipo) {
            const materialFields = document.getElementById("material_fields");
            const servicoFields = document.getElementById("servico_fields");
            const larguraField = document.getElementById("largura_field");
            const comprimentoField = document.getElementById("comprimento_field");
            const materialSelect = document.getElementById("material_id");
            const servicoSelect = document.getElementById("servico_id");
            const quantidadeInput = document.getElementById("quantidade");
            const larguraInput = document.getElementById("largura_m");
            const comprimentoInput = document.getElementById("comprimento_m");

            if (tipo === "material") {
                materialFields.style.display = "block";
                servicoFields.style.display = "none";
                larguraField.style.display = "block";
                comprimentoField.style.display = "block";
                materialSelect.required = true;
                servicoSelect.required = false;
                servicoSelect.value = ""; // Limpa seleção de serviço
                larguraInput.required = true;
                comprimentoInput.required = true;
                quantidadeInput.step = "0.01"; // Permite decimais para m²
                quantidadeInput.value = "1"; // Reseta quantidade
            } else if (tipo === "servico") {
                materialFields.style.display = "none";
                servicoFields.style.display = "block";
                larguraField.style.display = "none";
                comprimentoField.style.display = "none";
                materialSelect.required = false;
                servicoSelect.required = true;
                materialSelect.value = ""; // Limpa seleção de material
                larguraInput.required = false;
                comprimentoInput.required = false;
                larguraInput.value = "";
                comprimentoInput.value = "";
                quantidadeInput.step = "1"; // Apenas inteiros para serviços
                quantidadeInput.value = "1"; // Reseta quantidade
            } else {
                materialFields.style.display = "none";
                servicoFields.style.display = "none";
                larguraField.style.display = "none";
                comprimentoField.style.display = "none";
                materialSelect.required = false;
                servicoSelect.required = false;
                larguraInput.required = false;
                comprimentoInput.required = false;
            }
        }

        // Chama a função no carregamento da página caso haja old input
        document.addEventListener("DOMContentLoaded", function() {
            const tipoInicial = document.getElementById("tipo_item").value;
            if (tipoInicial) {
                toggleItemFields(tipoInicial);
            }
        });
    </script>
</x-app-layout>
