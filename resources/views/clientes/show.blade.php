<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalhes do Cliente: ') . $cliente->nome }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">
                        Detalhes do Cliente: {{ $cliente->nome }}
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <span class="block text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Nome / Razão Social</span>
                            <p class="mt-1 text-base text-gray-900 dark:text-gray-100">{{ $cliente->nome }}</p>
                        </div>
                        <div>
                            <span class="block text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Tipo</span>
                            <p class="mt-1 text-base text-gray-900 dark:text-gray-100">{{ strtoupper($cliente->tipo_cliente) }}</p>
                        </div>
                        <div>
                            <span class="block text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Documento</span>
                            <p class="mt-1 text-base text-gray-900 dark:text-gray-100">{{ $cliente->documento ?? 'Não informado' }}</p>
                        </div>
                        <div>
                            <span class="block text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Email</span>
                            <p class="mt-1 text-base text-gray-900 dark:text-gray-100">{{ $cliente->email ?? 'Não informado' }}</p>
                        </div>
                        <div>
                            <span class="block text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Telefone principal</span>
                            <p class="mt-1 text-base text-gray-900 dark:text-gray-100">{{ $cliente->telefone ?? 'Não informado' }}</p>
                        </div>
                        <div>
                            <span class="block text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Telefone secundário</span>
                            <p class="mt-1 text-base text-gray-900 dark:text-gray-100">{{ $cliente->telefone_secundario ?? 'Não informado' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <span class="block text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Endereço</span>
                            <p class="mt-1 text-base text-gray-900 dark:text-gray-100">
                                {{ $cliente->endereco ?? 'Não informado' }}
                                @if ($cliente->numero)
                                    , {{ $cliente->numero }}
                                @endif
                                @if ($cliente->complemento)
                                    - {{ $cliente->complemento }}
                                @endif
                            </p>
                        </div>
                        <div>
                            <span class="block text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Bairro</span>
                            <p class="mt-1 text-base text-gray-900 dark:text-gray-100">{{ $cliente->bairro ?? 'Não informado' }}</p>
                        </div>
                        <div>
                            <span class="block text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Cidade / UF</span>
                            <p class="mt-1 text-base text-gray-900 dark:text-gray-100">
                                {{ $cliente->cidade ?? 'Não informado' }}{{ $cliente->estado ? ' / ' . $cliente->estado : '' }}
                            </p>
                        </div>
                        <div>
                            <span class="block text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">CEP</span>
                            <p class="mt-1 text-base text-gray-900 dark:text-gray-100">{{ $cliente->cep ?? 'Não informado' }}</p>
                        </div>
                        <div>
                            <span class="block text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Data de cadastro</span>
                            <p class="mt-1 text-base text-gray-900 dark:text-gray-100">{{ $cliente->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <span class="block text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Observações</span>
                            <p class="mt-1 text-base text-gray-900 dark:text-gray-100">{{ $cliente->observacoes ?? 'Não informado' }}</p>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200 mb-4">Orçamentos associados</h4>
                        @forelse ($cliente->orcamentos as $orcamento)
                            <div class="mb-3 p-4 border border-gray-200 dark:border-gray-700 rounded-md">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Orçamento</p>
                                        <p class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $orcamento->numero }} • {{ $orcamento->status }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $orcamento->data->format('d/m/Y') }}</p>
                                    </div>
                                    <div class="mt-3 md:mt-0">
                                        <a href="{{ route('orcamentos.show', $orcamento) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 text-sm font-semibold">
                                            Ver orçamento
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400">Nenhum orçamento vinculado.</p>
                        @endforelse
                    </div>

                    <div class="flex items-center justify-end mt-6 space-x-4">
                        <a href="{{ route('clientes.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Voltar para Lista
                        </a>
                        <a href="{{ route('clientes.edit', $cliente) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-400 focus:bg-yellow-400 active:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Editar
                        </a>
                        <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <x-danger-button type="submit" onclick="return confirm('Tem certeza que deseja remover este cliente?')">
                                Remover
                            </x-danger-button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
