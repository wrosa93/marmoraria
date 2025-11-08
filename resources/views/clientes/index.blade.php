<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __("Clientes") }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-6">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Lista de Clientes</h3>
                                <div class="flex flex-col gap-4 md:flex-row md:items-center">
                                    <form method="GET" action="{{ route('clientes.index') }}" class="flex items-center">
                                        <input
                                            type="text"
                                            name="q"
                                            value="{{ $busca }}"
                                            placeholder="Buscar por nome, documento ou email..."
                                            class="rounded-l-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                        >
                                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-xs font-semibold uppercase rounded-r-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                            Buscar
                                        </button>
                                    </form>
                                    <a href="{{ route('clientes.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                        Novo Cliente
                                    </a>
                                </div>
                            </div>

                            @if (session('success'))
                                <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-200 rounded" role="alert">
                                    <strong class="font-bold">Sucesso!</strong>
                                    <span class="block sm:inline">{{ session('success') }}</span>
                                </div>
                            @endif
                            @if (session('error'))
                                <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-200 rounded" role="alert">
                                    <strong class="font-bold">Erro!</strong>
                                    <span class="block sm:inline">{{ session('error') }}</span>
                                </div>
                            @endif

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nome</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tipo</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Documento</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Contato</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cidade/UF</th>
                                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @forelse ($clientes as $cliente)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $cliente->nome }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 uppercase">{{ $cliente->tipo_cliente }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $cliente->documento ?? '-' }}</td>
                                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">
                                                    <div>{{ $cliente->telefone ?? '-' }}</div>
                                                    @if ($cliente->telefone_secundario)
                                                        <div class="text-xs text-gray-400 dark:text-gray-500">{{ $cliente->telefone_secundario }}</div>
                                                    @endif
                                                    <div class="text-xs text-gray-400 dark:text-gray-500">{{ $cliente->email ?? '-' }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                    {{ $cliente->cidade ?? '-' }}{{ $cliente->estado ? ' / ' . $cliente->estado : '' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-3">
                                                    <a href="{{ route('clientes.show', $cliente) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">Ver</a>
                                                    <a href="{{ route('clientes.edit', $cliente) }}" class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-900 dark:hover:text-yellow-300">Editar</a>
                                                    <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" class="inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300" onclick="return confirm('Tem certeza que deseja remover este cliente?')">
                                                            Remover
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">
                                                    Nenhum cliente cadastrado.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-6">
                                {{ $clientes->links() }}
                            </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
