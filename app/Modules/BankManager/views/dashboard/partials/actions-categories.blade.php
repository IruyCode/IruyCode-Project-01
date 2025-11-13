    <div x-data="modalActions()"
        class="flex flex-col md:flex-row justify-center md:justify-end gap-4 px-4 py-4 bg-black rounded-b-xl shadow-lg relative z-10"
        x-cloak>
        <!-- Botão Criar Categoria -->
        <button @click="showCategoriaModal = true"
            class="bg-blue-600 text-white px-8 py-4 rounded-full hover:bg-blue-700 transition duration-300 ease-in-out flex items-center justify-center gap-2 text-base font-semibold shadow-md hover:shadow-lg transform hover:scale-105">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Criar Categorias
        </button>

        <!-- Botão Adicionar Dados -->
        <button @click="showDadosModal = true"
            class="bg-green-600 text-white px-8 py-4 rounded-full hover:bg-green-700 transition duration-300 ease-in-out flex items-center justify-center gap-2 text-base font-semibold shadow-md hover:shadow-lg transform hover:scale-105">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Adicionar Dados
        </button>


        <!-- Modal Criar Categorias -->
        <div x-show="showCategoriaModal" x-transition x-cloak
            class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
            @click.self="showCategoriaModal = false">
            <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-white">Criar Nova Categoria</h3>
                    <button @click="showCategoriaModal = false" class="text-gray-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form action="{{ route('bank-manager.operation-categories.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-gray-300 mb-2">Nome da Categoria</label>
                        <input type="text" name="name" x-model="novaCategoria.nome"
                            class="w-full bg-gray-700 text-white rounded px-3 py-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-300 mb-2">Tipo</label>
                        <select name="operation_type" x-model="novaCategoria.tipo"
                            class="w-full bg-gray-700 text-white rounded px-3 py-2" required>
                            <option value="income">Receita</option>
                            <option value="expense">Despesa</option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" @click="showCategoriaModal = false"
                            class="px-4 py-2 text-gray-300 hover:text-white">
                            Cancelar
                        </button>

                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>


        <!-- Modal Adicionar Dados -->
        <div x-show="showDadosModal" x-transition x-cloak
            class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
            @click.self="showDadosModal = false">
            <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4" x-data="bankForm()">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-white">Adicionar Nova Transação</h3>
                    <button @click="showDadosModal = false" class="text-gray-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form action="{{ route('bank-manager.transactions.store') }}" method="POST">
                    @csrf

                    <!-- SELECT DA CONTA -->
                    <div class="mb-4">
                        <label class="block text-gray-300 mb-2">Conta</label>
                        <select x-model="selectedAccount" name="account_balance_id"
                            class="w-full bg-gray-700 text-white rounded px-3 py-2" required>
                            <option value="">Selecionar Conta</option>

                            <template x-for="acc in accounts" :key="acc.id">
                                <option :value="acc.id" x-text="acc.account_name + ' (' + acc.bank_name + ')'">
                                </option>
                            </template>
                        </select>
                    </div>

                    <!-- TIPO -->
                    <div class="mb-4">
                        <label class="block text-gray-300 mb-2">Tipo</label>
                        <select x-model="selectedType" name="operation_type" @change="updateCategories"
                            class="w-full bg-gray-700 text-white rounded px-3 py-2" required>
                            <option value="">Tipo</option>
                            <template x-for="type in types" :key="type.id">
                                <option :value="type.id" x-text="translateType(type.operation_type)"></option>
                            </template>
                        </select>
                    </div>

                    <!-- CATEGORIA -->
                    <div class="mb-4">
                        <label class="block text-gray-300 mb-2">Categoria</label>
                        <select x-model="selectedCategory" name="operation_category_id"
                            class="w-full bg-gray-700 text-white rounded px-3 py-2" required>
                            <option value="">Categoria</option>
                            <template x-for="category in filteredCategories" :key="category.id">
                                <option :value="category.id" x-text="category.name"></option>
                            </template>
                        </select>
                    </div>

                    <!-- VALOR -->
                    <div class="mb-4">
                        <label class="block text-gray-300 mb-2">Valor</label>
                        <input type="number" step="0.01" name="amount" x-model="amount"
                            class="w-full bg-gray-700 text-white rounded px-3 py-2" required>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" @click="showDadosModal = false"
                            class="px-4 py-2 text-gray-300 hover:text-white">Cancelar</button>

                        <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Salvar</button>
                    </div>

                </form>
            </div>
        </div>
    </div>


    <script>
        function modalActions() {
            return {
                showCategoriaModal: false,
                showDadosModal: false,
                novaCategoria: {
                    nome: '',
                    tipo: 'income'
                }
            }
        }

        function bankForm() {
            return {
                types: @json($operationTypes),
                categories: @json($operationCategoriesFilter),
                accounts: @json($accounts),

                selectedType: "",
                selectedCategory: "",
                selectedAccount: "",
                amount: "",

                get filteredCategories() {
                    return this.categories.filter(c => c.operation_type_id == this.selectedType);
                },

                updateCategories() {
                    this.selectedCategory = "";
                },

                translateType(type) {
                    return type === "income" ?
                        "Receita" :
                        type === "expense" ?
                        "Despesa" :
                        type;
                }
            };
        }
    </script>
