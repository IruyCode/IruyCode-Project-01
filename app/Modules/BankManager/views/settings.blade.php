@extends('bankmanager::app')

@section('content-component')

    <div class="px-6 py-6" x-data="settingsPage()">


        <h1 class="text-2xl font-bold text-white mb-6">Configurações – Categorias e Subcategorias</h1>

        <!-- CATEGORIAS -->
        <div class="bg-gray-800 p-4 rounded-lg mb-8 shadow-lg">
            <h2 class="text-xl font-semibold text-white mb-4">Categorias</h2>

            @if ($categories->isEmpty())
                <p class="text-gray-300">Nenhuma categoria criada ainda.</p>
            @else
                <table class="w-full text-left text-white">
                    <thead>
                        <tr class="border-b border-gray-600">
                            <th class="py-2">Nome</th>
                            <th class="py-2 w-32">Ações</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($categories as $cat)
                            <tr class="border-b border-gray-700">
                                <td class="py-2">{{ $cat->name }}</td>
                                <td class="py-2 flex gap-2">
                                    <button
                                        @click="openCategoryModal({ id: {{ $cat->id }}, name: '{{ $cat->name }}' })"
                                        class="px-3 py-1 bg-blue-600 rounded hover:bg-blue-700">
                                        Editar
                                    </button>

                                    <form action="/bank-manager/categories/delete/{{ $cat->id }}" method="POST"
                                        onsubmit="return confirm('Tem certeza que deseja apagar esta categoria?')">
                                        @csrf
                                        <button class="px-3 py-1 bg-red-600 rounded hover:bg-red-700">
                                            Apagar
                                        </button>
                                    </form>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- SUBCATEGORIAS (com filtro) -->
        <div class="bg-gray-800 p-4 rounded-lg shadow-lg">

            <h2 class="text-xl font-semibold text-white mb-4">Subcategorias</h2>

            <!-- SELECT DA CATEGORIA -->
            <div class="mb-4">
                <label class="block text-gray-300 mb-2">Selecionar Categoria</label>

                <select x-model="selectedCategory" @change="loadSubcategories"
                    class="w-full bg-gray-700 text-white rounded px-3 py-2">
                    <option value="">Selecione uma categoria</option>

                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- LISTA DE SUBCATEGORIAS -->
            <template x-if="subcategories.length === 0 && selectedCategory">
                <p class="text-gray-300">Nenhuma subcategoria encontrada para esta categoria.</p>
            </template>

            <template x-if="!selectedCategory">
                <p class="text-gray-300">Escolha uma categoria para listar as subcategorias.</p>
            </template>

            <template x-if="subcategories.length > 0">
                <table class="w-full text-left text-white mt-4">
                    <thead>
                        <tr class="border-b border-gray-600">
                            <th class="py-2">Subcategoria</th>
                            <th class="py-2 w-32">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="sub in subcategories" :key="sub.id">
                            <tr class="border-b border-gray-700">
                                <td class="py-2" x-text="sub.name"></td>
                                <td class="py-2 flex gap-2">
                                    <button
                                        @click="openSubModal({ 
                                            id: sub.id, 
                                            name: sub.name, 
                                            operation_category_id: sub.operation_category_id 
                                        })"
                                        class="px-3 py-1 bg-blue-600 rounded hover:bg-blue-700">
                                        Editar
                                    </button>

                                    <form :action="'/bank-manager/subcategories/delete/' + sub.id" method="POST"
                                        onsubmit="return confirm('Tem certeza que deseja apagar esta subcategoria?')">
                                        @csrf
                                        <button class="px-3 py-1 bg-red-600 rounded hover:bg-red-700">
                                            Apagar
                                        </button>
                                    </form>

                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </template>

        </div>

        <!-- Modal Editar Categoria -->
        <div x-show="showEditCategoryModal" x-transition x-cloak
            class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
            @click.self="showEditCategoryModal = false">

            <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-white">Editar Categoria</h3>
                    <button @click="showEditCategoryModal = false" class="text-gray-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form :action="'/bank-manager/categories/update/' + editCategory.id" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-gray-300 mb-2">Nome da Categoria</label>
                        <input type="text" x-model="editCategory.name" name="name"
                            class="w-full bg-gray-700 text-white rounded px-3 py-2" required>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" @click="showEditCategoryModal = false"
                            class="px-4 py-2 text-gray-300 hover:text-white">
                            Cancelar
                        </button>

                        <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Editar Subcategoria -->
        <div x-show="showEditSubCategoryModal" x-transition x-cloak
            class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
            @click.self="showEditSubCategoryModal = false">

            <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-white">Editar Subcategoria</h3>
                    <button @click="showEditSubCategoryModal = false" class="text-gray-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form :action="'/bank-manager/subcategories/update/' + editSubCategory.id" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-gray-300 mb-2">Categoria Pai</label>
                        <select name="operation_category_id" x-model="editSubCategory.category_id"
                            class="w-full bg-gray-700 text-white rounded px-3 py-2">
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-300 mb-2">Nome da Subcategoria</label>
                        <input type="text" x-model="editSubCategory.name" name="name"
                            class="w-full bg-gray-700 text-white rounded px-3 py-2" required>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" @click="showEditSubCategoryModal = false"
                            class="px-4 py-2 text-gray-300 hover:text-white">
                            Cancelar
                        </button>

                        <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>



    </div>

    <script>
        function settingsPage() {
            return {

                // Modais de Edição
                showEditCategoryModal: false,
                showEditSubCategoryModal: false,

                editCategory: {
                    id: null,
                    name: ""
                },

                editSubCategory: {
                    id: null,
                    name: "",
                    category_id: ""
                },


                // Subcategorias com Filtro
                selectedCategory: "",
                subcategories: [],

                async loadSubcategories() {
                    if (!this.selectedCategory) {
                        this.subcategories = [];
                        return;
                    }

                    const url = `/bank-manager/api/subcategories/${this.selectedCategory}`;
                    const res = await fetch(url);
                    this.subcategories = await res.json();
                },



                // Modal Edit Openers
                openCategoryModal(cat) {
                    this.editCategory.id = cat.id;
                    this.editCategory.name = cat.name;
                    this.showEditCategoryModal = true;
                },

                openSubModal(sub) {
                    this.editSubCategory.id = sub.id;
                    this.editSubCategory.name = sub.name;
                    this.editSubCategory.category_id = sub.operation_category_id;
                    this.showEditSubCategoryModal = true;
                },

            };
        }
    </script>

@endsection
