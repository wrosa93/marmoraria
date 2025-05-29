<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __("Editar Cliente: ") . $cliente->nome }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">Editar Cliente: {{ $cliente->nome }}</h3>

                    {{-- Formulário --}}
                    <form action="{{ route("clientes.update", $cliente) }}" method="POST">
                        @csrf
                        @method("PUT")

                        {{-- Nome --}}
                        <div class="mb-4">
                            <x-input-label for="nome" :value="__("Nome Completo")" />
                            <x-text-input id="nome" class="block mt-1 w-full" type="text" name="nome" :value="old("nome", $cliente->nome)" required autofocus />
                            <x-input-error :messages="$errors->get("nome")" class="mt-2" />
                        </div>

                        {{-- Email --}}
                        <div class="mb-4">
                            <x-input-label for="email" :value="__("Email (Opcional)")" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old("email", $cliente->email)" />
                            <x-input-error :messages="$errors->get("email")" class="mt-2" />
                        </div>

                        {{-- Telefone --}}
                        <div class="mb-4">
                            <x-input-label for="telefone" :value="__("Telefone (Opcional)")" />
                            <x-text-input id="telefone" class="block mt-1 w-full" type="tel" name="telefone" :value="old("telefone", $cliente->telefone)" />
                            <x-input-error :messages="$errors->get("telefone")" class="mt-2" />
                        </div>

                         {{-- Endereço --}}
                        <div class="mb-4">
                            <x-input-label for="endereco" :value="__("Endereço (Opcional)")" />
                            <x-text-input id="endereco" class="block mt-1 w-full" type="text" name="endereco" :value="old("endereco", $cliente->endereco)" />
                            <x-input-error :messages="$errors->get("endereco")" class="mt-2" />
                        </div>

                        {{-- Botões --}}
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route("clientes.index") }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150 mr-4">
                                Cancelar
                            </a>
                            <x-primary-button>
                                {{ __("Salvar Alterações") }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
