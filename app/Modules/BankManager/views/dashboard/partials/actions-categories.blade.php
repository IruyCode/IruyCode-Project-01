<div x-data="modalActions()"
    class="flex flex-col md:flex-row justify-center md:justify-end gap-4 px-4 py-4 bg-black rounded-b-xl shadow-lg relative z-10"
    x-cloak>

    <!-- Botão Configurações -->
    <a href="{{ route('bank-manager.settings') }}"
        class="bg-purple-600 text-white px-8 py-4 rounded-full hover:bg-purple-700 transition duration-300 ease-in-out flex items-center justify-center gap-2 text-base font-semibold shadow-md hover:shadow-lg transform hover:scale-105">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 4.5a2.5 2.5 0 015 0v1.379a2.5 2.5 0 010 4.242V11.5a2.5 2.5 0 01-5 0v-1.379a2.5 2.5 0 010-4.242V4.5zM6 8.5a2.5 2.5 0 015 0v7a2.5 2.5 0 01-5 0v-7z" />
        </svg>
        Configurações
    </a>

    <!-- Criar Categoria -->
    <button @click="showCategoriaModal = true"
        class="bg-blue-600 text-white px-8 py-4 rounded-full hover:bg-blue-700 transition duration-300 ease-in-out flex items-center justify-center gap-2 text-base font-semibold shadow-md hover:shadow-lg transform hover:scale-105">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        Criar Categoria
    </button>

    <!-- Criar Subcategoria -->
    <button @click="showSubCategoriaModal = true"
        class="bg-yellow-500 text-white px-8 py-4 rounded-full hover:bg-yellow-600 transition duration-300 ease-in-out flex items-center justify-center gap-2 text-base font-semibold shadow-md hover:shadow-lg transform hover:scale-105">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        Criar Subcategoria
    </button>

    <!-- Criar Transação -->
    <button @click="showTransacaoModal = true"
        class="bg-green-600 text-white px-8 py-4 rounded-full hover:bg-green-700 transition duration-300 ease-in-out flex items-center justify-center gap-2 text-base font-semibold shadow-md hover:shadow-lg transform hover:scale-105">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        Adicionar Transação
    </button>



    <!-- MODAL — Criar Categoria -->
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
                    <input type="text" name="name" class="w-full bg-gray-700 text-white rounded px-3 py-2"
                        required>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" @click="showCategoriaModal = false"
                        class="px-4 py-2 text-gray-300 hover:text-white">Cancelar</button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Salvar</button>
                </div>
            </form>
        </div>
    </div>



    <!-- MODAL — Criar Subcategoria -->
    <div x-show="showSubCategoriaModal" x-transition x-cloak
        class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
        @click.self="showSubCategoriaModal = false">

        <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-white">Criar Nova Subcategoria</h3>
                <button @click="showSubCategoriaModal = false" class="text-gray-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form action="{{ route('bank-manager.operation-subcategories.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-300 mb-2">Categoria Pai</label>
                    <select name="operation_category_id" class="w-full bg-gray-700 text-white rounded px-3 py-2"
                        required>
                        <option value="">Selecionar</option>
                        @foreach ($operationCategories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-300 mb-2">Nome da Subcategoria</label>
                    <input type="text" name="name" class="w-full bg-gray-700 text-white rounded px-3 py-2"
                        required>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" @click="showSubCategoriaModal = false"
                        class="px-4 py-2 text-gray-300 hover:text-white">Cancelar</button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Salvar</button>
                </div>
            </form>
        </div>
    </div>



    <!-- MODAL — Criar Transação -->
    <div x-show="showTransacaoModal" x-transition x-cloak
        class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
        @click.self="showTransacaoModal = false">

        <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4" x-data="bankForm()">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-white">Adicionar Nova Transação</h3>
                <button @click="showTransacaoModal = false" class="text-gray-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form action="{{ route('bank-manager.transactions.store') }}" method="POST">
                @csrf

                <!-- Conta -->
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

                <!-- Tipo -->
                <div class="mb-4">
                    <label class="block text-gray-300 mb-2">Tipo</label>
                    <select x-model="selectedType" name="operation_type_id"
                        class="w-full bg-gray-700 text-white rounded px-3 py-2" required>
                        <option value="">Tipo</option>
                        <template x-for="type in types" :key="type.id">
                            <option :value="type.id" x-text="translateType(type.operation_type)"></option>
                        </template>
                    </select>
                </div>

                <!-- Categoria -->
                <div class="mb-4">
                    <label class="block text-gray-300 mb-2">Categoria</label>
                    <select x-model="selectedCategory" class="w-full bg-gray-700 text-white rounded px-3 py-2"
                        required>
                        <option value="">Categoria</option>
                        <template x-for="category in filteredCategories" :key="category.id">
                            <option :value="category.id" x-text="category.name"></option>
                        </template>
                    </select>
                </div>

                <!-- Subcategoria -->
                <div class="mb-4">
                    <label class="block text-gray-300 mb-2">Subcategoria</label>
                    <select x-model="selectedSubCategory" name="operation_sub_category_id"
                        class="w-full bg-gray-700 text-white rounded px-3 py-2" required>
                        <option value="">Subcategoria</option>
                        <template x-for="sub in filteredSubCategories" :key="sub.id">
                            <option :value="sub.id" x-text="sub.name"></option>
                        </template>
                    </select>
                </div>

                <!-- Valor -->
                <div class="mb-4">
                    <label class="block text-gray-300 mb-2">Valor</label>
                    <input type="number" step="0.01" name="amount" x-model="amount"
                        class="w-full bg-gray-700 text-white rounded px-3 py-2" required>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" @click="showTransacaoModal = false"
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
            showSubCategoriaModal: false,
            showTransacaoModal: false,

            novaCategoria: {
                nome: '',
            }
        }
    }

    function bankForm() {
        return {
            types: @json($operationTypes),
            categories: @json($operationCategories),
            subcategories: @json($operationSubCategories),

            accounts: @json($accounts),

            selectedType: "",
            selectedCategory: "",
            selectedSubCategory: "",
            selectedAccount: "",
            amount: "",

            get filteredCategories() {
                return this.categories;
            },

            get filteredSubCategories() {
                return this.subcategories.filter(s =>
                    s.operation_category_id == this.selectedCategory
                );
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
