<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('accountManager', () => ({
            // Controle das modais
            openCreateAccount: false,
            activeEditAccountId: null,
            activeDeleteAccountId: null,

            // Inicialização
            init() {
                // qualquer inicialização futura
            },

            // Abre modal de edição para o id informado
            openEditAccount(id) {
                this.activeEditAccountId = id;
                // Fecha criação caso esteja aberta
                this.openCreateAccount = false;
                // Garante que a modal de delete esteja fechada
                this.activeDeleteAccountId = null;
            },

            // Fecha a modal de edição
            closeEditAccount() {
                this.activeEditAccountId = null;
            },

            // Abre modal de delete
            openDeleteAccount(id) {
                this.activeDeleteAccountId = id;
                this.openCreateAccount = false;
                this.activeEditAccountId = null;
            },

            // Fecha modal delete
            closeDeleteAccount() {
                this.activeDeleteAccountId = null;
            },

            // Abre modal criar
            openCreate() {
                this.openCreateAccount = true;
                this.activeEditAccountId = null;
                this.activeDeleteAccountId = null;
            },

            // Fecha modal criar
            closeCreate() {
                this.openCreateAccount = false;
            }
        }));
    });
</script>
