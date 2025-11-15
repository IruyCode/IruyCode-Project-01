<div x-show="activeEditInvestmentsId === {{ $investment->id }}" x-transition
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" x-cloak>
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg w-full max-w-xl">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">Editar Investimento</h2>
            <button @click="closeEditModal()" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('bank-manager.investments.edit', $investment->id) }}">
            @csrf
            <div class="mb-4">
                <label for="name"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nome</label>
                <input type="text" id="name" name="name" value="{{ old('name', $investment->name) }}"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                    required>
            </div>

            <div class="mb-4">
                <label for="platform"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Plataforma</label>
                <input type="text" id="platform" name="platform"
                    value="{{ old('platform', $investment->platform) }}"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                    required>
            </div>

            <div class="mb-4">
                <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo de
                    Investimento</label>
                <select id="type" name="type"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                    required>
                    @php
                        $types = ['Renda Fixa', 'Ações', 'Fundos Imobiliários'];
                    @endphp
                    @foreach ($types as $type)
                        <option value="{{ $type }}" @selected(old('type', $investment->type) === $type)>
                            {{ $type }}
                        </option>
                    @endforeach
                    <option value="" @selected(empty($investment->type))>Outro / Não especificado
                    </option>
                </select>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" @click="closeEditModal()"
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</div>
