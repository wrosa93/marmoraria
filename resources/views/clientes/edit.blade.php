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

                            <form action="{{ route('clientes.update', $cliente) }}" method="POST" class="space-y-6">
                                @csrf
                                @method('PUT')

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <x-input-label for="nome" :value="__('Nome / Razão Social')" />
                                        <x-text-input id="nome" class="block mt-1 w-full" type="text" name="nome" :value="old('nome', $cliente->nome)" required autofocus />
                                        <x-input-error :messages="$errors->get('nome')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="tipo_cliente" :value="__('Tipo de Cliente')" />
                                        <select id="tipo_cliente" name="tipo_cliente" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                            <option value="pf" {{ old('tipo_cliente', $cliente->tipo_cliente) === 'pf' ? 'selected' : '' }}>Pessoa Física</option>
                                            <option value="pj" {{ old('tipo_cliente', $cliente->tipo_cliente) === 'pj' ? 'selected' : '' }}>Pessoa Jurídica</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('tipo_cliente')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="documento" :value="__('CPF/CNPJ')" />
                                        <x-text-input id="documento" class="block mt-1 w-full" type="text" name="documento" :value="old('documento', $cliente->documento)" />
                                        <x-input-error :messages="$errors->get('documento')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="email" :value="__('Email')" />
                                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $cliente->email)" />
                                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="telefone" :value="__('Telefone principal')" />
                                        <x-text-input id="telefone" class="block mt-1 w-full" type="tel" name="telefone" :value="old('telefone', $cliente->telefone)" />
                                        <x-input-error :messages="$errors->get('telefone')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="telefone_secundario" :value="__('Telefone secundário')" />
                                        <x-text-input id="telefone_secundario" class="block mt-1 w-full" type="tel" name="telefone_secundario" :value="old('telefone_secundario', $cliente->telefone_secundario)" />
                                        <x-input-error :messages="$errors->get('telefone_secundario')" class="mt-2" />
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div class="md:col-span-2">
                                        <x-input-label for="endereco" :value="__('Endereço')" />
                                        <x-text-input id="endereco" class="block mt-1 w-full" type="text" name="endereco" :value="old('endereco', $cliente->endereco)" />
                                        <x-input-error :messages="$errors->get('endereco')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="numero" :value="__('Número')" />
                                        <x-text-input id="numero" class="block mt-1 w-full" type="text" name="numero" :value="old('numero', $cliente->numero)" />
                                        <x-input-error :messages="$errors->get('numero')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="complemento" :value="__('Complemento')" />
                                        <x-text-input id="complemento" class="block mt-1 w-full" type="text" name="complemento" :value="old('complemento', $cliente->complemento)" />
                                        <x-input-error :messages="$errors->get('complemento')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="bairro" :value="__('Bairro')" />
                                        <x-text-input id="bairro" class="block mt-1 w-full" type="text" name="bairro" :value="old('bairro', $cliente->bairro)" />
                                        <x-input-error :messages="$errors->get('bairro')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="cidade" :value="__('Cidade')" />
                                        <x-text-input id="cidade" class="block mt-1 w-full" type="text" name="cidade" :value="old('cidade', $cliente->cidade)" />
                                        <x-input-error :messages="$errors->get('cidade')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="estado" :value="__('UF')" />
                                        <x-text-input id="estado" class="block mt-1 w-full uppercase" type="text" name="estado" maxlength="2" :value="old('estado', $cliente->estado)" />
                                        <x-input-error :messages="$errors->get('estado')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="cep" :value="__('CEP')" />
                                        <x-text-input id="cep" class="block mt-1 w-full" type="text" name="cep" :value="old('cep', $cliente->cep)" />
                                        <x-input-error :messages="$errors->get('cep')" class="mt-2" />
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="observacoes" :value="__('Observações')" />
                                    <textarea id="observacoes" name="observacoes" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('observacoes', $cliente->observacoes) }}</textarea>
                                    <x-input-error :messages="$errors->get('observacoes')" class="mt-2" />
                                </div>

                                <div class="flex items-center justify-end">
                                    <a href="{{ route('clientes.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 mr-4">
                                        Cancelar
                                    </a>
                                    <x-primary-button>
                                        {{ __('Salvar Alterações') }}
                                    </x-primary-button>
                                </div>
                            </form>
                        </div>
                    </div>
        </div>
    </div>
</x-app-layout>
